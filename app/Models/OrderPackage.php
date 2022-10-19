<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPackage extends Model
{
    protected $fillable = ['merchant_id','order_id','order_package_number','delivery_status','payment_status','delivery_tracking_code','note','alix_order_id','alix_order_data'];



    public function orderProducts()
    {
        return $this->hasMany('App\Models\OrderProduct','order_package_id','id');
    }


    public function merchantInfo()
    {
        return $this->hasOne('App\Models\VendorInformation','user_id','merchant_id');
    }

    /**
     * get order by order_id 
     */
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }


   /**
    * get order's total product price by product price and order quantity
    * $products
    */
    public function totalProductPrice($products)
    {
        $totalCost = 0;               
        foreach($products as $product)
        {
            $totalCost += $product->product_quantity * $product->per_product_price ;
        }
        return $totalCost;
    }


}
