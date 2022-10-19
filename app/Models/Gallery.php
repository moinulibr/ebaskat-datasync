<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AdminLog;
class Gallery extends Model
{
    use AdminLog;
    
    protected $fillable = ['product_id','photo','ds_photo_id','name'];
    public $timestamps = false;
}
