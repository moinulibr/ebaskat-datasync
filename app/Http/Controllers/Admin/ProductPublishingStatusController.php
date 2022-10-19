<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductPublishingStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function unpublishedProductList(Request $request)
    { 
        return view('admin.product.publishing-status.unpublished-product-list');
    }  

    public function unpublishedProductListAjaxResponse(Request $request)
    {
        $products = Product::query();
        $products->whereNull('deleted_at');
        if($request->pname)
        {
            $products->where('name','like',"%".$request->pname."%")
            ->orWhere('sku','like',"%".$request->pname."%");
        }
        //$products->whereRaw('shipping_cost <= price');
        //$products->where('stock','>',5);
        
        $data['products'] = $products->select('id','sku','name','photo','pub_status','status','type',
        'shipping_cost','stock','price','deleted_at')
        ->where('pub_status',0)->latest()->paginate(100);
        $data['page_no'] = $request->page ?? 1;
        $view = view('admin.product.publishing-status.ajax-response-unpublished-product-list',$data)->render();
        return response()->json([
            'status' => true,
            'data' => $view 
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unpublishedProductPublishing(Request $request)
    {
        //Product::whereIn('id',$request->ids)->update(['pub_status' => 1,'status' => 1]);
        Product::whereIn('id',$request->ids)->where('stock','>', 0)->update(['pub_status' => 1,'status' =>1 ]);
        Product::whereIn('id',$request->ids)->where('stock','=', 0)->update(['pub_status' => 1,'status' =>0 ]);
        return response()->json([
            'status' => true,
            'message' => "Products Updated Successfully"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unpublishedProductDeleting(Request $request)
    {
        Product::whereIn('id',$request->ids)->update(['deleted_at'=>now(),'pub_status' => 1,'status' => 0]);
        return response()->json([
            'status' => true,
            'message' => "Products Deleted Successfully"
        ]);
    }

    public function publishedProductUnpublishing($id)
    {
        Product::where('id',$id)->update(['pub_status' => 0,'status' =>0 ]);
        $msg = 'Product Un-published Successfully.';
        return response()->json($msg);
    }

    public function unpublishedProductRepublishing($id)//admin.unpublished.product.unpublishing
    {
        Product::where('id',$id)->where('stock','>', 0)->update(['pub_status' => 1,'status' =>1 ]);
        Product::where('id',$id)->where('stock','=', 0)->update(['pub_status' => 1,'status' =>0 ]);
        $msg = 'Product Published Successfully.';
        return response()->json($msg);
    }



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
