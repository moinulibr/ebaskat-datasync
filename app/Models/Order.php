<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AdminLog;
class Order extends Model
{
    use AdminLog;
    
	protected $fillable = ['user_id', 'cart', 'method','shipping', 'pickup_location', 'totalQty', 'pay_amount', 'txnid', 'charge_id', 'order_number', 'payment_status', 'customer_email', 'customer_name', 'customer_phone', 'customer_address', 'customer_city', 'customer_zip','shipping_name', 'shipping_email', 'shipping_phone', 'shipping_address', 'shipping_city', 'shipping_zip', 'order_note', 'status'];

    public function vendororders()
    {
        return $this->hasMany('App\Models\VendorOrder');
    }

    public function tracks()
    {
        return $this->hasMany('App\Models\OrderTrack','order_id');
    }



    public function merchantPckages()
    {
        return $this->hasMany('App\Models\OrderPackage','order_id');
    }

    //order packages
    public function orderPckages()
    {
        return $this->hasMany('App\Models\OrderPackage','order_id');
    }

    //customer 
    public function customers()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    /*
    |-----------------------------------------------------------------------------------------------------------------
    | For General Orders (ebaskat orders)
    |-----------------------------------------------------------------------------------------------------------------
    */
        /**
         * relationship with order packages table 
         */
        public function ebaskatMerchantPackages()
        {
            return $this->hasMany('App\Models\OrderPackage','order_id')->where('merchant_id','!=',defaultEbaskatPrimeId_hd()); 
        }

        /**
         * only ebaskat merchants products (order packages details)
         * $id
         */
        public function ebaskatMerchantPackageProducts($id)
        {
            $orderPackageIds = OrderPackage::where('order_id',$id)
                        ->where('merchant_id','!=',defaultEbaskatPrimeId_hd())
                        ->pluck('id')
                        ->toArray();
            return OrderProduct::whereIn('order_package_id',$orderPackageIds)->get();           
        }

        /**
         * get total ebaskat order quantity only
         */
        public function ebaskatOrderQuantity()
        {
            return $this->ebaskatMerchantPackageProducts($this->id)->sum('product_quantity');
        }

        /**
         * get total ebaskat order amount
         */
        public function ebaskatOrderProductAmount()
        {
            $totalAmount = 0;
            foreach($this->ebaskatMerchantPackageProducts($this->id) as $product)
            {
                $totalAmount += $product->product_quantity * $product->per_product_price;
            }
            return $totalAmount;
        }

    /*
    |-----------------------------------------------------------------------------------------------------------------
    | For General Orders (ebaskat orders)
    |-----------------------------------------------------------------------------------------------------------------
    */



    /**
     * for aliexpress product count against of merchant 
     */
    public function checkAliexpressProductExist()
    {
        return $this->hasMany('App\Models\OrderPackage','order_id')->where('merchant_id',1)->count();
    }

}
