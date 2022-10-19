<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderTrack;
use App\Models\VendorOrder;
use App\Models\OrderPackage;
use Illuminate\Http\Request;
use App\Classes\EBasketMailer;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;


class OrderController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * main order 
     * display blade file 
     */
    public function mainOrder($currentStatus = 'none')
    {
        $data['currentStatus'] = $currentStatus;
        return view('admin.order.main_order_index',$data);
    }

    /**
     * main order list
     * display main order data
     * @param Request $request
     */
    public function mainOrderList(Request $request)
    { 
        if($request->ajax())
        {
            $status         = $request->status ?? NULL;
            $pagination     = $request->pagination ?? 50;
            $search         = $request->search ?? NULL;
            
            $date_from = Carbon::parse($request->input('start_date'));
            $date_to = Carbon::parse($request->input('end_date') ?? date("Y-m-d h:i:s",strtotime(date("Y-m-d h:i:s")."-21 day")));
            
            $query= Order::query();
            if($search)
            {   
                $query->where('order_number','like','%'.$search.'%')
                ->orWhere('customer_name','like','%'.$search.'%')
                ->orWhere('customer_email','like','%'.$search.'%')
                ->orWhere('customer_phone','like','%'.$search.'%')
                ->orWhere('customer_country','like','%'.$search.'%')
                ->orWhere('customer_city','like','%'.$search.'%')
                ->orWhere('shipping_name','like','%'.$search.'%')
                ->orWhere('shipping_lastname','like','%'.$search.'%')
                ->orWhere('shipping_country','like','%'.$search.'%')
                ->orWhere('shipping_email','like','%'.$search.'%')
                ->orWhere('shipping_phone','like','%'.$search.'%')
                ->orWhere('shipping_city','like','%'.$search.'%')
                ->orWhere('shipping_zip','like','%'.$search.'%')
                ->orWhere('status','like','%'.$search.'%')
                ->orWhere('payment_status','like','%'.$search.'%')
                ->orWhere('method','like','%'.$search.'%')
                ;
            }
            if($date_from)
            {
                $query->whereDate('created_at', '<=', $date_from)
                ->whereDate('created_at', '>=', $date_to);
            }
            if($status != "none" && $status != "")
            {
                $query->where('status',$status);
            }
            $data['orders'] =    $query->orderBy('id', 'desc')
                            ->paginate($pagination); 
            $data['page_no'] = $request->page ?? 1;
            $html = view('admin.order.ajax.order_list',$data)->render();
            return response()->json([
                'status' => true,
                'data'  => $html
            ]);
        }
    }


    public function datatables($status)
    {
        /* $datas = Order::orderBy('id', 'desc')
            // status
            ->where(function($q) use ($status){
                if ($status == 'pending') {
                    $q->where('status', '=', 'pending');
                } elseif ($status == 'processing') {
                    $q->where('status', '=', 'processing');
                } elseif ($status == 'completed') {
                    $q->where('status', '=', 'completed');
                } elseif ($status == 'declined') {
                    $q->where('status', '=', 'declined');
                } 
                elseif ($status == 'on delivery') {
                    $q->where('status', '=', 'on delivery');
                }
                elseif ($status == 'partial delivered') {
                    $q->where('status', '=', 'partial delivered');
                }else {
                    $q->orderBy('id', 'desc');
                }
            })
            ->get();
        */

        $datas = Order::orderBy('id', 'desc')
        // status
        ->where(function($q) use ($status){
            if ($status != 'none') {
                $q->where('status', '=', $status);
            }else {
                $q->orderBy('id', 'desc');
            }
        })
        ->get();

        return Datatables::of($datas)
            ->editColumn('id', function (Order $data) {
                $id = '<a class="text-primary" href="' . route('admin.order.invoice', $data->id) . '">' . $data->order_number . '</a>';
                return $id;
            })
            ->editColumn('pay_amount', function (Order $data) {
                return '€' . round($data->pay_amount, 2);
            })

            ->editColumn('payment_status', function(Order $data) {
                $class = 'warning';
                if($data->payment_status == 'pending'){
                    $class = 'warning';
                }elseif($data->payment_status == 'processing'){
                    $class = 'primary';
                }elseif($data->payment_status == 'completed'){
                    $class = 'success';
                }elseif($data->payment_status == 'canceled' || $data->payment_status == 'failed'){
                    $class = 'danger';
                }
                $payment_status = '<span class="badge bg-'.$class.' text-white"><strong>'.ucfirst($data->payment_status).'</strong></span>';
                return $payment_status;
            })

            ->editColumn('status', function(Order $data) {
                $class = 'warning';
                if($data->status == 'pending'){
                    $class = 'warning';
                }elseif($data->status == 'processing'){
                    $class = 'primary';
                }elseif($data->status == 'completed'){
                    $class = 'success';
                }elseif($data->status == 'declined'){
                    $class = 'danger';
                }
                $status = '<span class="badge bg-'.$class.' text-white"><strong>'.ucfirst($data->status).'</strong></span>';
                return $status;
            })
            ->addColumn('action', function (Order $data) {
                $orders = null;
                /* if (Auth::guard('admin')->user()->role->permissionCheck('orders|edit')) {
                    $orders .= '<a href="javascript:;" data-href="' . route('admin.order.edit', $data->id) . '" class="delivery" data-toggle="modal" data-target="#modal1"><i class="fas fa-dollar-sign"></i> Delivery Status</a>';
                } */
                if (Auth::guard('admin')->user()->role->permissionCheck('orders|edit')) {
                    $orders .= '<a href="javascript:;" data-href="' . route('admin.main.order.show.delivery.status.details') . '" class="delivery_status" data-id="'.$data->id.'" ><i class="fas fa-dollar-sign"></i> Delivery Status</a>';
                }
                return '<div class="godropdown"><button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button><div class="action-list"><a href="' . route('admin.order.show', $data->id) . '" > <i class="fas fa-eye"></i> Details</a><a href="javascript:;" class="send" data-email="' . $data->customer_email . '" data-toggle="modal" data-target="#vendorform"><i class="fas fa-envelope"></i> Send</a><a href="javascript:;" data-href="' . route('admin.order.track', $data->id) . '" class="track" data-toggle="modal" data-target="#modal1"><i class="fas fa-truck"></i> Track Order</a>' . '' . $orders . '</div></div>';
                $action = '<div class="godropdown">
                <button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button>
                <div class="action-list">';

                /* if (Auth::guard('admin')->user()->role->permissionCheck('orders|edit|status_update')) {
                    $action .= '<a href="javascript:;" data-href="' . route('admin.order.edit', $data->id) . '" class="delivery" data-toggle="modal" data-target="#modal1"><i class="fas fa-dollar-sign"></i> Delivery Status</a>';
                } */
                if (Auth::guard('admin')->user()->role->permissionCheck('orders|edit|status_update')) {
                    $action .= '<a href="javascript:;" data-href="' . route('admin.main.order.show.delivery.status.details') . '" class="delivery_status" data-id="'.$data->id.'"><i class="fas fa-dollar-sign"></i> Delivery Status</a>';
                }
                if(Auth::guard('admin')->user()->role->permissionCheck('orders|send_email')) 
                {
                    $action .= '<a href="javascript:;" class="send" data-email="' . $data->customer_email . '" data-toggle="modal" data-target="#vendorform"><i class="fas fa-envelope"></i> Send</a>';
                }
                if(Auth::guard('admin')->user()->role->permissionCheck('orders|track'))
                {
                    $action .= '<a href="javascript:;" data-href="' . route('admin.order.track', $data->id) . '" class="track" data-toggle="modal" data-target="#modal1"><i class="fas fa-truck"></i> Track Order</a>';
                }
                $action .= '<a href="' . route('admin.order.show', $data->id) . '" > <i class="fas fa-eye"></i> Details</a>';
                
                $action .= '</div></div>';

                return $action;
            })
            ->rawColumns(['id', 'status', 'payment_status', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }


    public function index($currentStatus = 'none')
    {
        $data['currentStatus'] = $currentStatus;
        return view('admin.order.index',$data);
    }

    public function edit($id)
    {
        $data = Order::find($id);
        return view('admin.order.delivery', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = Order::findOrFail($id);
        $input = $request->all();
        if ($data->status == "completed") {
            // Then Save Without Changing it.
            $input['status'] = "completed";
            $data->update($input);

            // send mail to user
            try {
                $this->guzzleClient()->post(env('QUEUE_URL') . 'send-mail/order/status-change', [
                    'form_params' => [
                        'customer_name' => $data->customer_name,
                        'email'         => $data->customer_email,
                        'order_id'      => $data->id,
                        'order_status'  => $input['status'],
                    ]
                ]);
            } catch (\Throwable $th) {
                //throw $th;
            }
            //--- Logic Section Ends

          
            //--- Redirect Section
            $msg = 'Status Updated Successfully.';
            return response()->json($msg);
            //--- Redirect Section Ends

        } else {
            if ($input['status'] == "completed") {
                foreach ($data->vendororders as $vorder) {
                    $uprice = User::findOrFail($vorder->user_id);
                    $uprice->current_balance = $uprice->current_balance + $vorder->price;
                    $uprice->update();
                }

                if (User::where('id', $data->affilate_user)->exists()) {
                    $auser = User::where('id', $data->affilate_user)->first();
                    $auser->affilate_income += $data->affilate_charge;
                    $auser->update();
                }

                // send mail to user
                try {
                    $this->guzzleClient()->post(env('QUEUE_URL') . 'send-mail/order/status-change', [
                        'form_params' => [
                            'customer_name' => $data->customer_name,
                            'email'         => $data->customer_email,
                            'order_id'      => $data->id,
                            'order_status'  => $input['status'],
                        ]
                    ]);
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            if ($input['status'] == "declined") {

                $cart = unserialize($data->cart);

                foreach ($cart->items as $prod) {
                    $x = (string)$prod['stock'];
                    if ($x != null) {
                        $product = Product::findOrFail($prod['item']['id']);
                        $product->stock = $product->stock + $prod['qty'];
                        $product->update();
                    }
                }

                foreach ($cart->items as $prod) {
                    $x = (string)$prod['size_qty'];
                    if (!empty($x)) {
                        $product = Product::findOrFail($prod['item']['id']);
                        $x = (int)$x;
                        $temp = $product->size_qty;
                        $temp[$prod['size_key']] = $x;
                        $temp1 = implode(',', $temp);
                        $product->size_qty =  $temp1;
                        $product->update();
                    }
                }

                // send mail to user
                try {
                    $this->guzzleClient()->post(env('QUEUE_URL') . 'send-mail/order/status-change', [
                        'form_params' => [
                            'customer_name' => $data->customer_name,
                            'email'         => $data->customer_email,
                            'order_id'      => $data->order_number,
                            'order_status'  => $input['status'],
                        ]
                    ]);
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }

            $data->update($input);

            if ($request->track_text) {
                $title = ucwords($request->status);
                $ck = OrderTrack::where('order_id', '=', $id)->where('title', '=', $title)->first();
                if ($ck) {
                    $ck->order_id = $id;
                    $ck->title = $title;
                    $ck->text = $request->track_text;
                    $ck->update();
                } else {
                    $data = new OrderTrack;
                    $data->order_id = $id;
                    $data->title = $title;
                    $data->text = $request->track_text;
                    $data->save();
                }
            }

            $order = VendorOrder::where('order_id', '=', $id)->update(['status' => $input['status']]);

            //--- Redirect Section
            $msg = 'Status Updated Successfully.';
            return response()->json($msg);
            //--- Redirect Section Ends    
            //--- Redirect Section Ends

        }

        //--- Redirect Section
        $msg = 'Status Updated Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends

    }


    //not using this.. now managing all kinds of status from index page
        public function pending()
        {
            return view('admin.order.pending');
        }
        public function processing()
        {
            return view('admin.order.processing');
        }
        public function completed()
        {
            return view('admin.order.completed');
        }
        public function declined()
        {
            return view('admin.order.declined');
        }
        public function onDelivery()
        {
            return view('admin.order.on-delivery');
        }
        public function partialDelivered()
        {
            return view('admin.order.partial-delivered');
        }
    //not using this.. now managing all kinds of status from index page

    public function show($id)
    {
        if (!Order::where('id', $id)->exists()) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }

        $order          =   Order::findOrFail($id);
        return view('admin.order.details', compact('order'));
    }

    public function invoice($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.order.invoice', compact('order'));
    }

    public function emailsub(Request $request)
    {
        $data = 0;
        $datas = [
            'to' => $request->to,
            'subject' => $request->subject,
            'body' => $request->message,
        ];

        $mailer = new EBasketMailer();
        $mail = $mailer->sendCustomMail($datas);
        if ($mail) {
            $data = 1;
        }

        return response()->json($data);
    }

    public function printpage($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.order.print', compact('order'));
    }

    public function license(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $cart = unserialize($order->cart);
        $cart->items[$request->license_key]['license'] = $request->license;
        $order->cart = serialize($cart);
        $order->update();
        return response()->json('Successfully Changed The License Key.');
    }
}
