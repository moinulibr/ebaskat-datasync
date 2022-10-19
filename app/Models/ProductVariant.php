<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    // use SoftDeletes;
    //protected $fillable = ['product_id','alix_variation_id','variation_price','ds_variation_price','variation_previous_price','variation_previous_price_condition','variation_sale_price','variation_color','variation_size','variation_bundle','variation_stock_quantity','variation_stock_status','variation_dimension','variation_photo'];
    protected $fillable = ['product_from','product_id','ds_product_id','ds_variation_id','variation_sku','current_price','regular_price','sale_price','stock_quantity','stock_status','attributes','dimension','variation_photo'];
}
