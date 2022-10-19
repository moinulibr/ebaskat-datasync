<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Package::where(function($q){
            if (! Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) {
                $q->whereNull('deleted_at');
            }
        })->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
        ->editColumn('price', function (Package $data) {
            $price = $data->price;
            $price = '€' . $price;
            return  $price;
        })
            ->addColumn('action', function (Package $data) {

                $action = '<div class="action-list">';

                if(Auth::guard('admin')->user()->role->permissionCheck('general_settings|edit'))
                {
                    $action .= '<a data-href="' . route('admin-package-edit', $data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a>';
                }
                
                if(! $data->deleted_at)
                {
                    if(Auth::guard('admin')->user()->role->permissionCheck('general_settings|delete'))
                    {
                        $action .= '<a href="javascript:;" data-href="' .route('admin-package-delete', $data->id) . '" data-toggle="modal" data-target="#delete_modal" class="delete"><i class="fas fa-trash-alt"></i></a>';
                    }
                }
                else
                {
                    if (Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) 
                    {
                        $action .= '<a href="javascript:;" data-href="' . route('admin-package-restore', $data->id) . '" data-toggle="modal" data-target="#restore_modal" title="restore"><i class="fas fa-reply-all"></i></a>';
                    }
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
        return view('admin.package.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.package.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = ['title' => 'unique:packages'];
        $customs = ['title.unique' => 'This title has already been taken.'];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Package();
        $input = $request->all();
        $input['price'] = $input['price'];
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section        
        $msg = 'New Data Added Successfully.';
        return response()->json($msg);      
        //--- Redirect Section Ends    
    }

    //*** GET Request
    public function edit($id)
    {
        $data = Package::findOrFail($id);
        return view('admin.package.edit',compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = ['title' => 'unique:packages,title,'.$id];
        $customs = ['title.unique' => 'This title has already been taken.'];
        $validator = Validator::make($request->all(), $rules, $customs);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }        
        //--- Validation Section Ends

        //--- Logic Section
        $data = Package::findOrFail($id);
        $input = $request->all();
        $input['price'] = $input['price'];
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);      
        //--- Redirect Section Ends            
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Package::findOrFail($id);
        $data->deleted_at = now();
        $data->save(); 
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }

    public function restore($id)
    {
        $data = Package::findOrFail($id);
        $data->deleted_at = null;
        $data->save(); 
        $msg = 'Data Restored Successfully.';
        return response()->json($msg);
    }
}
