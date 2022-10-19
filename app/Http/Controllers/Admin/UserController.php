<?php

namespace App\Http\Controllers\Admin;

use App\Classes\EBasketMailer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

use App\Traits\FileStorage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use FileStorage;
    
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = User::where(function($q){
            if (! Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) {
                $q->whereNull('deleted_at');
            }
        })
        ->where('is_vendor', '=', 0)
        ->orderBy('created_at','desc')
        ->get();
        //--- Integrating This Collection Into Datatables
        return DataTables::of($datas)
        ->addColumn('created_at', function (User $data) {
            return $data->created_at->diffForHumans();
        })
        ->addColumn('action', function (User $data) {
            $class = $data->ban == 0 ? 'drop-success' : 'drop-danger';
            $s = $data->ban == 1 ? 'selected' : '';
            $ns = $data->ban == 0 ? 'selected' : '';

            $ban = '<select class="process select droplinks ' . $class . '">' .
            '<option data-val="0" value="' . route('admin.user.ban', ['id1' => $data->id, 'id2' => 1]) . '" ' . $s . '>Block</option>' .
            '<option data-val="1" value="' . route('admin.user.ban', ['id1' => $data->id, 'id2' => 0]) . '" ' . $ns . '>UnBlock</option></select>';

            $action = '<div class="action-list">';
            if(Auth::guard('admin')->user()->role->permissionCheck('customers|detail'))
            {
                $action .= '<a href="' . route('admin.user.show', $data->id) . '" > <i class="fas fa-eye"></i> Details</a>';
            }

            // can edit and block unblock
            if(Auth::guard('admin')->user()->role->permissionCheck('customers|edit'))
            {
                $action .= '<a data-href="' . route('admin.user.edit', $data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a>'. $ban;
            }

            if(Auth::guard('admin')->user()->role->permissionCheck('customers|send_email'))
            {
                $action .= '<a href="javascript:;" class="send" data-email="' . $data->email . '" data-toggle="modal" data-target="#vendorform"><i class="fas fa-envelope"></i> Send</a>';
            }

            // delete
            if(! $data->deleted_at)
            {
                if(Auth::guard('admin')->user()->role->permissionCheck('customers|delete'))
                {
                    $action .= '<a href="javascript:;" data-href="' . route('admin.user.delete', $data->id) . '" data-toggle="modal" data-target="#delete_modal" class="delete"><i class="fas fa-trash-alt"></i></a>';
                }
            }
            else
            {
                if (Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) {

                    $action .= '<a href="javascript:;" data-href="' . route('admin.user.restore', $data->id) . '" data-toggle="modal" data-target="#restore_modal" title="restore"><i class="fas fa-reply-all"></i></a>';
                }
            }
            
            // password reset
            if(Auth::guard('admin')->user()->role->permissionCheck('reset_password'))
            {
                $action .= '<a href="javascript:;" data-href="' . route('admin.user.reset-password', $data->id) . '" onclick="resetOperation(this)" ><i class="fas fa-undo"></i>Reset Password</a>';
            }

            $action .= '</div>';
            return $action;
        })
        ->rawColumns(['action'])
        ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.user.index');
    }

    //*** GET Request
    public function show($id)
    {
        if(!User::where('id',$id)->exists())
        {
            return redirect()->route('admin.dashboard')->with('unsuccess',__('Sorry the page does not exist.'));
        }
        $data = User::findOrFail($id);
        return view('admin.user.show',compact('data'));
    }

    //*** GET Request
    public function ban($id1,$id2)
    {
        $user = User::findOrFail($id1);
        $user->ban = $id2;
        $user->update();

    }

    //*** GET Request    
    public function edit($id)
    {
        $data = User::findOrFail($id);
        return view('admin.user.edit',compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
                'photo' => 'mimes:jpeg,jpg,png,svg',
                ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        $user = User::findOrFail($id);
        $data = $request->all();
        if ($file = $request->file('photo'))
        {
            /* $name = time().str_replace(' ', '', $file->getClientOriginalName());
            $file->move('assets/images/users',$name);
            if($user->photo != null)
            {
                if (file_exists(public_path().'/assets/images/users/'.$user->photo)) {
                    unlink(public_path().'/assets/images/users/'.$user->photo);
                }
            }
            $data['photo'] = $name; */
            $this->destination  = userProfilePictureStorageDestination_hd();//'public/users';   //its mandatory
            $this->imageWidth   = userProfilePictureWidth_hd();//300; //its mandatory
            $this->imageHeight  = userProfilePictureHeight_hd();//NULL; //its nullable
            $this->file         = $request->file('photo');  //its mandatory
            $this->imageNameFromDb = $user->photo;  //its mandatory
            $data['photo']     = $this->updateImage(); 
        }
        $user->update($data);
        $msg = 'Customer Information Updated Successfully.';
        return response()->json($msg);   
    }

    //*** GET Request Delete
    /* public function destroy($id)
    {
        $user = User::findOrFail($id);

        if($user->reports->count() > 0)
        {
            foreach ($user->reports as $gal) {
                $gal->delete();
            }
        }

        if($user->shippings->count() > 0)
        {
            foreach ($user->shippings as $gal) {
                $gal->delete();
            }
        }

        if($user->packages->count() > 0)
        {
            foreach ($user->packages as $gal) {
                $gal->delete();
            }
        }
        
        if($user->ratings->count() > 0)
        {
            foreach ($user->ratings as $gal) {
                $gal->delete();
            }
        }

        if($user->notifications->count() > 0)
        {
            foreach ($user->notifications as $gal) {
                $gal->delete();
            }
        }

        if($user->wishlists->count() > 0)
        {
            foreach ($user->wishlists as $gal) {
                $gal->delete();
            }
        }

        if($user->withdraws->count() > 0)
        {
            foreach ($user->withdraws as $gal) {
                $gal->delete();
            }
        }

        if($user->socialProviders->count() > 0)
        {
            foreach ($user->socialProviders as $gal) {
                $gal->delete();
            }
        }

        if($user->conversations->count() > 0)
        {
            foreach ($user->conversations as $gal) {
            if($gal->messages->count() > 0)
            {
                foreach ($gal->messages as $key) {
                    $key->delete();
                }
            }
                $gal->delete();
            }
        }
        if($user->comments->count() > 0)
        {
            foreach ($user->comments as $gal) {
            if($gal->replies->count() > 0)
            {
                foreach ($gal->replies as $key) {
                    $key->delete();
                }
            }
                $gal->delete();
            }
        }

        if($user->replies->count() > 0)
        {
            foreach ($user->replies as $gal) {
                if($gal->subreplies->count() > 0)
                {
                    foreach ($gal->subreplies as $key) {
                        $key->delete();
                    }
                }
                $gal->delete();
            }
        }

        if($user->favorites->count() > 0)
        {
            foreach ($user->favorites as $gal) {
                $gal->delete();
            }
        }

        if($user->subscribes->count() > 0)
        {
            foreach ($user->subscribes as $gal) {
                $gal->delete();
            }
        }

        if($user->services->count() > 0)
        {
            foreach ($user->services as $gal) {
                if (file_exists(public_path().'/assets/images/services/'.$gal->photo)) {
                    unlink(public_path().'/assets/images/services/'.$gal->photo);
                }
                $gal->delete();
            }
        }

        if($user->withdraws->count() > 0)
        {
            foreach ($user->withdraws as $gal) {
                $gal->delete();
            }
        }

        if($user->products->count() > 0)
        {
            // PRODUCT
            foreach ($user->products as $prod) {
                if($prod->galleries->count() > 0)
                {
                    foreach ($prod->galleries as $gal) {
                            if (file_exists(public_path().'/assets/images/galleries/'.$gal->photo)) {
                                unlink(public_path().'/assets/images/galleries/'.$gal->photo);
                            }
                        $gal->delete();
                    }

                }
                if($prod->ratings->count() > 0)
                {
                    foreach ($prod->ratings as $gal) {
                        $gal->delete();
                    }
                }
                if($prod->wishlists->count() > 0)
                {
                    foreach ($prod->wishlists as $gal) {
                        $gal->delete();
                    }
                }

                if($prod->clicks->count() > 0)
                {
                    foreach ($prod->clicks as $gal) {
                        $gal->delete();
                    }
                }

                if($prod->comments->count() > 0)
                {
                    foreach ($prod->comments as $gal) {
                    if($gal->replies->count() > 0)
                    {
                        foreach ($gal->replies as $key) {
                            $key->delete();
                        }
                    }
                        $gal->delete();
                    }
                }

                if (file_exists(public_path().'/assets/images/products/'.$prod->photo)) {
                    unlink(public_path().'/assets/images/products/'.$prod->photo);
                }

                $prod->delete();
            }


            // PRODUCT ENDS

        }
        // OTHER SECTION 

        if($user->senders->count() > 0)
        {
            foreach ($user->senders as $gal) {
            if($gal->messages->count() > 0)
            {
                foreach ($gal->messages as $key) {
                    $key->delete();
                }
            }
                $gal->delete();
            }
        }

        if($user->recievers->count() > 0)
        {
            foreach ($user->recievers as $gal) {
            if($gal->messages->count() > 0)
            {
                foreach ($gal->messages as $key) {
                    $key->delete();
                }
            }
                $gal->delete();
            }
        }

        if($user->conversations->count() > 0)
        {
            foreach ($user->conversations as $gal) {
            if($gal->messages->count() > 0)
            {
                foreach ($gal->messages as $key) {
                    $key->delete();
                }
            }
                $gal->delete();
            }
        }

        if($user->vendororders->count() > 0)
        {
            foreach ($user->vendororders as $gal) {
                $gal->delete();
            }
        }

        if($user->notivications->count() > 0)
        {
            foreach ($user->notivications as $gal) {
                $gal->delete();
            }
        }

        // OTHER SECTION ENDS

        //If Photo Doesn't Exist
        if($user->photo == null){
            $user->delete();
            //--- Redirect Section     
            $msg = 'Data Deleted Successfully.';
            return response()->json($msg);      
            //--- Redirect Section Ends 
        }
        //If Photo Exist
        if (file_exists(public_path().'/assets/images/users/'.$user->photo)) {
                unlink(public_path().'/assets/images/users/'.$user->photo);
                }
        $user->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);      
        //--- Redirect Section Ends    
    } */

    // soft delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->deleted_at = now();
        $user->save();
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }
    // restore user
    public function restore($id)
    {
        $user = User::findOrFail($id);
        $user->deleted_at = null;
        $user->save();
        $msg = 'Data Restored Successfully.';
        return response()->json($msg);
    }

    // reset password
    public function resetPassword($id)
    {
        $user = User::find($id);
        if(!$user)
        {
            return response()->json(['error'=>'user not found'], 404);
        }

        $new_pass = rand();
        $user->password = Hash::make($new_pass);
        $user->save();

        // sending mail
        $subject = "Password Reseted By Admin";
        $msg = 'Your password is reseted to : '. $new_pass . ' by admin. <br> Please login to your account and change the password as soon as you can.';
            $data = [
                'to' => $user->email,
                'subject' => $subject,
                'body' => $msg,
            ];
            $mailer = new EBasketMailer();
            $mailer->sendCustomMail($data);
        // mail ends
        return response()->json(['success'=>'Password reset']);
    }

    //*** JSON Request
    public function withdrawdatatables()
    {
        $datas = Withdraw::where('type', '=', 'user')->orderBy('id', 'desc')->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
        ->addColumn('email', function (Withdraw $data) {
            $email = $data->user->email;
            return $email;
        })
            ->addColumn('phone', function (Withdraw $data) {
                $phone = $data->user->phone;
                return $phone;
            })
            ->editColumn('status', function (Withdraw $data) {
                $status = ucfirst($data->status);
                return $status;
            })
            ->editColumn('amount', function (Withdraw $data) {
                $amount = '€' . round($data->amount, 2);
                return $amount;
            })
            ->addColumn('action', function (Withdraw $data) {
                $action = '<div class="action-list"><a data-href="' . route('admin.withdraw.show', $data->id) . '" class="view details-width" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i> Details</a>';
                if ($data->status == "pending") {
                    $action .= '<a data-href="' . route('admin.withdraw.accept', $data->id) . '" data-toggle="modal" data-target="#confirm-delete"> <i class="fas fa-check"></i> Accept</a><a data-href="' . route('admin.withdraw.reject', $data->id) . '" data-toggle="modal" data-target="#confirm-delete1"> <i class="fas fa-trash-alt"></i> Reject</a>';
                }
                $action .= '</div>';
                return $action;
            })
            ->rawColumns(['name', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function withdraws()
    {
        return view('admin.user.withdraws');
    }

    //*** GET Request       
    public function withdrawdetails($id)
    {
        $withdraw = Withdraw::findOrFail($id);
        return view('admin.user.withdraw-details', compact('withdraw'));
    }

    //*** GET Request   
    public function accept($id)
    {
        $withdraw = Withdraw::findOrFail($id);
        $data['status'] = "completed";
        $withdraw->update($data);
        //--- Redirect Section     
        $msg = 'Withdraw Accepted Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends   
    }

    //*** GET Request   
    public function reject($id)
    {
        $withdraw = Withdraw::findOrFail($id);
        $account = User::findOrFail($withdraw->user->id);
        $account->affilate_income = $account->affilate_income + $withdraw->amount + $withdraw->fee;
        $account->update();
        $data['status'] = "rejected";
        $withdraw->update($data);
        //--- Redirect Section     
        $msg = 'Withdraw Rejected Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends   
    }


    //===============================================================
    //customer coupons 
    public function customerCouponAbleList()
    {
        //$data['customrs'] = User:: whereNull('delete_at')->paginate(2); 
        return view('admin.user.customer-coupon.couponable');
    }

    public function customerCouponAbleListByAjax(Request $request)
    {
        $products = User::query();
        if($request->pname)
        {
            $products->where('name','like',"%".$request->pname."%")
            ->orWhere('email','like',"%".$request->pname."%")
            ->orWhere('phone','like',"%".$request->pname."%");
        }
        $data['customrs'] = $products->whereNull('deleted_at')
                            ->where('is_vendor', '=', 0)
                            ->latest()->paginate(100);
        $view = view('admin.user.customer-coupon.ajax-response-customer-coupon-list',$data)->render();
        return response()->json([
            'status' => true,
            'data' => $view 
        ]);
    
    }


    public function customerCouponActivateStatus($id1,$id2)
    {
        // id1 = user id
        // id2 = coupon_apply
        $user = User::findOrFail($id1);
        $user->coupon_apply = $id2;
        $user->update();
    }

    public function customerCouponBulkActivate(Request $request)
    {
        $user = User::whereIn('id',$request->ids)->update(['coupon_apply'=>1]);
        return response()->json([
            'status' => true,
            'message' => "Activate Successfully"
        ]);
    }
    public function customerCouponBulkInactivate(Request $request)
    {
        $user = User::whereIn('id',$request->ids)->update(['coupon_apply'=>0]);
        return response()->json([
            'status' => true,
            'message' => "Deactivate Successfully"
        ]);
    }


}