<?php

namespace App\Http\Controllers\Api\Bigbuy;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    //all updateable products id [array,like; [125455,12365,45213]]
    //all importable products id [array,like; [125455,12365,45213]]
    // single product by product id. [like variation]


    
    /*
    |------------------------------------------------------------------
    | get bigbuy all importable products id [array format]
    |------------------------------------------------------------------
    */
        public function getBigbuyImportableProduct()
        {
            $data = Product::select('ds_product_id')
                    ->where('updateable',2)
                    ->where('product_from','Bigbuy')
                    ->where('status','>',0)
                    ->pluck('ds_product_id')
                    ->toArray();
            if(count($data) > 0)
            {
                return response()->json([
                    'message' => 'Success',
                    'success' => true,
                    'error' => false,
                    'status' => 200,
                    'data' =>  json_encode($data),
                    'note' => "All Importable products id",
                    //'note:-product status' => "0 = permanently inactive, 1 = active, 2 = temporary inactive",
                ]);
            } 
            return response()->json([
                'message' => 'product not found',
                'success' => true,
                'error' => false,
                'status' => 200,
                'data' =>  json_encode([]),
                'note' => "All Importable products id",
                //'note:-product status' => "0 = permanently inactive, 1 = active, 2 = temporary inactive",
            ]); 
        }
    /*
    |------------------------------------------------------------------
    | get bigbuy all importable products id [array format]
    |------------------------------------------------------------------
    */




    /*
    |------------------------------------------------------------------
    | get bigbuy all updateable products id [array format]
    |------------------------------------------------------------------
    */
        public function getBigbuyUpdateableProducts()
        {
            $data = Product::select('ds_product_id')
                ->where('updateable',1)
                ->where('product_from','Bigbuy')
                ->where('status','>',0)
                ->pluck('ds_product_id')
                ->toArray();

            if(count($data) > 0)
            {
                return response()->json([
                    'message' => 'Success',
                    'success' => true,
                    'error' => false,
                    'status' => 200,
                    'data' =>  json_encode($data),
                    'note' => "All Updateable products id",
                    //'note:-product status' => "0 = permanently inactive, 1 = active, 2 = temporary inactive",
                ]);
            } 
            return response()->json([
                'message' => 'products not found',
                'success' => true,
                'error' => false,
                'status' => 200,
                'data' =>  json_encode([]),
            ]);
        }
    /*
    |------------------------------------------------------------------
    | get bigbuy all updateable products id [array format]
    |------------------------------------------------------------------
    */



    /*
    |------------------------------------------------------------------
    | get bigbuy single product details by ds product id
    |------------------------------------------------------------------
    */
        public function getBigbuyProductDetailsByDsProductId($dsProductId = NULL)
        {
            if($dsProductId == NULL)
            {
                return response()->json([
                    'message' => 'BAD REQUEST',
                    'success' => false,
                    'error' => true,
                    'status' => 400,
                    'note' => 'please send ds_product_id'
                ]);
            }
            else{
                $data = Product::select('ds_product_id','product_type','sku','name','photo','thumbnail',
                    'current_price','regular_price','sale_price','description','stock_quantity','stock_status',
                    'status','product_from','images','category_id','subcategory_id','shipping_cost','dimension','brand_id'
                    )
                    ->where('ds_product_id',$dsProductId)
                    ->where('product_from','Bigbuy')
                    ->where('status','>',0)
                    ->first();
                if($data)
                {  
                    Product::select('ds_product_id','updated_fields','updateable')
                        ->where('ds_product_id',$dsProductId)
                        ->update(['updateable'=> 0 , 'updated_fields' => NULL]);
                    
                    return response()->json([
                        'message' => 'Success',
                        'success' => true,
                        'error' => false,
                        'status' => 200,
                        'data' =>  json_encode($data),
                    ]);
                }else{
                    return response()->json([
                        'message' => 'product not found',
                        'success' => true,
                        'error' => false,
                        'status' => 404,
                        'data' =>  json_encode([]),
                    ]);     
                }
            }
             
        }
    /*
    |------------------------------------------------------------------
    | get bigbuy single product details by ds product id
    |------------------------------------------------------------------
    */


    /*
    |------------------------------------------------------------------
    | get bigbuy product variation by ds product id 
    |------------------------------------------------------------------
    */
        public function getProductVariationByDsProductId($dsProductId = NULL)
        {
            if($dsProductId == NULL)
            {
                return response()->json([
                    'message' => 'BAD REQUEST',
                    'success' => false,
                    'error' => true,
                    'status' => 404,
                    'note' => 'please send ds_product_id',
                ]);
            }

            $data = ProductVariant::select('ds_product_id','id','ds_variation_id','variation_sku',
                        'current_price','regular_price','sale_price','stock_quantity','stock_status','attributes',
                        'dimension','variation_photo','created_at','updated_at'
                        )
                        ->where('ds_product_id',$dsProductId)
                        ->where('product_from','Bigbuy')
                        ->get();
            if(count($data) > 0)
            {
                return response()->json([
                    'message' => 'Success',
                    'success' => true,
                    'error' => false,
                    'status' => 200,
                    'data' =>  json_encode($data),
                ]);
            }  
            return response()->json([
                'message' => 'product variant not found',
                'success' => true,
                'error' => false,
                'status' => 200,
                'data' =>  json_encode([]),
            ]);        
        }
    /*
    |------------------------------------------------------------------
    | get bigbuy product variation by ds product id 
    |------------------------------------------------------------------
    */





    /*=========================================================================
    |------------------------------------------------------------------
    | get all importable products with all data
    | AND get all updateable products with all data
    |------------------------------------------------------------------
    */
        //get all importable products with all data
        public function getImportableProduct()
        {
            $data = Product::select('ds_product_id','product_type','sku','name','photo','thumbnail',
                    'current_price','regular_price','sale_price','description','stock_quantity','stock_status',
                    'status','product_from','images','category_id','subcategory_id','shipping_cost','dimension','brand_id'
                    )
                    ->where('updateable',2)
                    ->where('product_from','Bigbuy')
                    ->where('status','>',0)
                    ->get();
            if(count($data) > 0)
            {
                return response()->json([
                    'message' => 'Success',
                    'success' => true,
                    'error' => false,
                    'status' => 200,
                    'data' =>  json_encode($data),
                    'note:-product status' => "0 = permanently inactive, 1 = active, 2 = temporary inactive",
                ]);
            } 
            return response()->json([
                'message' => 'product not found',
                'success' => true,
                'error' => false,
                'status' => 200,
                'data' =>  json_encode([]),
                'note:-product status' => "0 = permanently inactive, 1 = active, 2 = temporary inactive",
            ]);
             
        }

        //get all updateable products with all data
        public function getUpdateableProduct()
        {
            $data = Product::select('ds_product_id','product_type','sku','name','photo','thumbnail',
            'current_price','regular_price','sale_price','description','stock_quantity','stock_status',
            'status','product_from','images','category_id','subcategory_id','shipping_cost','dimension','brand_id'
            )
            ->where('updateable',1)
            ->where('product_from','Bigbuy')
            ->where('status','>',0)
            ->get();
            if(count($data) > 0)
            {
                return response()->json([
                    'message' => 'Success',
                    'success' => true,
                    'error' => false,
                    'status' => 200,
                    'data' =>  json_encode($data),
                    'note:-product status' => "0 = permanently inactive, 1 = active, 2 = temporary inactive",
                ]);
            } 
            return response()->json([
                'message' => 'product not found',
                'success' => true,
                'error' => false,
                'status' => 200,
                'data' =>  json_encode([]),
                'note:-product status' => "0 = permanently inactive, 1 = active, 2 = temporary inactive",
            ]);
        }
    /*
    |------------------------------------------------------------------
    | get all importable products with all data
    | AND get all updateable products with all data
    |------------------------------------------------------------------
    *///=========================================================================


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
