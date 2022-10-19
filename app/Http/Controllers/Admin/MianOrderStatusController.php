<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderTrack;
use App\Models\OrderPackage;
use App\Models\OrderProduct;

use Illuminate\Http\Request;
use App\Traits\AliexpressOrderStatus;
use App\Traits\OrderStatusMailing;
use App\Http\Controllers\Controller;
use App\Models\Order;
use PhpParser\Node\Expr\FuncCall;

class MianOrderStatusController extends Controller
{
    use AliexpressOrderStatus,OrderStatusMailing;


    /**
    * main order delivery status show details here
    */
    public function mainOrderShowDeliveryStatusDetails(Request $request)
    { 
        $data['order']  = Order::findOrFail($request->id);
        if($data['order'])
        {
            $view = view('admin.order.delivery-status.status',$data)->render();
            return response()->json([
                'status' => true,
                'html' => $view
            ]);
        }
        return response()->json([
            'status' => false
        ]);
    }



    /**
    * main Order  status update 
    */
    public function mainOrderStatusUpdate(Request $request)
    {
        Order::findOrFail($request->order_id)->update([
            'status' => $request->main_order_status
        ]);


        $data['order']  = Order::findOrFail($request->order_id);

        //order status changing message send to the customer by mail
        if($request->email_applicable == 1) 
        {
            $this->mainOrderDetails = $data['order'];
            $this->mainOrderStatusChangingMail();
        }
        //order status changing message send to the customer by mail

        if($data['order'])
        {
            $view = view('admin.order.delivery-status.status',$data)->render();
            return response()->json([
                'status' => true,
                'html' => $view
            ]);
        }
        return response()->json([
            'status' => false
        ]);
    }

    /*
    * update order_packages delivery status update
    */
    public function orderPackageDeliveryStatusUpdateFromMianOrder(Request $request)
    {
        OrderPackage::findOrFail($request->order_package_id)->update([
            'delivery_status' => $request->status  
        ]);
        $data['order']  = Order::findOrFail($request->order_id);

        //partial order status changing message send to the customer by mail 
        if($request->email_applicable == 1) 
        {   
            $this->mainOrderDetails = $data['order'];
            $this->splittedOrderStatusChangingMail();
        }
        //partial order status changing message send to the customer by mail    
        if($data['order'])
        {
            $view = view('admin.order.delivery-status.status',$data)->render();
            return response()->json([
                'status' => true,
                'html' => $view
            ]);
        }
        return response()->json([
            'status' => false
        ]);
    }

    /*
    * update order_products delivery status update and order trackings
    */
    public function orderProductDeliveryStatusUpdateFromMianOrder(Request $request)
    {
        OrderProduct::findOrFail($request->order_product_id)->update([
            'delivery_status' => $request->status  
        ]); 
        
        /* OrderPackage::findOrFail($request->order_package_id)->update([
            'delivery_status' => $request->status  
        ]); */
        $orderTrac = new OrderTrack();
        $orderTrac->order_id    = $request->order_id;
        $orderTrac->title       = $request->title;
        $orderTrac->text        = $request->text;
        $orderTrac->save();

        $data['order']  = Order::findOrFail($request->order_id);
        //partial order status changing message send to the customer by mail 
        if($request->email_applicable == 1) 
        {    
            $this->mainOrderDetails = $data['order'];
            $this->splittedOrderStatusChangingMail();
        }
        //partial order status changing message send to the customer by mail   
        if($data['order'])
        {
            $view = view('admin.order.delivery-status.status',$data)->render();
            return response()->json([
                'status' => true,
                'html' => $view
            ]);
        }
        return response()->json([
            'status' => false
        ]);
    }
    /*
    * update order_products delivery status update and order trackings
    */




    //---------------------------------------------may not using this---------------------------------
    //---------------------------------------------may not using this---------------------------------
        /** may not using this
        * Order Package status syncing :  Bulk
        */
        public function orderPackageStatusUpdateBySyncingBulking(Request $request)
        {
            if(count($request->ids) > 0) 
            {
                $orderPackages =  OrderPackage::where("alix_order_id","!=",NULL)
                                        ->select('alix_order_id')
                                        ->whereIn('id',$request->ids)
                                        ->pluck('alix_order_id')
                                        ->toArray();
                if(count($orderPackages) > 0)
                {
                    foreach($orderPackages as $aliexpressOrderId)
                    {
                        $this->alix_order_id = $aliexpressOrderId;
                        $deliveryStatus =  $this->getOrderStatusFromObaskat();

                        $ebOrderPackage = OrderPackage::where('alix_order_id',$aliexpressOrderId)->update([
                            'delivery_status' => ucfirst($deliveryStatus) 
                        ]);
                    }
                    return response()->json([
                        'status' => true
                    ]);
                }else{
                    return response()->json([
                        'status' => false
                    ]);
                } 
            }
            return response()->json([
                'status' => false
            ]);
        }
        /**
        * Order Package status syncing :  Bulk
        */






        //---------------------------------------------------------------------------------
        /**
        * show delivery status modal 
        */
        public function orderDeliveryStatus(Request $request)
        {
            $data['orderPackage'] = OrderPackage::findOrFail($request->id); //order package id
            if($data['orderPackage'])
            {
                $view = view('admin.order-aliexpress.delivery-status.status',$data)->render();
                return response()->json([
                    'status' => true,
                    'html' => $view
                ]);
            }
            return response()->json([
                'status' => false
            ]);
        }
        /**
        * show delivery status modal 
        */
        

        /**
         * order packages table : delivery status update by order package id
         */
        public function orderPackageStatusUpdate(Request $request)
        {
            /* $request->order_id;  
            $request->order_package_id;  
            $request->order_package_status; */ 
            $orderPack  =   OrderPackage::findOrFail($request->order_package_id); 
            $orderPack->delivery_status  =  $request->order_package_status; 
            $orderPack->save();

            $data['orderPackage'] = $orderPack;
            if($data['orderPackage'])
            {
                $view = view('admin.order-aliexpress.delivery-status.status',$data)->render();
                return response()->json([
                    'status' => true,
                    'html' => $view
                ]);
            }
            return response()->json([
                'status' => false
            ]);
        }
        /**
        * order packages table : delivery status update by order package id
        */

        /** single single products: 
        * order products table : delivery status update by order_product_id
        * and insert Order trackings table 
        */
        public function orderProductStatusUpdate(Request $request)
        {
            $orderTra  =   new OrderTrack(); 
            $orderTra->order_id    =   $request->order_id; 
            $orderTra->title       =   $request->title; 
            $orderTra->text        =   $request->text; 
            $orderTra->save();

            OrderProduct::findOrFail($request->order_product_id)->update([
                'delivery_status' => $request->status
            ]);

            $data['orderPackage'] =  OrderPackage::findOrFail($request->order_package_id); 
            if($data['orderPackage'])
            {
                $view = view('admin.order-aliexpress.delivery-status.status',$data)->render();
                return response()->json([
                    'status' => true,
                    'html' => $view
                ]);
            }
            return response()->json([
                'status' => false
            ]);
        }
        /** single single products: 
        * order products table : delivery status update by order_product_id
        * and insert Order trackings table 
        */




        /**
        * show order tracking details : modal show
        */
        public function orderTrackingDetails(Request $request)
        {
            $data['orders'] = OrderTrack::where('order_id',$request->id)->latest()->get();
            if($data['orders'])
            {
                $order = Order::findOrFail($request->id);
                $data['order_id'] = $order?$order->order_number:$request->id;
                $view = view('admin.order-aliexpress.tracking.tracking',$data)->render();
                return response()->json([
                    'status' => true,
                    'html' => $view
                ]);
            }
            return response()->json([
                'status' => false
            ]);
        }
        /**
        * show order tracking details : modal show
        */
    //---------------------------------------------may not using this---------------------------------
    //---------------------------------------------may not using this---------------------------------
}
