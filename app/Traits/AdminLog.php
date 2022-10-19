<?php

namespace App\Traits;

use App\Models\AdminLogHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait AdminLog
{
    /**
     * Handle model event
     */
    public static function bootAdminLog()
    {
        /**
         * Data creating and updating event
         */
        
        if(static::activeUserGuard() == 'admin'){ //check admin guard
            static::saved(function ($model) {
                // create or update?
                if ($model->wasRecentlyCreated) {
                    static::storeLog($model, static::class, 'CREATED');
                } else {
                    if (!$model->getChanges()) {
                        return;
                    }
                    static::storeLog($model, static::class, 'UPDATED');
                }
            });
    
            /**
             * Data deleting event
             */
            static::deleted(function (Model $model) {
                static::storeLog($model, static::class, 'DELETED');
            });
        }
        
    }

    /**
     * Generate the model name
     * @param  Model  $model
     * @return string
     */
    public static function getTagName(Model $model)
    {
        return !empty($model->tagName) ? $model->tagName : Str::title(Str::snake(class_basename($model), ' '));
    }

    /**
     * Retrieve the current login user id
     * @return int|string|null
     */
    public static function activeUserId()
    {
        return Auth::guard('admin')->id();
        //return Auth::guard(static::activeUserGuard())->id();// for dynamic guard

    }

    /**
     * Retrieve the current login user guard name
     * @return mixed|null
     */
    public static function activeUserGuard()
    {
        foreach (array_keys(config('auth.guards')) as $guard) {

            if (auth()->guard($guard)->check()) {
                return $guard;
            }
        }
        return null;
    }

    /**
     * Store model logs
     * @param $model
     * @param $modelPath
     * @param $action
     */
    public static function storeLog($model, $modelPath, $action)
    {
        
        $newValues = null;
        $oldValues = null;
        if ($action === 'CREATED') {
            $newValues = $model->getAttributes();
        } elseif ($action === 'UPDATED') {
            $newValues = $model->getChanges();
        }

        if ($action !== 'CREATED') {
            $oldValues = $model->getOriginal();
        }

        $activityTracking					=	new AdminLogHistory();
		$activityTracking->admin_user_id	=	static::activeUserId();
		$activityTracking->module_name 		=	static::getTagName($model);
		$activityTracking->activity_label	=	$action;
		$activityTracking->history			=	!empty($newValues) ? json_encode($newValues) : null;
		$activityTracking->old_history		=   !empty($oldValues) ? json_encode($oldValues) : null;
		$activityTracking->ip_address		=   request()->ip();
		$activityTracking->mac_address		=   substr(exec('getmac'), 0, 17);
		$activityTracking->user_agent		=   request()->header('User-Agent');
		$activityTracking->save();
    }

}