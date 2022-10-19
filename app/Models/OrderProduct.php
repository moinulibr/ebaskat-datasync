<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $fillable = ['order_package_id','product_id','product_quantity','per_product_price','per_product_commission','per_product_discount','coupon_discount','product_status','delivery_status','cart','ds_order_no','ds_order_data'];


    /**
     * get products details by product id from products table
     */
    public function products()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

}
