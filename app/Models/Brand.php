<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\VendorInformation;
use App\Models\BrandMerchant;
class Brand extends Model
{
    protected $fillable = ['name','logo','slug','web_address','email','created_by','updated_at'];

    //public $timestamps = false;

    /* public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_replace(' ', '-', $value);
    } */


    public function merchants()
    {
        return $this->belongsToMany(VendorInformation::class,'brand_merchant','brand_id', 'vendor_information_id')
                        ->withPivot('deleted_at')->withTimestamps();
    }
}
