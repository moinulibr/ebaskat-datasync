<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Counter;
use App\Models\Order;
use App\Models\Blog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Traits\FileStorage;
class DashboardController extends Controller
{
    use FileStorage;
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $data['pendingOrder']               =   Order::select('status')->where('status','=','pending')->count();
        $data['processingOrder']            =   Order::select('status')->where('status','=','processing')->count();
        $data['completedOrder']             =   Order::select('status')->where('status','=','completed')->count();
        $data['last30DaysCustomerCount']    =   User::select('is_vendor','created_at')->where('is_vendor',0)->where( 'created_at', '>', Carbon::now()->subDays(30))->count();
        $data['last30DaysOrderCount']       =   Order::select('status','created_at')->where('status','=','completed')->where( 'created_at', '>', Carbon::now()->subDays(30))->count();

        /* for($i = 0; $i <= 30; $i++) {
            $totalsales[] = [
                'date' => date("Y-m-d", strtotime('-'. $i .' days')),
                'value' => $this->orderQuery('completed',$i)
            ];
            $paidamount[] = [
                'date' => date("Y-m-d", strtotime('-'. $i .' days')),
                'value' =>  Order::where('payment_status','=','completed')->whereDate('created_at', '=', date("Y-m-d", strtotime('-'. $i .' days')))->sum('pay_amount')
            ];

            $orders[] = [
                'date' => date("Y-m-d", strtotime('-'. $i .' days')),
                'pending' => $this->orderQuery('pending',$i),
                'processing' => $this->orderQuery('processing',$i),
                'completed' => $this->orderQuery('completed',$i),
                'declined' => $this->orderQuery('declined',$i)
            ];
        }

        $data['totalsales'] = json_encode(array_reverse($totalsales));
        $data['transactionAmount'] = json_encode(array_reverse($paidamount));
        $data['statuswiseorder'] = json_encode(array_reverse($orders)); */
        $data['totalCustomer']              =   User::select('is_vendor')->where('is_vendor',0)->count();
        $data['totalProducts']              =   Product::select('id')->count();
        //$data['totalBlogs']                 =   Blog::select('')->count();
        return view('admin.dashboard',$data);
    }

    public function dashboardChartLoadByAjax()
    {
        for($i = 0; $i <= 30; $i++) {
            $totalsales[] = [
                'date' => date("Y-m-d", strtotime('-'. $i .' days')),
                'value' => $this->orderQuery('completed',$i)
            ];
            $paidamount[] = [
                'date' => date("Y-m-d", strtotime('-'. $i .' days')),
                'value' =>  Order::where('payment_status','=','completed')->whereDate('created_at', '=', date("Y-m-d", strtotime('-'. $i .' days')))->sum('pay_amount')
            ];

            $orders[] = [
                'date' => date("Y-m-d", strtotime('-'. $i .' days')),
                'pending' => $this->orderQuery('pending',$i),
                'processing' => $this->orderQuery('processing',$i),
                'completed' => $this->orderQuery('completed',$i),
                'declined' => $this->orderQuery('declined',$i)
            ];
        }
        $data['totalsales'] = json_encode(array_reverse($totalsales));
        $data['transactionAmount'] = json_encode(array_reverse($paidamount));
        $data['statuswiseorder'] = json_encode(array_reverse($orders));
        $html = view('admin.dashboard_ajax_response',$data)->render();
        return response()->json([
            'status' => true,
            'data'  => $html,
        ]);
    }


    public function orderQuery($status,$value){
        return Order::select('status','created_at')->where('status','=',$status)->whereDate('created_at', '=', date("Y-m-d", strtotime('-'. $value .' days')))->count();
    }

    public function profile()
    {
        $data = Auth::guard('admin')->user();
        return view('admin.profile',compact('data'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function profileUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'mimes:jpeg,jpg,png,svg',
            'email' => 'unique:admins,email,' . Auth::guard('admin')->user()->id
        ]);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $input = $request->all();
        $data = Auth::guard('admin')->user();
        if ($request->file('photo')) {
            $this->destination  = adminProfilePictureStorageDestination_hd();//'public/admins';   //its mandatory
            $this->imageWidth   = adminProfilePictureWidth_hd();//300; //its mandatory
            $this->imageHeight  = adminProfilePictureHeight_hd();//NULL; //its nullable
            $this->file         = $request->file('photo');  //its mandatory
            $this->imageNameFromDb = $data->photo;  //its mandatory
            $input['photo']     = $this->updateImage();
        }
        $data->update($input);
        return response()->json('Successfully updated your profile');
    }

    public function passwordReset()
    {
        $data = Auth::guard('admin')->user();
        return view('admin.password',compact('data'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'newPassword' => 'min:8',
            'confirmNewPassword' => 'min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $admin = Auth::guard('admin')->user();
        if ($request->currentPassword){
            if (Hash::check($request->currentPassword, $admin->password)){
                if ($request->newPassword == $request->confirmNewPassword){
                    $admin->password = Hash::make($request->newPassword);
                }else{
                    return response()->json(['errors' => [ 0 => 'Confirm password does not match.' ]]);
                }
            }else{
                return response()->json(['errors' => [ 0 => 'Current password Does not match.' ]]);
            }
        }
        $admin->save();
        return response()->json('Successfully change your password');
    }
}
