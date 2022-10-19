<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLogHistory extends Model
{


    protected $fillable =[
        'admin_user_id','module_name','activity_label','history','old_history','ip_address','mac_address','user_agent'
    ];

    public function admin(){
        return $this->hasOne('App\Models\Admin', 'id', 'admin_user_id')->select('id', 'name');
	}

}
