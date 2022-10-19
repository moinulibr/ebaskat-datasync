<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\VendorInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Str;
use App\Traits\FileStorage;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    use FileStorage;
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //*** JSON Request
    public function datatables()
    {
        $datas = Brand::orderBy('id', 'desc')->get();
        //--- Integrating This Collection Into Datatables
        return DataTables::of($datas)
        ->addColumn('status', function (Category $data) {
            $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
            $s = $data->status == 1 ? 'selected' : '';
            $ns = $data->status == 0 ? 'selected' : '';
            return '<div class="action-list"><select class="process select droplinks ' . $class . '"><option data-val="1" value="' . route('admin.category.status', ['id1' => $data->id, 'id2' => 1]) . '" ' . $s . '>Activated</option><option data-val="0" value="' . route('admin.category.status', ['id1' => $data->id, 'id2' => 0]) . '" ' . $ns . '>Deactivated</option></select></div>';
        })
        ->addColumn('attributes', function (Category $data) {
            $buttons = '<div class="action-list"><a data-href="' . route('admin.attr.create.category', $data->id) . '" class="attribute" data-toggle="modal" data-target="#attribute"> <i class="fas fa-edit"></i>Create</a>';
            if ($data->attributes()->count() > 0) {
                $buttons .= '<a href="' . route('admin.attr.manage', $data->id) . '?type=category' . '" class="edit"> <i class="fas fa-edit"></i>Manage</a>';
            }
            $buttons .= '</div>';

            return $buttons;
        })
        ->addColumn('action', function (Category $data) {

            $action = '<div class="action-list">';
            if (Auth::guard('admin')->user()->role->permissionCheck('categories|edit')) {
                $action .= '<a data-href="' . route('admin.category.edit', $data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a>';
            }

            if (Auth::guard('admin')->user()->role->permissionCheck('categories|delete')) {
                $action .= '<a href="javascript:;" data-href="' . route('admin.category.delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>';
            }
            $action .= '</div>';

            return $action;
        })
        ->rawColumns(['status', 'attributes', 'action'])
        ->toJson(); //--- Returning Json Data To Client Side
    }
  
    //*** GET Request
    public function index(Request $r)
    {
        if($r->ajax()){

            $datas = Brand::where(function($q){
                if (! Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) {
                    $q->whereNull('deleted_at');
                }
            })->orderBy('id', 'desc')->get();

            return DataTables::of($datas)
            ->addColumn('action', function (Brand $data) {

                $action ='<div class="action-list">';

                if(Auth::guard('admin')->user()->role->permissionCheck('brands_manage|edit'))
                {
                    $action .=  '<a data-id="'.  $data->id .'" class="showSingle show"> <i class="fas fa-eye"></i>View</a>';
                }
                if(Auth::guard('admin')->user()->role->permissionCheck('brands_manage|edit'))
                {
                    $action .= '<a data-id="'. $data->id .'" class="edit"><iclass="fas fa-edit"></i>Edit</a>';
                }

                if(! $data->deleted_at)
                {
                    if(Auth::guard('admin')->user()->role->permissionCheck('brands_manage|delete'))
                    {
                        $action .= '<a href="javascript:;" data-href="' . route('admin.brand.delete', $data->id) . '" data-toggle="modal" data-target="#delete_modal" class="delete"><i class="fas fa-trash-alt"></i></a>';
                    }
                }
                else
                {
                    if (Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) 
                    {
                        $action .= '<a href="javascript:;" data-href="' . route('admin.brand.restore', $data->id) . '" data-toggle="modal" data-target="#restore_modal" title="restore"><i class="fas fa-reply-all"></i></a>';
                    }
                }
                $action .= '</div>';
                return $action;
            })
            ->rawColumns(['action'])->addIndexColumn()->toJson();
        }

        $data['merchants'] = $merchants  = VendorInformation::select('id','user_id','shop_name')->get();
        return view('admin.brand.index',$data);
    }
  
    //*** GET Request
    public function create()
    {
        return view('admin.category.create');
    }
  
    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:brands,email',
            'web_address' => 'required|min:2|max:255',
            'logo' => 'mimes:jpeg,jpg,png,svg',
            'slug' => 'required|min:3|unique:brands|regex:/^[a-zA-Z0-9\s-]+$/'
            ];
        $customs =[
            'name.required'   => 'Name is required.',
            'name.min'        => 'Name minimum 2 characters.',
            'name.max'        => 'Name maximum 255 characters.',

            'email.required'  => 'Email is required.',
            'email.unique'    => 'This slug has already been taken.',

            'web_address.required' => 'Web Address is required.',
            'web_address.min'      => 'Web Address minimum 2 characters.',
            'web_address.max'      => 'Web Address maximum 255 characters.',

            'logo.mimes'      => 'Logo Type is Invalid.',

            'slug.required'   => 'Slug is required.',
            'slug.unique'     => 'This slug has already been taken.',
            'slug.regex'      => 'Slug Must Not Have Any Special Characters.'
            ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('status' => 'errors','error' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Brand();
        $input = $request->except('_token') ;//$request->all();
        
        if ($file = $request->file('logo'))
        {
           /*  $name = time().str_replace(' ', '', $file->getClientOriginalName());
            $file->move('assets/images/brands',$name);
            $input['logo'] = $name; */

            $this->destination  = brandImageStorageDestination_hd();//'public/brand';   //its mandatory
            $this->imageWidth   = brandImageWidth_hd();//300; //its mandatory
            $this->imageHeight  = brandImageHeight_hd();//NULL; //its nullable
            $this->file         = $request->file('logo');  //its mandatory
            $input['logo']     = $this->storeImage(); 
        }
        $data->fill($input)->save();
        //cache()->forget('brands');
        //--- Logic Section Ends
        $data->merchants()->attach($request->merchant_id);
        //--- Redirect Section
        $msg = 'New Data Added Successfully.';

        return response()->json([
            'status' => true,
            'message' => $msg 
        ]);
        //--- Redirect Section Ends
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $data['brand'] = Brand::find($request->id);
        $html = view('admin.brand.show',$data)->render();
        return response()->json([
            'status' => true,
            'html' => $html
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $data['merchants'] = $merchants  = VendorInformation::select('id','user_id','shop_name')->get();
        $data['brand'] = Brand::find($request->id);
        $html = view('admin.brand.edit',$data)->render();
        return response()->json([
            'status' => true,
            'html' => $html
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:brands,email,'.$request->id.'',
            'web_address' => 'required|min:2|max:255',
            'logo' => 'mimes:jpeg,jpg,png,svg',
            'slug' => 'required|min:2|unique:brands,slug,'.$request->id.'|regex:/^[a-zA-Z0-9\s-]+$/'
            ];
        $customs =[
            'name.required'   => 'Name is required.',
            'name.min'        => 'Name minimum 2 characters.',
            'name.max'        => 'Name maximum 255 characters.',

            'email.required'  => 'Email is required.',
            'email.unique'    => 'This email has already been taken.',

            'web_address.required' => 'Web Address is required.',
            'web_address.min'      => 'Web Address minimum 2 characters.',
            'web_address.max'      => 'Web Address maximum 255 characters.',

            'logo.mimes'      => 'Logo Type is Invalid.',

            'slug.required'   => 'Slug is required.',
            'slug.unique'     => 'This slug has already been taken.',
            'slug.regex'      => 'Slug Must Not Have Any Special Characters.'
            ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('status' => 'errors','error' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Brand::findOrFail($request->id);
        $input = $request->all();
            if ($file = $request->file('logo'))
            {
                /* $name = time().str_replace(' ', '', $file->getClientOriginalName());
                $file->move('assets/images/brands',$name);
                if($data->logo != null)
                {
                    if (file_exists(public_path().'/assets/images/brands/'.$data->logo)) {
                        unlink(public_path().'/assets/images/brands/'.$data->logo);
                    }
                }
                $input['logo'] = $name; */

                $this->destination  = brandImageStorageDestination_hd();//'public/brand';   //its mandatory
                $this->imageWidth   = brandImageWidth_hd();//300; //its mandatory
                $this->imageHeight  = brandImageHeight_hd();//NULL; //its nullable
                $this->file         = $request->file('logo');  //its mandatory
                $this->imageNameFromDb = $data->logo;  //its mandatory
                $input['logo']     = $this->updateImage(); 
            }

        $data->update($input);
        //cache()->forget('categories');
        //--- Logic Section Ends
        
        $data->merchants()->sync($request->merchant_id);
        //--- Redirect Section
        $msg = 'Data Updated Successfully.';

        return response()->json([
            'status' => true,
            'message' => $msg 
        ]);
        //--- Redirect Section Ends
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data =  Brand::findOrfail($id);
         //If logo Doesn't Exist
        //  if($brand->logo == null){
        //     $brand->deleted_at = date('Y-m-d h:i:s');
        //     $brand->save();
        //     //--- Redirect Section
        //     $msg = 'Data Deleted Successfully.';
        //     return response()->json([
        //         'status' => true,
        //     ]);
        //     //--- Redirect Section Ends
        // }
        //If logo Exist
        // if (file_exists(public_path().'/assets/images/brands/'.$brand->logo)) {
        //     //unlink(public_path().'/assets/images/brands/'.$brand->logo);
        // }
        /* $this->destination  = 'public/brand';  
        $this->imageNameFromDb = $brand->logo;  //its mandatory
        $this->imageDelete(); */ 

        $data->deleted_at = now();
        $data->save(); 
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function restore($id)
    {
        $data =  Brand::findOrfail($id);
        $data->deleted_at = null;
        $data->save(); 
        $msg = 'Data Restored Successfully.';
        return response()->json($msg);
    }
}
