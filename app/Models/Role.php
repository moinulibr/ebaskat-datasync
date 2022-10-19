<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AdminLog;
use Illuminate\Support\Facades\Auth;

class Role extends Model
{
    use AdminLog;
    
    protected $fillable = ['name', 'permissions'];

    public $timestamps = false;

    public function admins()
    {
        return $this->hasMany('App\Models\Admin');
    }


    public function sectionCheck($value)
    {
        $sections = explode(" , ", $this->section);
        if (in_array($value, $sections)) {
            return true;
        } else {
            return false;
        }
    }

    public function permissionCheck($value)
    {
        // explode permission string
        // retrive permission from database as an array
        // fisrt match permission 0 index
        //  if match and permission array have more than 1 value then check untile the last 
        //  return true if last chaeck return true

        if (Auth::guard('admin')->user()->id == 1) {
            return true;
        }

        // explode permission string
        $permissions = explode('|', $value);

        // retrive permission from database as an array
        $allowed = json_decode($this->permissions, 1);

        if (array_key_exists($permissions[0], $allowed ?? [])) {
            if (count($permissions) < 2) {
                return true;
            }

            //checks if, asked more permission but have 0 
            if (count($allowed[$permissions[0]] ?? []) == 0) {
                return false;
            }

            // else

            // check requsted permissing
            for ($i = 1; $i < count($permissions); $i++) {

                if (!in_array($permissions[$i], $allowed[$permissions[0]])) {
                    // missing permission
                    return false;
                }
            }
            // have all permission
            return true;
        } else {
            return false;
        }
    }

    public function permissionVerifyForEdit($value)
    {
        // explode permission string
        // retrive permission from database as an array
        // fisrt match permission 0 index
        //  if match and permission array have more than 1 value then check untile the last 
        //  return true if last chaeck return true

        // explode permission string
        $permissions = explode('|', $value);

        // retrive permission from database as an array
        $allowed = json_decode($this->permissions, 1);

        if (array_key_exists($permissions[0], $allowed ?? [])) {
            if (count($permissions) < 2) {
                return true;
            }

            //checks if, asked more permission but have 0 
            if (count($allowed[$permissions[0]] ?? []) == 0) {
                return false;
            }

            // else

            // check requsted permissing
            for ($i = 1; $i < count($permissions); $i++) {

                if (!in_array($permissions[$i], $allowed[$permissions[0]])) {
                    // missing permission
                    return false;
                }
            }
            // have all permission
            return true;
        } else {
            return false;
        }
    }
}
