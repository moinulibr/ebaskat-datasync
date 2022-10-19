<?php

namespace App\Http\Controllers\Admin;
//brand

use Carbon\Carbon;
use App\Models\OrderPackage;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Traits\BigbuyOrder;
use App\Http\Controllers\Controller;

use App\Traits\Testing\TestingBigbuyOrder;
class BigbuyOrderController extends Controller
{
    use BigbuyOrder;
    use TestingBigbuyOrder;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

 
    public function index()
    {
        $data['pacakages'] = OrderPackage::where('merchant_id',defaultEbaskatPrimeBbId_hd())
                        ->orderBy('id', 'desc')
                        ->paginate(50);
        return view('admin.order-bigbuy.index',$data);
    }


    public function orderListByAjax(Request $request)
    {
        if($request->ajax())
        {
            $status         = $request->status ?? NULL;
            $pagination     = $request->pagination ?? 50;
            $search         = $request->search ?? NULL;
            
            $date_from = Carbon::parse($request->input('start_date'));
            $date_to = Carbon::parse($request->input('end_date') ?? date("Y-m-d h:i:s",strtotime(date("Y-m-d h:i:s")."-21 day")));
            
            $query= OrderPackage::query();
            $query->where('merchant_id',defaultEbaskatPrimeBbId_hd());
                if($search)
                {   
                    //have to process 
                    $query->where('order_package_number','like','%'.$search.'%')
                    ->orWhere('alix_order_id','like','%'.$search.'%');
                }
                if($date_from)
                {
                    $query->whereDate('created_at', '<=', $date_from)
                    ->whereDate('created_at', '>=', $date_to);
                }
                if($status)
                {
                    if($status == "processing")
                    {
                        $query->where('delivery_status','like',$status.'%');
                    }else{
                        $query->where('delivery_status',$status);
                    }
                }
            $data['pacakages'] =    $query->orderBy('id', 'desc')
                            ->paginate($pagination); 
            $data['page_no'] = $request->page ?? 1;
            $html = view('admin.order-bigbuy.ajax.order_list',$data)->render();
            return response()->json([
                'status' => true,
                'data'  => $html
            ]);
        }

        /* 
            $query->when($search && ($date_from  == NULL),function($q)use($search,$date_from){
                return $q->where('order_package_number','like','%'.$search.'%')
                ->orWhere('alix_order_id','like','%'.$search.'%');
            });
            $query->when($date_from && ($search  == NULL),function($d)use($search,$date_from,$date_to){
                return $d->whereDate('created_at', '<=', $date_from)
                ->whereDate('created_at', '>=', $date_to);
            });
            $query->when($date_from && $search,function($dq)use($date_from,$date_to,$search){
                return $dq
                ->whereDate('created_at', '<=', $date_from)
                ->whereDate('created_at', '>=', $date_to)
                ->where('order_package_number','like','%'.$search.'%')
                ->orWhere('alix_order_id','like','%'.$search.'%');
            }); 
        */
        /* 
            ->when($date_from || $search,function($ds)use($date_from,$date_to,$search){
                return 
                $ds->when($date_from,function($dq)use($date_from,$date_to){
                    return $dq->whereDate('created_at', '<=', $date_from)
                    ->whereDate('created_at', '>=', $date_to);
                })
                ->when($search,function($q)use($search){
                    return $q->where('order_package_number','like','%'.$search.'%')
                    ->orWhere('alix_order_id','like','%'.$search.'%');
                });
            }) 
        */
    }


    /**
     * Show details data of order package
     * @param integer $id
     */
    public function show(int $id)
    {
        if (!OrderPackage::where('id', $id)->exists()) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }

        $package  =   OrderPackage::findOrFail($id);
        return view('admin.order-bigbuy.details', compact('package'));
    }


    /**
     * order process to Bigbuy
     * @param [type] int $id
     */
    public function orderToBigbuy(int $id)
    {
        $this->orderId = $id;
        $this->merchantId = defaultEbaskatPrimeBbId_hd(); 
        $statusCode = $this->processOrderToBigbuy();
        return response()->json([
            "status" => $statusCode['status'],
            "message" => $statusCode['msg']
        ]);
        
        
        /* if($this->processOrderToBigbuy() == "success")
        {
            $msg = 'Order Process to Bigbuy Successfully.';
            $status = "success";
        }else{
            $msg = 'Order Not Process to Bigbuy Successfully.';
            $status = "error";
        }
       // $result = $this->processOrderToBigbuy();
       return response()->json([
           "status" => $status,
           "message" => $msg
       ]); */
    }


    /**
     * bulk order place  to obasket 
     * @param Request $request
     */
    public function bulkOrderToBigbuy(Request $request)
    {
        $this->bulkOrderIds = $request->ids;
        $this->merchantId   = defaultEbaskatPrimeBbId_hd(); 
        $orders = [];
        if(count($request->ids) > 0) {
            $orders = $this->bulkOrderProcessToBigbuy();
        }
        $results = [];
        foreach($orders as $i => $order)
        {
            $results[$i]['order_package_number']    = $order['order_package_number'];
            $results[$i]['orderedMessage']          = $order['message'];
            if($order['action'] == "success" )
            {
                $results[$i]['action'] = 'success';
            }
            else{
                $results[$i]['action'] = 'error';
            }
        }

        $data = [];
        foreach($results as $index => $result)
        {
            if($result['action'] == 'success')
            {   
                $data[$index]['message_type'] = "success";
            }else{
                $data[$index]['message_type'] = "error"; 
            }
            $data[$index]['message'] = 'Order package number - '.$result['order_package_number'] ." :  ".$result['orderedMessage'];
        }
        if(count($data) > 0)
        {
            return response()->json([
                "status" => true,
                "datas" => $data
            ]);
        }
        return response()->json([
            "status" => false,
            "datas" => []
        ]);

    }




    public function displayAllProductForSingleOrderPlace(Request $request)
    {
        //$request->order_id;
        $data['package']  =   OrderPackage::findOrFail($request->package_id);
        $modalData      = view('admin.order-bigbuy.single-order-place.display_product_modal',$data)->render();
        $productData    = view('admin.order-bigbuy.single-order-place.display_product',$data)->render();
        return response()->json([
            'status'        => true,
            'modalData'     => $modalData,
            'productData'   => $productData,
        ]);
    }


    /**
     * Single order placing to bigbuy
     *
     * @param Request $request
     */
    public function singleOrderPlacing(Request $request)
    {
        $this->orderProductId   = $request->order_product_id;
        $this->orderPackageId   = $request->order_package_id;
        $this->orderId          = $request->order_id;
        $this->productId        = $request->product_id;
        $this->merchantId       = defaultEbaskatPrimeBbId_hd(); 
        $statusCode             = $this->singleOrderProcessToBigbuy();//['status'=>'success','msg' =>'success'];//
        
        $data['package']        = OrderPackage::findOrFail($request->order_package_id);
        $productData            = view('admin.order-bigbuy.single-order-place.display_product',$data)->render();
        return response()->json([
            "status"        => $statusCode['status'],
            "message"       => $statusCode['msg'],
            'productData'   => $productData,
        ]);

        /* $request->order_package_id;
        $request->product_id;
        $request->order_product_id;
        $request->order_id; */
    }




    /** [after place order to bigbuy]
     * get bigbuy order details 
     */
    public function getBigbuyOrder()
    {
        $orderDetails = $this->getOrderDetailsByBigbiyOrderId($bigbuyOrderId = '13858532');
        $orderDetails = $this->getOrderDetailsByBigbiyOrderId($bigbuyOrderId = 'sdafkjkljf');
        return $orderDetails;
        json_decode($orderDetails);
    }

    




    /**
     *  bigbuy order id update
     */
    public function bigbuyOrderIdUpdate(Request $request)
    {
        OrderPackage::findOrFail($request->orderPackageId)->update([
            'alix_order_id' => $request->bigbuyOrderNo
        ]);
        return response()->json([
            'status' => true,
            'message' => "Bigbuy order no/number updated successfully!"
        ]);
    }



    /**
     *  bigbuy order no  update for single order update
     */
    public function bigbuyOrderNoUpdateForSingleOrder(Request $request)
    {
        OrderProduct::find($request->orderProductId)->update([
            'ds_order_no' => $request->bigbuyOrderNo
        ]);
        return response()->json([
            'status' => true,
            'message' => "Bigbuy order no/number updated successfully!"
        ]);
    }



}



