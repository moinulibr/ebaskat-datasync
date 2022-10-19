<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;

class ChildCategoryController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Childcategory::where(function($q){
            if (! Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) {
                $q->whereNull('deleted_at');
            }
        })->orderBy('id', 'desc')->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
        ->addColumn('category', function (Childcategory $data) {
            return $data->subcategory->category->name;
        })
            ->addColumn('subcategory', function (Childcategory $data) {
                return $data->subcategory->name;
            })
            ->addColumn('status', function (Childcategory $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks ' . $class . '"><option data-val="1" value="' . route('admin-childcat-status', ['id1' => $data->id, 'id2' => 1]) . '" ' . $s . '>Activated</option><option data-val="0" value="' . route('admin-childcat-status', ['id1' => $data->id, 'id2' => 0]) . '" ' . $ns . '>Deactivated</option>/select></div>';
            })
            ->addColumn('attributes', function (Childcategory $data) {
                $buttons = '<div class="action-list"><a data-href="' . route('admin.attr.create.child.category', $data->id) . '" class="attribute" data-toggle="modal" data-target="#attribute"> <i class="fas fa-edit"></i>Create</a>';
                if ($data->attributes()->count() > 0) {
                    $buttons .= '<a href="' . route('admin.attr.manage', $data->id) . '?type=childcategory' . '" class="edit"> <i class="fas fa-edit"></i>Manage</a>';
                }
                $buttons .= '</div>';

                return $buttons;
            })
            ->addColumn('action', function (Childcategory $data) {

                $action = '<div class="action-list">';
                if (Auth::guard('admin')->user()->role->permissionCheck('categories|edit')) {
                    $action .= '<a data-href="' . route('admin-childcat-edit', $data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a>';
                }
                if(! $data->deleted_at)
                {
                    if(Auth::guard('admin')->user()->role->permissionCheck('categories|delete'))
                    {
                        $action .= '<a href="javascript:;" data-href="' . route('admin-childcat-delete', $data->id) . '" data-toggle="modal" data-target="#delete_modal" class="delete"><i class="fas fa-trash-alt"></i></a>';
                    }
                }
                else
                {
                    if (Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) 
                    {
                        $action .= '<a href="javascript:;" data-href="' . route('admin.childcat.restore', $data->id) . '" data-toggle="modal" data-target="#restore_modal" title="restore"><i class="fas fa-reply-all"></i></a>';
                    }
                }
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['status', 'attributes', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }



    //*** GET Request
    public function index(Request $request)
    {
        $search = $request->search ? $request->search : '';
        $child_categories = Childcategory::where(function($q){
            if (! Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) {
                $q->whereNull('deleted_at');
            }
        })->where('name', 'like', '%' . $search . '%')->orderBy('id', 'desc')->paginate(50);
        return view('admin.childcategory.index', compact(['child_categories','search']));
    }

    //*** GET Request
    public function create()
    {
      	$cats = Category::all();
        return view('admin.childcategory.create',compact('cats'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            'slug' => 'unique:childcategories|regex:/^[a-zA-Z0-9\s-]+$/'
                 ];
        $customs = [
            'slug.unique' => 'This slug has already been taken.',
            'slug.regex' => 'Slug Must Not Have Any Special Characters.'
                   ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Childcategory();
        $input = $request->all();
        $data->fill($input)->save();
        //--- Logic Section Ends
        cache()->forget('categories');
        //--- Redirect Section
        $msg = 'New Data Added Successfully.';
        return redirect()->back()->with('success', $msg);
        // return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function edit($id)
    {
    	$cats = Category::all();
        $subcats = Subcategory::all();
        $data = Childcategory::findOrFail($id);
        return view('admin.childcategory.edit',compact('data','cats','subcats'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            'slug' => 'unique:childcategories,slug,'.$id.'|regex:/^[a-zA-Z0-9\s-]+$/'
                 ];
        $customs = [
            'slug.unique' => 'This slug has already been taken.',
            'slug.regex' => 'Slug Must Not Have Any Special Characters.'
                   ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Childcategory::findOrFail($id);
        $input = $request->all();
        $data->update($input);
        //--- Logic Section Ends
        cache()->forget('categories');
        //--- Redirect Section
        $msg = 'Data Updated Successfully.';
        return redirect()->back()->with('success', $msg);
        // return response()->json($msg);
        //--- Redirect Section Ends
    }

      //*** GET Request Status
      public function status($id1,$id2)
        {
            $data = Childcategory::findOrFail($id1);
            $data->status = $id2;
            $data->update();
            cache()->forget('categories');
        }

    //*** GET Request
    public function load($id)
    {
        $subcat = Subcategory::findOrFail($id);
        return view('load.childcategory',compact('subcat'));
    }

    public function destroy($id)
    {
        $data = Childcategory::findOrFail($id);

        if ($data->attributes->count() > 0) {
            $msg = 'Remove the Attributes first !';
            return response()->json(['status' => 'error', 'mgs' => $msg]);
        }

        if ($data->products->count() > 0) {
            //--- Redirect Section
            $msg = 'Remove the products first !';
            return response()->json(['status' => 'error', 'mgs' => $msg]);
        }
        cache()->forget('categories');

        $data->deleted_at = now();
        $data->save();
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }

    public function restore($id)
    {
        $data = Childcategory::findOrFail($id);
        $data->deleted_at = null;
        $data->save();
        $msg = 'Data Restored Successfully.';
        return response()->json($msg);
    }
}
