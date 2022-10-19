<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderTrack;
use App\Models\OrderPackage;
use App\Models\OrderProduct;

use Illuminate\Http\Request;
use App\Traits\AliexpressOrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\OrderStatusMailing;
class AliexpressOrderStatusController extends Controller
{
    use AliexpressOrderStatus, OrderStatusMailing;
    

    /**
    * Order Package status syncing :  Individually
    */
    public function orderPackageStatusUpdateBySyncingIndividually(Request $request)
    {
        $this->alix_order_id = $request->id;
        $deliveryStatus =  $this->getOrderStatusFromObaskat();

        $ebOrderPackage = OrderPackage::where('alix_order_id',$request->id)->update([
            'delivery_status' => ucfirst($deliveryStatus) 
        ]);
        
        $pack = OrderPackage::where('alix_order_id',$request->id)->first();
        $data['order'] = Order::findOrFail($pack->order_id);
        //partial order status changing message send to the customer by mail   
        if($request->email_applicable == 1) 
        {
            $this->mainOrderDetails = $data['order'];
            $this->splittedOrderStatusChangingMail(); 
        }
        //partial order status changing message send to the customer by mail

        return response()->json([
            'status' => true,
        ]);
    }
    /**
    * Order Package status syncing :  Individually
    */


    /**
    * Order Package status syncing :  Bulk
    */
    public function orderPackageStatusUpdateBySyncingBulking(Request $request)
    {
        if(count($request->ids) > 0) 
        {
            $orderPackages =  OrderPackage::where("alix_order_id","!=",NULL)
                                    ->where('merchant_id',defaultEbaskatPrimeId_hd())
                                    ->select('alix_order_id','order_id')
                                    ->whereIn('order_id',$request->ids)
                                    ->pluck('alix_order_id')
                                    ->toArray();
            if(count($orderPackages) > 0)
            {
                foreach($orderPackages as $aliexpressOrderId)
                {
                    $this->alix_order_id = $aliexpressOrderId;
                    $deliveryStatus =  $this->getOrderStatusFromObaskat();

                    OrderPackage::where('alix_order_id',$aliexpressOrderId)->update([
                        'delivery_status' => ucfirst($deliveryStatus) 
                    ]);

                    $pack = OrderPackage::where('alix_order_id',$aliexpressOrderId)->first();
                    $data['order'] = Order::findOrFail($pack->order_id);
                    //partial order status changing message send to the customer by mail
                    if($request->email_applicable == 1) 
                    {    
                        $this->mainOrderDetails = $data['order'];
                        $this->splittedOrderStatusChangingMail();
                    } 
                    //partial order status changing message send to the customer by mail
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

        $data['order'] = Order::findOrFail($orderPack->order_id);
        //partial order status changing message send to the customer by mail    
        if($request->email_applicable == 1)
        {
            $this->mainOrderDetails = $data['order'];
            $this->splittedOrderStatusChangingMail();
        }
        //partial order status changing message send to the customer by mail 

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
        
        $data['order'] = Order::findOrFail($data['orderPackage']->order_id);
        //partial order status changing message send to the customer by mail
        if($request->email_applicable == 1)
        {    
            $this->mainOrderDetails = $data['order'];
            $this->splittedOrderStatusChangingMail();
        } 
        //partial order status changing message send to the customer by mail 

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
