<?php

namespace App\Http\Controllers\Admin;

use App\Classes\EBasketMailer;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Traits\FileStorage;
use Illuminate\Support\Facades\Hash;


class StaffController extends Controller
{
    use FileStorage;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Admin::where(function($q){
            $q->where('role_id', '!=', 0);
            $q->where('id', '!=', Auth::guard('admin')->user()->id);

            if (! Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) {
                $q->whereNull('deleted_at');
            }
        })->orderBy('id')->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
        ->addColumn('role', function (Admin $data) {
            $role = $data->role_id == 0 ? 'No Role' : $data->role->name;
            return $role;
        })
            ->addColumn('action', function (Admin $data) {

                $action = null;

                if(Auth::guard('admin')->user()->role->permissionCheck('manage_staffs|edit'))
                {
                    $action .= '<a data-href="' . route('admin-staff-edit', $data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a>';

                }
                if(! $data->deleted_at)
                {
                    if(Auth::guard('admin')->user()->role->permissionCheck('manage_staffs|delete'))
                    {
                        $action .= '<a href="javascript:;" data-href="' .route('admin-staff-delete', $data->id) . '" data-toggle="modal" data-target="#delete_modal" class="delete"><i class="fas fa-trash-alt"></i></a>';
                    }
                }
                else
                {
                    if (Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) 
                    {
                        $action .= '<a href="javascript:;" data-href="' . route('admin.staff.restore', $data->id) . '" data-toggle="modal" data-target="#restore_modal" title="restore"><i class="fas fa-reply-all"></i></a>';
                    }
                }
                // password reset
                if(Auth::guard('admin')->user()->role->permissionCheck('reset_password'))
                {
                    $action .= '<a href="javascript:;" data-href="' . route('admin.staff.reset-password', $data->id) . '" onclick="resetOperation(this)"><i class="fas fa-undo"></i>Reset Password</a>';
                }

                return '<div class="action-list"><a data-href="' . route('admin-staff-show', $data->id) . '" class="view details-width" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i>Details</a>' . $action . '</div>';
            })
            ->rawColumns(['action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
  	public function index()
    {
        return view('admin.staff.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.staff.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            'photo' => 'required|mimes:jpeg,jpg,png,svg',
            'password' => 'min:8',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Admin();
        $input = $request->all();
        if ($file = $request->file('photo'))
         {
            /* $name = time().str_replace(' ', '', $file->getClientOriginalName());
            $file->move('assets/images/admins',$name);
            $input['photo'] = $name; */

            $this->destination  = adminProfilePictureStorageDestination_hd();//'public/admins';   //its mandatory
            $this->imageWidth   = adminProfilePictureWidth_hd();//300; //its mandatory
            $this->imageHeight  = adminProfilePictureHeight_hd();//NULL; //its nullable
            $this->file         = $request->file('photo');  //its mandatory
            $input['photo']     = $this->storeImage();
        }
        $input['role'] = 'Staff';
        $input['password'] = Hash::make($request['password']);
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'New Data Added Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }


    public function edit($id)
    {
        $data = Admin::findOrFail($id);
        return view('admin.staff.edit',compact('data'));
    }

    public function update(Request $request,$id)
    {
        //--- Validation Section
        if($id != Auth::guard('admin')->user()->id)
        {
            $rules =
            [
                'photo' => 'mimes:jpeg,jpg,png,svg',
                'email' => 'unique:admins,email,'.$id
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            //--- Validation Section Ends
            $input = $request->all();
            $data = Admin::findOrFail($id);
                if ($file = $request->file('photo'))
                {
                    /* $name = time().str_replace(' ', '', $file->getClientOriginalName());
                    $file->move('assets/images/admins/',$name);
                    if($data->photo != null)
                    {
                        if (file_exists(public_path().'/assets/images/admins/'.$data->photo)) {
                            unlink(public_path().'/assets/images/admins/'.$data->photo);
                        }
                    }
                    $input['photo'] = $name; */
                    $this->destination  = adminProfilePictureStorageDestination_hd();//'public/admins';   //its mandatory
                    $this->imageWidth   = adminProfilePictureWidth_hd();//300; //its mandatory
                    $this->imageHeight  = adminProfilePictureHeight_hd();//NULL; //its nullable
                    $this->file         = $request->file('photo');  //its mandatory
                    $this->imageNameFromDb = $data->photo;  //its mandatory
                    $input['photo']     = $this->updateImage();
                }
            if($request->password == ''){
                $input['password'] = $data->password;
            }
            else{
                $input['password'] = Hash::make($request->password);
            }
            $data->update($input);
            $msg = 'Data Updated Successfully.';
            return response()->json($msg);
        }
        else{
            $msg = 'You can not change your role.';
            return response()->json($msg);
        }

    }

    //*** GET Request
    public function show($id)
    {
        $data = Admin::findOrFail($id);
        return view('admin.staff.show',compact('data'));
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Admin::findOrFail($id);

        //If Photo Doesn't Exist
        // if($data->photo == null){
        //     $data->delete();
        //     //--- Redirect Section
        //     $msg = 'Data Deleted Successfully.';
        //     return response()->json($msg);
        //     //--- Redirect Section Ends
        // }
        //If Photo Exist
       /*  if (file_exists(public_path().'/assets/images/admins/'.$data->photo)) {
            unlink(public_path().'/assets/images/admins/'.$data->photo);
        } */
        // $this->destination  = 'public/admins';          //its mandatory
        // $this->imageNameFromDb = $data->photo;    //its mandatory
        // $this->imageDelete();
        $data->deleted_at = now();
        $data->save();
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }

    public function restore($id)
    {
        $data = Admin::findOrFail($id);
        $data->deleted_at = null;
        $data->save();
        $msg = 'Data Restored Successfully.';
        return response()->json($msg);
    }

    public function resetPassword($id)
    {
        $admin = Admin::find($id);
        if(!$admin)
        {
            return response()->json(['error'=>'admin not found'], 404);
        }

        $new_pass = rand();
        $admin->password = Hash::make($new_pass);
        $admin->save();

        // sending mail
        $subject = "Password Reseted By Admin";
        $msg = 'Your password is reseted to : '. $new_pass . ' by admin. <br> Please login to your account and change the password as soon as you can.';

        $data = [
            'to' => $admin->email,
            'subject' => $subject,
            'body' => $msg,
        ];
        $mailer = new EBasketMailer();
        $mailer->sendCustomMail($data);
        
        // mail ends
        return response()->json(['success'=>'Password reset']);
    }
}
