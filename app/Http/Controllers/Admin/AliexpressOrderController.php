<?php

namespace App\Http\Controllers\Admin;
//brand

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderTrack;
use App\Models\VendorOrder;
use App\Models\OrderPackage;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Classes\EBasketMailer;
use App\Traits\AliexpressOrder;
use App\Traits\AliexpressOrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AliexpressOrderController extends Controller
{
    use AliexpressOrder, AliexpressOrderStatus;


    public function __construct()
    {
        $this->middleware('auth:admin');
    }

 
    public function index()
    {
        $data['pacakages'] = OrderPackage::where('merchant_id',defaultEbaskatPrimeId_hd())
                        ->orderBy('id', 'desc')
                        ->paginate(50);
        return view('admin.order-aliexpress.index',$data);
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
            $query->where('merchant_id',defaultEbaskatPrimeId_hd());
                if($search)
                {
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
            $html = view('admin.order-aliexpress.ajax.order_list',$data)->render();
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
     * @return void
     */
    public function show(int $id)
    {
        if (!OrderPackage::where('id', $id)->exists()) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }

        $package  =   OrderPackage::findOrFail($id);
        return view('admin.order-aliexpress.details', compact('package'));
    }


    /**
     * order process to aliexpress
     * @param [type] int $id
     * @return void
     */
    public function orderToAliexpress(int $id)
    {
        $this->orderId = $id;
        $this->merchantId = defaultEbaskatPrimeId_hd(); 
        
        if($this->processOrderToAliexpress() == "success")
        {
            $msg = 'Order Process to Aliexpress Successfully.';
            $status = "success";
        }else{
            $msg = 'Order Not Process to Aliexpress Successfully.';
            $status = "error";
        }
       // $result = $this->processOrderToAliexpress();
       return response()->json([
           "status" => $status,
           "message" => $msg
       ]);
    }


    /**
     * bulk order place  to obasket 
     * @param Request $request
     * @return void
     */
    public function bulkOrderToAliexpress(Request $request)
    {
        //$this->bulkOrderIds = $request->ids;
        //$this->merchantId   = defaultEbaskatPrimeId_hd(); 
        //$results = $this->bulkOrderProcessToAliexpress();
        /* $orders = [10,11,12,13,14,15];
        $results = [];
        foreach($orders as $index => $order)
        {
            $results[$index]['order_id'] = $order;
            if($order == 10 ||$order == 12 || $order == 14  )
            {
                $results[$index]['action'] = 'success';
            }
            else{
                $results[$index]['action'] = 'error';
            }
        }

        $data = [];
        foreach($results as $index => $result)
        {
            if($result['action'] == 'success')
            {   
                $data[$index]['message_type'] = "success";
                $data[$index]['message'] = 'Order package number : '.$result['order_id'].' is placed to Aliexpress successfully.';  
                //$data[$index]['message'] = 'Order package number - '.$result['order_package_number'].' is placed to Aliexpress successfully.';  
            }else{
                $data[$index]['message_type'] = "error"; 
                $data[$index]['message'] = 'Order package number : '.$result['order_id'].' is not placed to Aliexpress.';  
                //$data[$index]['message'] = 'Order package number - '.$result['order_package_number'].' is not placed to Aliexpress.';  
            }
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
        ]); */


        //after obaskat hosted successfully, then have to remove the top all (method and others)
        
        $this->bulkOrderIds = $request->ids;
        $this->merchantId   = defaultEbaskatPrimeId_hd(); 
        $orders = [];
        if(count($request->ids) > 0) {
            $orders = $this->bulkOrderProcessToAliexpress();
        }
        
        $results = [];
        foreach($orders as $i => $order)
        {
            $results[$i]['order_package_number'] = $order['order_package_number'];
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
                //$data[$index]['message'] = 'Order package number : '.$result['order_id'].' is placed to Aliexpress successfully.';  
                $data[$index]['message'] = 'Order package number - '.$result['order_package_number'].' is placed to Aliexpress successfully.';  
            }else{
                $data[$index]['message_type'] = "error"; 
                //$data[$index]['message'] = 'Order package number : '.$result['order_id'].' is not placed to Aliexpress.';  
                $data[$index]['message'] = 'Order package number - '.$result['order_package_number'].' is not placed to Aliexpress.';  
            }
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

}



