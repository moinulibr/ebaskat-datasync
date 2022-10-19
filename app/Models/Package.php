<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AdminLog;
class Package extends Model
{
    use AdminLog;
    
    protected $fillable = ['user_id', 'title', 'subtitle', 'price'];

    public $timestamps = false;

}