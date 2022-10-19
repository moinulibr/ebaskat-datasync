<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Category;
use App\Traits\FileStorage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    use FileStorage;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Category::where(function($q){
            if (! Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) {
                $q->whereNull('deleted_at');
            }
        })->orderBy('id', 'desc')->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
        ->addColumn('status', function (Category $data) {
            $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
            $s = $data->status == 1 ? 'selected' : '';
            $ns = $data->status == 0 ? 'selected' : '';
            return '<div class="action-list"><select class="process select droplinks ' . $class . '"><option data-val="1" value="' . route('admin.category.status', ['id1' => $data->id, 'id2' => 1]) . '" ' . $s . '>Activated</option><option data-val="0" value="' . route('admin.category.status', ['id1' => $data->id, 'id2' => 0]) . '" ' . $ns . '>Deactivated</option></select></div>';
        })
            ->addColumn('attributes', function (Category $data) {
                $buttons = '<div class="action-list">';
                if(Auth::guard('admin')->user()->role->permissionCheck('categories|edit'))
                {
                    $buttons .= '<a data-href="' . route('admin.attr.create.category', $data->id) . '" class="attribute" data-toggle="modal" data-target="#attribute"> <i class="fas fa-edit"></i>Create</a>';
                    if ($data->attributes()->count() > 0) {
                        $buttons .= '<a href="' . route('admin.attr.manage', $data->id) . '?type=category' . '" class="edit"> <i class="fas fa-edit"></i>Manage</a>';
                    }
    
                    return $buttons;
                }
                $buttons .= '</div>';
                return $buttons;
            })
            ->addColumn('action', function (Category $data) {

                $action = '<div class="action-list">';
                if(Auth::guard('admin')->user()->role->permissionCheck('categories|edit'))
                {
                    $action .= '<a data-href="' . route('admin.category.edit', $data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a>';
                }

                if(! $data->deleted_at)
                {
                    if(Auth::guard('admin')->user()->role->permissionCheck('categories|delete'))
                    {
                        $action .= '<a href="javascript:;" data-href="' .route('admin.category.delete', $data->id) . '" data-toggle="modal" data-target="#delete_modal" class="delete"><i class="fas fa-trash-alt"></i></a>';
                    }
                }
                else
                {
                    if (Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) 
                    {
                        $action .= '<a href="javascript:;" data-href="' . route('admin.cat.restore', $data->id) . '" data-toggle="modal" data-target="#restore_modal" title="restore"><i class="fas fa-reply-all"></i></a>';
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
        $categorys = Category::where(function($q){
            if (! Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) {
                $q->whereNull('deleted_at');
            }
        })->where('name', 'like', '%' . $search . '%')->orderBy('id', 'desc')->paginate(50);
        return view('admin.category.index', compact(['categorys','search']));
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
            'photo' => 'mimes:jpeg,jpg,png,svg',
            'slug' => 'unique:categories|regex:/^[a-zA-Z0-9\s-]+$/'
                 ];
        $customs = [
            'photo.mimes' => 'Icon Type is Invalid.',
            'slug.unique' => 'This slug has already been taken.',
            'slug.regex' => 'Slug Must Not Have Any Special Characters.'
                   ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Category();
        $input = $request->all();
        if ($file = $request->file('photo'))
        {
            /* $name = time().str_replace(' ', '', $file->getClientOriginalName());
            $file->move('assets/images/categories',$name);
            $input['photo'] = $name; */

            $this->destination  = categoryImageStorageDestination_hd();//'public/categories';   //its mandatory
            $this->imageWidth   = categoryImageWidth_hd();//300; //its mandatory
            $this->imageHeight  = categoryImageHeight_hd();//150; //its nullable
            $this->file         = $request->file('photo');  //its mandatory
            $input['photo']     = $this->storeImage(); 
        }

        if ($request->is_featured == ""){
            $input['is_featured'] = 0;
        }
        else {
                $input['is_featured'] = 1;
                //--- Validation Section
                $rules = [
                    'image' => 'required|mimes:jpeg,jpg,png,svg'
                        ];
                $customs = [
                    'image.required' => 'Feature Image is required.',
                    'image.mimes' => 'Feature Image Type is Invalid.'
                        ];
                $validator = Validator::make($request->all(), $rules, $customs);

                if ($validator->fails()) {
                    return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
                }
                //--- Validation Section Ends
                if ($file = $request->file('image'))
                {
                   /* $name = time().str_replace(' ', '', $file->getClientOriginalName());
                   $file->move('assets/images/categories',$name);
                   $input['image'] = $name; */

                   $this->destination  = categoryImageStorageDestination_hd();//'public/categories';   //its mandatory
                   $this->imageWidth   = categoryImageWidth_hd();//500; //its mandatory
                   $this->imageHeight  = categoryImageHeight_hd();//300; //its nullable
                   $this->file         = $request->file('image');  //its mandatory
                   $input['image']     = $this->storeImage(); 
                }
        }
        $data->fill($input)->save();
        cache()->forget('categories');
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'New Data Added Successfully.';
        return redirect()->back()->with('success', $msg);
        // return response()->json($msg);
        //--- Redirect Section Ends
    }

   

    //*** GET Request
    public function edit($id)
    {
        $data = Category::findOrFail($id);
        return view('admin.category.edit',compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
        	'photo' => 'mimes:jpeg,jpg,png,svg',
        	'slug' => 'unique:categories,slug,'.$id.'|regex:/^[a-zA-Z0-9\s-]+$/'
        		 ];
        $customs = [
        	'photo.mimes' => 'Icon Type is Invalid.',
        	'slug.unique' => 'This slug has already been taken.',
            'slug.regex' => 'Slug Must Not Have Any Special Characters.'
        		   ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Category::findOrFail($id);
        $input = $request->all();
            if ($file = $request->file('photo'))
            {
                /* $name = time().str_replace(' ', '', $file->getClientOriginalName());
                $file->move('assets/images/categories',$name);
                if($data->photo != null)
                {
                    if (file_exists(public_path().'/assets/images/categories/'.$data->photo)) {
                        unlink(public_path().'/assets/images/categories/'.$data->photo);
                    }
                }
                $input['photo'] = $name; */

                $this->destination  = categoryImageStorageDestination_hd();//'public/categories';   //its mandatory
                $this->imageWidth   = categoryImageWidth_hd();//500; //its mandatory
                $this->imageHeight  = categoryImageHeight_hd();//300; //its nullable
                $this->file         = $request->file('photo');  //its mandatory
                $this->imageNameFromDb = $data->photo;  //its mandatory
                $input['photo']     = $this->updateImage(); 
            }

            if ($request->is_featured == ""){
                $input['is_featured'] = 0;
            }
            else {
                    $input['is_featured'] = 1;
                    //--- Validation Section
                    $rules = [
                        'image' => 'mimes:jpeg,jpg,png,svg'
                            ];
                    $customs = [
                        'image.required' => 'Feature Image is required.'
                            ];
                    $validator = Validator::make($request->all(), $rules, $customs);

                    if ($validator->fails()) {
                    return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
                    }
                    //--- Validation Section Ends
                    if ($file = $request->file('image'))
                    {
                       /* $name = time().str_replace(' ', '', $file->getClientOriginalName());
                       $file->move('assets/images/categories',$name);
                       $input['image'] = $name; */

                        $this->destination  = categoryImageStorageDestination_hd();//'public/categories';   //its mandatory
                        $this->imageWidth   = categoryImageWidth_hd();//500; //its mandatory
                        $this->imageHeight  = categoryImageHeight_hd();//300; //its nullable
                        $this->file         = $request->file('image');  //its mandatory
                        $this->imageNameFromDb = $data->image;  //its mandatory
                        $input['photo']     = $this->updateImage(); 
                    }
            }

        $data->update($input);
        cache()->forget('categories');
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'Data Updated Successfully.';
        return redirect()->back()->with('success', $msg);
        // return response()->json($msg);
        //--- Redirect Section Ends
    }

      //*** GET Request Status
      public function status($id1,$id2)
      {
          $data = Category::findOrFail($id1);
          $data->status = $id2;
          $data->update();
          cache()->forget('categories');
      }


    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Category::findOrFail($id);

        if($data->attributes->count() > 0)
        {
            $msg = 'Remove the Attributes first !';
            return response()->json(['status' => 'error', 'mgs' => $msg]);
        }
        if($data->subs->count()>0)
        {
            $msg = 'Remove the subcategories first !';
            return response()->json(['status' => 'error', 'mgs' => $msg]);
        }
        if($data->products->count()>0)
        {
            $msg = 'Remove the products first !';
            return response()->json(['status' => 'error', 'mgs' => $msg]);
        }

        //If Photo Doesn't Exist
        // if($data->photo == null)
        // {
        //     $data->delete();
        //     $msg = 'Data Deleted Successfully.';
        //     return response()->json($msg);
        // }
        //If Photo Exist
        /* if (file_exists(public_path().'/assets/images/categories/'.$data->photo)) {
            unlink(public_path().'/assets/images/categories/'.$data->photo);
        }
        if (file_exists(public_path().'/assets/images/categories/'.$data->image)) {
            unlink(public_path().'/assets/images/categories/'.$data->image);
        } */

        // delete the image 
        // $this->destination  = 'public/categories';   //its mandatory
        // $this->imageNameFromDb = $data->photo ;   //its mandatory
        // $this->imageDelete();

        // $this->destination  = 'public/categories';          //its mandatory
        // $this->imageNameFromDb = $data->image;    //its mandatory
        // $this->imageDelete();
        // delete the image 

        $data->deleted_at = now();
        $data->save();
        cache()->forget('categories');
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }

    public function restore($id)
    {
        $data = Category::findOrFail($id);
        $data->deleted_at = null;
        $data->save();
        $msg = 'Data Restored Successfully.';
        return response()->json($msg);
    }


    public function slug(Request $request)
    {
        $slug =  Str::slug($request->name,'-');
        return response()->json(['slug' => $slug]);

    }


}
