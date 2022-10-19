<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AdminLog;
use Illuminate\Database\Eloquent\Collection;

class Permission
{
    use AdminLog;

    protected $permissions;

    public function __construct()
    {
        $this->permissions = json_decode(json_encode(config('permissions')), FALSE);
    }

    public function all(){
        return $this->permissions;
    }

    public function checkPermissionExist($permission)
    {
        $permission_col = collect(config('permissions'));
        return in_array(explode('|', $permission)[0], $permission_col->pluck('name')->toArray());
    }
}
