<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderTrack;
use App\Models\VendorOrder;
use App\Models\OrderPackage;
use Illuminate\Http\Request;
use App\Classes\EBasketMailer;
use App\Traits\AliexpressOrder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;


class EbaskatOrderController extends Controller
{
    use AliexpressOrder;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.order-ebaskat.index');
    }


    public function orderListByAjax(Request $request)
    {
        if($request->ajax())
        {
            $status         = $request->status ?? NULL;
            $pagination     = $request->pagination ?? 50;
            $search         = $request->search ?? NULL;
            $date_from = Carbon::parse($request->input('start_date'));
            $date_to = Carbon::parse($request->input('end_date') ?? date("Y-m-d h:i:s",strtotime(date("Y-m-d h:i:s")."-7 day")));

            $query = Order::query();
            $query->withCount('ebaskatMerchantPackages');
            if($status)
            {
                $query->where('status',$status);
            }
            $query->when($search && ($date_from  == NULL),function($q)use($search){
                return $q->where('order_number','like','%'.$search.'%')
                ->orWhere('customer_email','like','%'.$search.'%')
                ;
            })
            ->when($date_from && ($search  == NULL),function($d)use($date_from,$date_to){
                return $d->whereDate('created_at', '<=', $date_from)
                ->whereDate('created_at', '>=', $date_to);
            })
            ->when($date_from && $search,function($dq)use($date_from,$date_to,$search){
                return $dq
                ->whereDate('created_at', '<=', $date_from)
                ->whereDate('created_at', '>=', $date_to)
                ->where('order_number','like','%'.$search.'%')
                ->orWhere('customer_email','like','%'.$search.'%')
                ;
            });
            /* 
                ->when($date_from || $search,function($ds)use($date_from,$date_to,$search){
                    return 
                    $ds->when($date_from,function($dq)use($date_from,$date_to){
                        return $dq->whereDate('created_at', '<=', $date_from)
                        ->whereDate('created_at', '>=', $date_to);
                    })
                    ->when($search,function($q)use($search){
                        return $q->where('order_number','like','%'.$search.'%')
                        ->orWhere('alix_order_id','like','%'.$search.'%');
                    });
                }) 
            */
            
            /* $query->when($status,function($s)use($status){
                return $s->where('status',$status);
            }); */

            $data['orders'] = $query->orderBy('id', 'desc')
                ->having('ebaskat_merchant_packages_count','>',0)
                ->paginate($pagination); 
            $html = view('admin.order-ebaskat.ajax.order_list',$data)->render();
            return response([
                'status' => true,
                'data'  => $html
            ]);
        }
    }




    public function edit($id)
    {
        $data = Order::find($id);
        return view('admin.order-ebaskat.delivery', compact('data'));
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


    /* public function pending()
    {
        return view('admin.order-ebaskat.pending');
    }

    public function processing()
    {
        return view('admin.order-ebaskat.processing');
    }

    public function completed()
    {
        return view('admin.order-ebaskat.completed');
    }

    public function declined()
    {
        return view('admin.order-ebaskat.declined');
    } */


    public function show($id)
    {
        if (!Order::where('id', $id)->exists()) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }

        $order          =   Order::findOrFail($id);
        return view('admin.order-ebaskat.details', compact('order'));
    }


    public function invoice($id)
    {
        if (!Order::where('id', $id)->exists()) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }
        $order = Order::findOrFail($id);
        return view('admin.order-ebaskat.invoice', compact('order'));
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
        if (!Order::where('id', $id)->exists()) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }
        $order = Order::findOrFail($id);
        return view('admin.order-ebaskat.print', compact('order'));
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
