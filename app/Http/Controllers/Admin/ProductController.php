<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Brand;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Exceptions\Handler;
use App\Models\Subcategory;
use App\Traits\FileStorage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Childcategory;
use App\Models\AttributeOption;
use App\Models\VendorInformation;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProductUpdateCsvFileProcess;
use App\Jobs\ProductUploadCsvFileProcess;

class ProductController extends Controller
{
    use FileStorage;


    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //have to complete
    public function productList(Request $request)
    {
        if($request->ajax())
        {
            $status         = $request->status ?? NULL;
            $pagination     = $request->pagination ?? 50;
            $search         = $request->search ?? NULL;
            $category_id    = $request->category_id ?? NULL;
            $date_from = Carbon::parse($request->input('start_date'));
            $date_to = Carbon::parse($request->input('end_date') ?? date("Y-m-d h:i:s",strtotime(date("Y-m-d h:i:s")."-21 day")));
            
            $query= Product::query();
                if($search)
                {   
                    $query->where('name','like','%'.$search.'%')
                    ->orWhere('sku','like','%'.$search.'%');
                }
                if($category_id)
                {   
                    $query->where('category_id',$category_id);
                }
                /* if($date_from)
                {
                    $query->whereDate('created_at', '<=', $date_from)
                    ->whereDate('created_at', '>=', $date_to);
                } */
            $data['products'] = $query->orderBy('id', 'desc')
                                ->paginate($pagination); 
            $data['page_no'] = $request->page ?? 1;
            $html = view('admin.product.ajax-response.list',$data)->render();
            return response([
                'status' => true,
                'data'  => $html
            ]);
        }
    }


    public function promotionList(Request $request)
    {
        if($request->ajax())
        {
            $pagination     = $request->pagination ?? 50;
            $search         = $request->search ?? NULL;
            $category_id    = $request->category_id ?? NULL;
            
            $query= Product::orWhere('just_in', '=', 1)
            ->orWhere('weekly_deals', '=', 1)
            ->orWhere('trending_products', '=', 1)
            ->orWhere('top_kids_baby_products', '=', 1)
            ->orWhere('featured_phones_accessories', '=', 1)
            ->orWhere('the_beauty_editors_pick', '=', 1);
                if($search)
                {   
                    $query->where('name','like','%'.$search.'%')
                    ->orWhere('sku','like','%'.$search.'%');
                }
                if($category_id)
                {   
                    $query->where('category_id',$category_id);
                }
            $data['products'] =    $query->orderBy('id', 'desc')
                            ->paginate($pagination); 
            $data['page_no'] = $request->page ?? 1;
            $html = view('admin.product.ajax-response.promotion-list',$data)->render();
            return response([
                'status' => true,
                'data'  => $html
            ]);
        }
    }

    //*** JSON Request
    public function datatables($search = NULL)
    {
        $product = Product::query();
        $product->orderBy('id','desc');
        $search = trim($search);
        if($search != NULL || $search != "")
        {
            $product->where('name','like','%'.$search.'%')
            ->orWhere('sku','like','%'.$search.'%');
            $product->where(function ($q){
                $q->where('product_type','=','normal')
                ->orWhere('product_type','=','variant');
            });
        }else{
            $product->where(function ($q){
                $q->where('product_type','=','normal')
                ->orWhere('product_type','=','variant');
            });
        }
        $product->where(function($q){
            if (! Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) {
                $q->whereNull('deleted_at');
            }
        });
        $datas = $product->select('id','name', 'type', 'sku','user_id', 'price', 'stock','pub_status', 'status', 'is_catalog', 'deleted_at');
       
        /* 
            $datas = Product::where('product_type','=','normal')
            ->orWhere('product_type','=','variant')
            ->where(function($q){
                if (! Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) {
                    $q->whereNull('deleted_at');
                }
            })
            ->orderBy('id','desc')
            ->select('id','name', 'type', 'sku','user_id', 'price', 'stock','pub_status', 'status', 'is_catalog', 'deleted_at');
        */
        
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
        ->editColumn('name', function(Product $data) {
            $name =  mb_strlen($data->name,'UTF-8') > 50 ? mb_substr($data->name,0,50,'UTF-8').'...' : $data->name;
            $name =  '<span class="productShortDetail" data-id="'.$data->id.'">'.$name.'</span>';
            $id = '<small>'.__("ID").': <a href="#" target="_blank">'.sprintf("%'.08d",$data->id).'</a></small>';

            $id3 = $data->type == 'Physical' ?'<small class="ml-2"> '.__("SKU").': <a href="#" target="_blank">'.$data->sku.'</a>' : '';

            return  $name.'<br>'.$id.$id3.$data->checkVendor();
        })
        ->editColumn('category', function(Product $data) {
            //return $data->categories ? $data->categories->name : "No Category";
            return $data->categories($data->id);
            //return '<span class="productShortDetail" data-id="'.$data->id.'">'.$data->categories($data->id).'</span>';
        })
        ->editColumn('price', function(Product $data) {
            $price = round($data->price , 2);
            $price = '€'.$price;
            return $price;
        })
        ->editColumn('stock', function(Product $data) {
            $stck = (string)$data->stock;
            if($stck == "0")
            return "Out Of Stock";
            elseif($stck == null)
            return "Unlimited";
            else
            return $data->stock;
        })
        ->addColumn('status', function(Product $data) {
            $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
            $s = $data->status == 1 ? 'selected' : '';
            $ns = $data->status == 0 ? 'selected' : '';
            return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.product.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><<option data-val="0" value="'. route('admin.product.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option>/select></div>';
        })
        ->addColumn('action', function(Product $data) {

            if(Auth::guard('admin')->user()->role->permissionCheck('products|edit_catalog'))
            {
                $catalog = $data->type == 'Physical' ? ($data->is_catalog == 1 ? '<a href="javascript:;" data-href="' . route('admin.prod.catalog',['id1' => $data->id, 'id2' => 0]) . '" data-toggle="modal" data-target="#catalog-modal" class="delete"><i class="fas fa-trash-alt"></i> Remove Catalog</a>' : '<a href="javascript:;" data-href="'. route('admin.prod.catalog',['id1' => $data->id, 'id2' => 1]) .'" data-toggle="modal" data-target="#catalog-modal"> <i class="fas fa-plus"></i> Add To Catalog</a>') : '';
            }

            $action = '<div class="godropdown">
            <button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button>
            <div class="action-list">';

            if(Auth::guard('admin')->user()->role->permissionCheck('products|edit'))
            {
                $action .= '<a href="' . route('admin.product.edit',$data->id) . '"> <i class="fas fa-edit"></i> Edit</a>';
                $action .= '<a class="categoryEdit" data-id="'.$data->id.'"> <i class="fas fa-edit"></i>Category Edit</a>';
                //$action .= '<a data-href="' . route('admin.product.feature',$data->id) . '" class="feature" data-toggle="modal" data-target="#modal2"> <i class="fas fa-star"></i> Highlight</a>';
            }
            if(! $data->deleted_at)
            {
                if(Auth::guard('admin')->user()->role->permissionCheck('products|delete'))
                {
                    $action .= '<a href="javascript:;" data-href="' .route('admin.product.delete',$data->id) . '" data-toggle="modal" data-target="#delete_modal" class="delete"><i class="fas fa-trash-alt"></i> Delete</a>';
                }
            }
            else
            {
                if (Auth::guard('admin')->user()->role->permissionCheck('recover_delete'))
                {
                    $action .= '<a href="javascript:;" data-href="' . route('admin.prod.restore', $data->id) . '" data-toggle="modal" data-target="#restore_modal" title="restore"><i class="fas fa-reply-all"></i> Restore</a>';
                }
            }
            
            //unpublishing product 
            if($data->pub_status == 1 && $data->status == 1 && !$data->deleted_at)
            {
                if(Auth::guard('admin')->user()->role->permissionCheck('products|delete'))
                {
                    $action .= '<a href="javascript:;" data-href="' .route('admin.published.product.unpublishing',$data->id) . '" data-toggle="modal" data-target="#unpublished_modal" class="delete"><i class="fas fa-reply-all"></i> Un-published</a>';
                }
            }
            else if($data->pub_status == 0 && $data->status == 0 && !$data->deleted_at)
            {
                if (Auth::guard('admin')->user()->role->permissionCheck('recover_delete'))
                {
                    $action .= '<a href="javascript:;" data-href="' . route('admin.unpublished.product.unpublishing', $data->id) . '" data-toggle="modal" data-target="#publishing_modal" title="published"><i class="fas fa-forward"></i> Published</a>';
                }
            }
            //unpublishing product
            
            //promotional level
            $action .= '<a href="javascript:;" data-href="' . route('admin.main.order.show.delivery.status.details') . '" class="promotion_level" data-id="'.$data->id.'"><i class="fas fa-reply-all"></i> Promotional Level</a>'; 
            //promotional level

            //$action .= '<a href="javascript" class="set-gallery" data-toggle="modal" data-target="#setgallery"><input type="hidden" value="'.$data->id.'"><i class="fas fa-eye"></i> View Gallery</a>';
            //$action .= $catalog ?? '';
            //$action .= '</div></div>';

            return $action;
        })
        ->rawColumns(['name', 'status', 'action'])
        ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** JSON Request
    public function deactivedatatables()
    {
        $datas = Product::where('status', '=', 0)->orderBy('id', 'desc');

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
        ->editColumn('name', function (Product $data) {
            $name =  mb_strlen($data->name, 'UTF-8') > 50 ? mb_substr($data->name, 0, 50, 'UTF-8') . '...' : $data->name;

            $id = '<small>' . __("ID") . ': <a href="#" target="_blank">' . sprintf("%'.08d", $data->id) . '</a></small>';

            $id3 = $data->type == 'Physical' ? '<small class="ml-2"> ' . __("SKU") . ': <a href="#" target="_blank">' . $data->sku . '</a>' : '';

            return  $name . '<br>' . $id . $id3 . $data->checkVendor();
        })
            ->editColumn('price', function (Product $data) {
                $price = round($data->price, 2);
                $price = '€'. $price;
                return  $price;
            })
            ->editColumn('stock', function (Product $data) {
                $stck = (string)$data->stock;
                if ($stck == "0")
                    return "Out Of Stock";
                elseif ($stck == null)
                    return "Unlimited";
                else
                    return $data->stock;
            })
            ->addColumn('status', function (Product $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks ' . $class . '"><option data-val="1" value="' . route('admin.product.status', ['id1' => $data->id, 'id2' => 1]) . '" ' . $s . '>Activated</option><<option data-val="0" value="' . route('admin.product.status', ['id1' => $data->id, 'id2' => 0]) . '" ' . $ns . '>Deactivated</option>/select></div>';
            })
            ->addColumn('action', function (Product $data) {
                $catalog = $data->type == 'Physical' ? ($data->is_catalog == 1 ? '<a href="javascript:;" data-href="' . route('admin.prod.catalog', ['id1' => $data->id, 'id2' => 0]) . '" data-toggle="modal" data-target="#catalog-modal" class="delete"><i class="fas fa-trash-alt"></i> Remove Catalog</a>' : '<a href="javascript:;" data-href="' . route('admin.prod.catalog', ['id1' => $data->id, 'id2' => 1]) . '" data-toggle="modal" data-target="#catalog-modal"> <i class="fas fa-plus"></i> Add To Catalog</a>') : '';

                $action = '<div class="godropdown">
                <button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button>
                <div class="action-list">';

                if(Auth::guard('admin')->user()->role->permissionCheck('products|edit'))
                {
                    $action .= '<a href="' . route('admin.product.edit',$data->id) . '"> <i class="fas fa-edit"></i> Edit</a>';
                }
                if(! $data->deleted_at)
                {
                    if(Auth::guard('admin')->user()->role->permissionCheck('products|delete'))
                    {
                        $action .= '<a href="javascript:;" data-href="' .route('admin.product.delete',$data->id) . '" data-toggle="modal" data-target="#delete_modal" class="delete"><i class="fas fa-trash-alt"></i> Delete</a>';
                    }
                }
                else
                {
                    if (Auth::guard('admin')->user()->role->permissionCheck('recover_delete'))
                    {
                        $action .= '<a href="javascript:;" data-href="' . route('admin.prod.restore', $data->id) . '" data-toggle="modal" data-target="#restore_modal" title="restore"><i class="fas fa-reply-all"></i> Restore</a>';
                    }
                }

                $action .= '<a href="javascript" class="set-gallery" data-toggle="modal" data-target="#setgallery"><input type="hidden" value="'.$data->id.'"><i class="fas fa-eye"></i> View Gallery</a>';
                $action .= $catalog;
                $action .= '<a data-href="' . route('admin.product.feature',$data->id) . '" class="feature" data-toggle="modal" data-target="#modal2"> <i class="fas fa-star"></i> Highlight</a>';
                $action .= '</div></div>';

                return $action;
            })
            ->rawColumns(['name', 'status', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** JSON Request
    public function promotiondatatables()
    {
        $datas = Product::orWhere('just_in', '=', 1)
        ->orWhere('weekly_deals', '=', 1)
        ->orWhere('trending_products', '=', 1)
        ->orWhere('top_kids_baby_products', '=', 1)
        ->orWhere('featured_phones_accessories', '=', 1)
        ->orWhere('the_beauty_editors_pick', '=', 1)
        ->orderBy('id', 'desc');

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
        ->editColumn('name', function (Product $data) {
            $name =  mb_strlen($data->name, 'UTF-8') > 50 ? mb_substr($data->name, 0, 50, 'UTF-8') . '...' : $data->name ;

            $id = '<small>' . __("ID") . ': <a href="#" target="_blank">' . sprintf("%'.08d", $data->id) . '</a></small>';

            $id3 = $data->type == 'Physical' ? '<small class="ml-2"> ' . __("SKU") . ': <a href="#" target="_blank">' . $data->sku . '</a>' : '';

            return  '<span class="productShortDetail" data-id="'.$data->id.'">'.$name . '</span> <br>' . $id . $id3 . $data->checkVendor();
        })
            ->addColumn('just_in', function (Product $data) {
                $class = $data->just_in == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->just_in == 1 ? 'selected' : '';
                $ns = $data->just_in == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks ' . $class . '"><option data-val="1" value="' . route('admin.product.promotion_status', ['id1' => $data->id, 'id2' => 'just_in', 'id3' => 1]) . '" ' . $s . '>Activated</option><<option data-val="0" value="' . route('admin.product.promotion_status', ['id1' => $data->id, 'id2' => 'just_in', 'id3' => 0]) . '" ' . $ns . '>Deactivated</option>/select></div>';
            })
            ->addColumn('weekly_deals', function (Product $data) {
                $class = $data->weekly_deals == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->weekly_deals == 1 ? 'selected' : '';
                $ns = $data->weekly_deals == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks ' . $class . '"><option data-val="1" value="' . route('admin.product.promotion_status', ['id1' => $data->id, 'id2' => 'weekly_deals', 'id3' => 1]) . '" ' . $s . '>Activated</option><<option data-val="0" value="' . route('admin.product.promotion_status', ['id1' => $data->id, 'id2' => 'weekly_deals', 'id3' => 0]) . '" ' . $ns . '>Deactivated</option>/select></div>';
            })
            ->addColumn('trending_products', function (Product $data) {
                $class = $data->trending_products == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->trending_products == 1 ? 'selected' : '';
                $ns = $data->trending_products == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks ' . $class . '"><option data-val="1" value="' . route('admin.product.promotion_status', ['id1' => $data->id, 'id2' => 'trending_products', 'id3' => 1]) . '" ' . $s . '>Activated</option><<option data-val="0" value="' . route('admin.product.promotion_status', ['id1' => $data->id, 'id2' => 'trending_products', 'id3' => 0]) . '" ' . $ns . '>Deactivated</option>/select></div>';
            })
            ->addColumn('top_kids_baby_products', function (Product $data) {
                $class = $data->top_kids_baby_products == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->top_kids_baby_products == 1 ? 'selected' : '';
                $ns = $data->top_kids_baby_products == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks ' . $class . '"><option data-val="1" value="' . route('admin.product.promotion_status', ['id1' => $data->id, 'id2' => 'top_kids_baby_products', 'id3' => 1]) . '" ' . $s . '>Activated</option><<option data-val="0" value="' . route('admin.product.promotion_status', ['id1' => $data->id, 'id2' => 'top_kids_baby_products', 'id3' => 0]) . '" ' . $ns . '>Deactivated</option>/select></div>';
            })
            ->addColumn('featured_phones_accessories', function (Product $data) {
                $class = $data->featured_phones_accessories == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->featured_phones_accessories == 1 ? 'selected' : '';
                $ns = $data->featured_phones_accessories == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks ' . $class . '"><option data-val="1" value="' . route('admin.product.promotion_status', ['id1' => $data->id, 'id2' => 'featured_phones_accessories', 'id3' => 1]) . '" ' . $s . '>Activated</option><<option data-val="0" value="' . route('admin.product.promotion_status', ['id1' => $data->id, 'id2' => 'featured_phones_accessories', 'id3' => 0]) . '" ' . $ns . '>Deactivated</option>/select></div>';
            })
            ->addColumn('the_beauty_editors_pick', function (Product $data) {
                $class = $data->the_beauty_editors_pick == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->the_beauty_editors_pick == 1 ? 'selected' : '';
                $ns = $data->the_beauty_editors_pick == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks ' . $class . '"><option data-val="1" value="' . route('admin.product.promotion_status', ['id1' => $data->id, 'id2' => 'the_beauty_editors_pick', 'id3' => 1]) . '" ' . $s . '>Activated</option><<option data-val="0" value="' . route('admin.product.status', ['id1' => $data->id, 'id2' => 'the_beauty_editors_pick', 'id3' => 0]) . '" ' . $ns . '>Deactivated</option>/select></div>';
            })
            
            ->rawColumns(['name', 'just_in','weekly_deals', 'trending_products', 'top_kids_baby_products','featured_phones_accessories','the_beauty_editors_pick'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** product short description
    public function productDetail(Request $request)
    {
        $data['product']    = Product::findOrFail($request->id); 
        $data['categories'] = Category::whereNull('deleted_at')->get();
        $data['subCategories'] = Subcategory::where('category_id',$data['product']->category_id)->whereNull('deleted_at')->get();
        if($data['product'])
        {
            $view =  view('admin.product.product-detail.detail',$data)->render();
            return response()->json([
                'status' => true,
                'data' => $view
            ]);
        }
    }//*** product short description

 

    //*** Promotion Level
    public function promotionLevel(Request $request)
    {
        $data['product'] = Product::findOrFail($request->id); 
        if($data['product'])
        {
            $view =  view('admin.product.promotion-level',$data)->render();
            return response()->json([
                'status' => true,
                'data' => $view
            ]);
        }
    }//*** Promotion Level

    //*** Promotion Level Status Change
    public function promotionStatus(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $flag = 0;
        if($request->value == 'just_in' ){
            if($product->just_in == 0){
                $product->just_in = 1;
                $flag = 1;
            }
            else{
                $product->just_in = 0;
                $flag = 0;
            }
        }
        elseif($request->value == 'weekly_deals' ){
            if($product->weekly_deals == 0){
                $product->weekly_deals = 1;
                $flag = 1;
            }
            else{
                $product->weekly_deals = 0;
                $flag = 0;
            }
        }
        elseif($request->value == 'trending_products' ){
            if($product->trending_products == 0){
                $product->trending_products = 1;
                $flag = 1;
            }
            else{
                $product->trending_products = 0;
                $flag = 0;
            }
        }
        elseif($request->value == 'top_kids_baby_products' ){
            if($product->top_kids_baby_products == 0){
                $product->top_kids_baby_products = 1;
                $flag = 1;
            }
            else{
                $product->top_kids_baby_products = 0;
                $flag = 0;
            }
        }
        elseif($request->value == 'featured_phones_accessories' ){
            if($product->featured_phones_accessories == 0){
                $product->featured_phones_accessories = 1;
                $flag = 1;
            }
            else{
                $product->featured_phones_accessories = 0;
                $flag = 0;
            }
        }
        elseif($request->value == 'the_beauty_editors_pick' ){
            if($product->the_beauty_editors_pick == 0){
                $product->the_beauty_editors_pick = 1;
                $flag = 1;
            }
            else{
                $product->the_beauty_editors_pick = 0;
                $flag = 0;
            }
        }
        if($product->save())
        {
            return response()->json([
                'status' => true,
                'data' => $flag,
                'value' => $request->value
            ]);
        }
    }//*** Promotion Level Status Change
    

    //*** product Category edit
    public function categoryEdit(Request $request)
    {
        $data['product']    = Product::findOrFail($request->id); 
        $data['categories'] = Category::whereNull('deleted_at')->get();
        $data['subCategories'] = Subcategory::where('category_id',$data['product']->category_id)->whereNull('deleted_at')->get();
        if($data['product'])
        {
            $view =  view('admin.product.category-update.detail',$data)->render();
            return response()->json([
                'status' => true,
                'data' => $view
            ]);
        }
    }//*** product Category edit
   
    //*** product Category update
    public function categoryUpdate(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $product->category_id   = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->save();

        if($product)
        {
            return response()->json([
                'status' => true,
                'cat_name'      =>  Category::findOrFail($request->category_id)->name,
                'sub_cat_name'  =>  Subcategory::findOrFail($request->subcategory_id)->name
            ]);
        }else{
            return response()->json([
                'status' => false
            ]);
        }
    }//*** product Category update


    //*** subCategoryByCatId
    public function subCategoryByCatId(Request $request)
    {
        $subcats = Subcategory::where('category_id',$request->catid)->whereNull('deleted_at')->get();
        $html = "";
        if($subcats)
        {
            foreach($subcats as $subcat)
            {
                $html .= '<option value="'.$subcat->id.'" >'.$subcat->name . '</option>';
            }
        } 
        return response()->json([
            'status' => true,
            'html' => $html
        ]);
   
    }//*** subCategoryByCatId


    //*** JSON Request
    public function catalogdatatables()
    {
         $datas = Product::where('is_catalog','=',1)->orderBy('id','desc')->get();

         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('name', function(Product $data) {
                                $name = mb_strlen(strip_tags($data->name),'utf-8') > 50 ? mb_substr(strip_tags($data->name),0,50,'utf-8').'...' : strip_tags($data->name);
                                $id = '<small>ID: <a href="#" target="_blank">'.sprintf("%'.08d",$data->id).'</a></small>';

                                $id3 = $data->type == 'Physical' ?'<small class="ml-2"> SKU: <a href="#" target="_blank">'.$data->sku.'</a>' : '';

                                return  $name.'<br>'.$id.$id3;
                            })
                            ->editColumn('price', function(Product $data) {
                                $price = round($data->price , 2);
                                $price = '€'.$price ;
                                return  $price;
                            })
                            ->editColumn('stock', function(Product $data) {
                                $stck = (string)$data->stock;
                                if($stck == "0")
                                return "Out Of Stock";
                                elseif($stck == null)
                                return "Unlimited";
                                else
                                return $data->stock;
                            })
                            ->addColumn('status', function(Product $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.product.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><<option data-val="0" value="'. route('admin.product.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option>/select></div>';
                            })
                            ->addColumn('action', function(Product $data) {

                                $action = '<div class="godropdown"><button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button>
                                <div class="action-list">';

                                // can edit
                                if (Auth::guard('admin')->user()->role->permissionCheck('products|edit'))
                                {
                                    $action .= '<a href="' . route('admin.product.edit',$data->id) . '"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="javascript" class="set-gallery" data-toggle="modal" data-target="#setgallery"><input type="hidden" value="'.$data->id.'"><i class="fas fa-eye"></i> View Gallery</a>
                                    <a data-href="' . route('admin.product.feature',$data->id) . '" class="feature" data-toggle="modal" data-target="#modal2"> <i class="fas fa-star"></i> Highlight</a>';
                                }

                                if(Auth::guard('admin')->user()->role->permissionCheck('products|delete'))
                                {
                                    $action .= '<a href="javascript:;" data-href="' . route('admin.prod.catalog',['id1' => $data->id, 'id2' => 0]) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> Remove Catalog</a>';
                                }

                                $action .= '</div></div>';

                                return $action;
                            })
                            ->rawColumns(['name', 'status', 'action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        $data['categories'] = Category::get();
        return view('admin.product.index');
    }

    //*** GET Request
    public function deactive()
    {
        return view('admin.product.deactive');
    }


    //*** GET Request
    public function promotion()
    {
        return view('admin.product.promotion');
    }

    //*** GET Request
    public function catalogs()
    {
        return view('admin.product.catalog');
    }

    //*** GET Request
    public function types()
    {
        return redirect()->route('admin.product.physical.create');
        //return view('admin.product.types');
    }

    //*** GET Request
    public function createPhysical()
    {
        $data['brands'] = Brand::whereNull('deleted_at')->get();
        $cats           = Category::all();
        $merchants      = VendorInformation::select('user_id','shop_name')->get();
        return view('admin.product.create.physical',compact('cats','merchants'),$data);
    }

    //*** GET Request
    public function createDigital()
    {
        $cats = Category::all();
        return view('admin.product.create.digital',compact('cats'));
    }

    //*** GET Request
    public function createLicense()
    {
        $cats = Category::all();
        return view('admin.product.create.license',compact('cats'));
    }

    //*** GET Request
    public function status($id1,$id2)
    {
        $data = Product::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    //*** GET Request
    public function promotionchange($id1,$id2,$id3)
    {
        $product = Product::findOrFail($id1);
        if($id2 == 'just_in' ){
            $product->just_in = $id3;
        }
        elseif($id2 == 'weekly_deals' ){
            $product->weekly_deals = $id3;
        }
        elseif($id2 == 'trending_products' ){
            $product->trending_products = $id3;
        }
        elseif($id2 == 'top_kids_baby_products' ){
            $product->top_kids_baby_products = $id3;
        }
        elseif($id2 == 'featured_phones_accessories' ){
            $product->featured_phones_accessories = $id3;
        }
        elseif($id2 == 'the_beauty_editors_pick' ){
            $product->the_beauty_editors_pick = $id3;
        }
        $product->update();
    }

    //*** GET Request
    public function catalog($id1,$id2)
    {
        $data = Product::findOrFail($id1);
        $data->is_catalog = $id2;
        $data->update();
        if($id2 == 1) {
            $msg = "Product added to catalog successfully.";
        }
        else {
            $msg = "Product removed from catalog successfully.";
        }

        return response()->json($msg);

    }

    //*** POST Request
    public function uploadUpdate(Request $request,$id)
    {
        //--- Validation Section
        $rules = [
          'image' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = Product::findOrFail($id);

        //--- Validation Section Ends
        $image = $request->image;
        list($type, $image) = explode(';', $image);
        list(, $image)      = explode(',', $image);
        $image = base64_decode($image);
        $image_name = Str::random(10).'.png';
        $path = 'assets/images/products/'.$image_name;
        file_put_contents($path, $image);
                if($data->photo != null)
                {
                    if (file_exists(public_path().'/assets/images/products/'.$data->photo)) {
                        unlink(public_path().'/assets/images/products/'.$data->photo);
                    }
                }
                        $input['photo'] = $image_name;
         $data->update($input);
                if($data->thumbnail != null)
                {
                    if (file_exists(public_path().'/assets/images/thumbnails/'.$data->thumbnail)) {
                        unlink(public_path().'/assets/images/thumbnails/'.$data->thumbnail);
                    }
                }

        $img = Image::make(public_path().'/assets/images/products/'.$data->photo)->resize(285, 285);
        $thumbnail = Str::random(10).'.jpg';
        $img->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
        $data->thumbnail  = $thumbnail;
        $data->update();
        return response()->json(['status'=>true,'file_name' => $image_name]);
    }


    /**CKEditor upload image */
	public function productCreateCkEditorUploadImage(Request $request)
    {
        if($request->hasFile('upload')) {
            //get filename with extension
            $filenamewithextension = $request->file('upload')->getClientOriginalName();

            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

            //get file extension
            $extension = $request->file('upload')->getClientOriginalExtension();

            //filename to store
            $filenametostore = $filename.'_'.time().'.'.$extension;

            //Upload File
            $request->file('upload')->storeAs('public/ckeditor', $filenametostore);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('storage/ckeditor/'.$filenametostore);
            $msg = 'Image successfully uploaded';
            $re = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            // Render HTML output
            @header('Content-type: text/html; charset=utf-8');
            echo $re;
        }

    }
    /**CKEditor upload image */


    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            'photo'      => 'required',
            'file'       => 'mimes:zip'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Product;
        $input = $request->all();

        // Check File
        if ($file = $request->file('file')) {
            $name = time() . str_replace(' ', '', $file->getClientOriginalName());
            $file->move('assets/files', $name);
            $input['file'] = $name;
        }
        /* $image = $request->photo;
        list($type, $image) = explode(';', $image);
        list(, $image)      = explode(',', $image);
        $image = base64_decode($image);
        $image_name = Str::random(10).'.png';
        $path = 'assets/images/products/'.$image_name;
        file_put_contents($path, $image);
        $input['photo'] = $image_name; */

        /**For Base64 image upload */
        $this->destination  = productImageStorageDestination_hd();//'public/products';
        $this->imageWidth   = productImageWidth_hd();//400;  //its mandatory
        $this->imageHeight  = productImageHeight_hd();//NULL;  //its nullable
        $this->file         = $request->photo;
        $input['photo'] =   $this->storeBase64Image();
        /**For Base64 image upload */

        // Check Physical
        if ($request->type == "Physical") {
            //--- Validation Section
            $rules = [
                'sku'      => 'min:8|unique:products',
                'user_id'  => 'required',
                'price'  => 'numeric|required',
                'previous_price'  => 'numeric|required',
                'product_commission'  => 'numeric|required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            //--- Validation Section Ends

            //Product commission
            if ($request->product_commission > $request->price && $request->product_commission > $request->previous_price) {

                return response()->json(array('errors' => [0 => 'Product commission should be less than product current price and previous price']));
            } else {
                //$input['commission'] = $request->product_commission;
                $input['commission'] = $request->product_commission;
            }

            // Conert Price According to Currency
            $input['price'] = $input['price'];
            $input['shipping_cost'] = $request->shipping_cost;
            $input['previous_price'] = $input['previous_price'];

            // Check Condition
            if ($request->product_condition_check == "") {
                $input['product_condition'] = 0;
            }

            // Check Shipping Time
            if ($request->shipping_time_check == "") {
                $input['ship'] = null;
            }

            // Check Size
            if (empty($request->size_check)) {
                $input['size'] = null;
                $input['size_qty'] = null;
                $input['size_price'] = null;
            } else {
                if (in_array(null, $request->size) || in_array(null, $request->size_qty)) {
                    $input['size'] = null;
                    $input['size_qty'] = null;
                    $input['size_price'] = null;
                } else {
                    if (in_array(0, $input['size_qty'])) {
                        return response()->json(array('errors' => [0 => 'Size Qty can not be 0.']));
                    }

                    $input['size'] = implode(',', $request->size);
                    $input['size_qty'] = implode(',', $request->size_qty);
                    $size_prices = $request->size_price;
                    $s_price = array();
                    foreach ($size_prices as $key => $sPrice) {
                        $s_price[$key] = $sPrice;
                    }

                    $input['size_price'] = implode(',', $s_price);
                }
            }

            // Check Whole Sale
            if (empty($request->whole_check)) {
                $input['whole_sell_qty'] = null;
                $input['whole_sell_discount'] = null;
            } else {
                if (in_array(null, $request->whole_sell_qty) || in_array(null, $request->whole_sell_discount)) {
                    $input['whole_sell_qty'] = null;
                    $input['whole_sell_discount'] = null;
                } else {
                    $input['whole_sell_qty'] = implode(',', $request->whole_sell_qty);
                    $input['whole_sell_discount'] = implode(',', $request->whole_sell_discount);
                }
            }

            // Check Color
            $input['color'] = empty($request->color_check) ? null : implode(',', $request->color);

            // Vendor name
            $input['user_id'] = empty($request->user_id) ? null : $request->user_id;

            //Check Measurement
            if ($request->measure == "") {
                $input['measure'] = null;
            } else {
                $input['measure'] = $request->measure;
            }
        }

        // Check Seo
        if (empty($request->seo_check)) {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;
        } else {
            if (!empty($request->meta_tag)) {
                $input['meta_tag'] = implode(',', $request->meta_tag);
            }
        }

        // Check License
        if ($request->type == "License") {
            if (in_array(null, $request->license) || in_array(null, $request->license_qty)) {
                $input['license'] = null;
                $input['license_qty'] = null;
            } else {
                $input['license'] = implode(',,', $request->license);
                $input['license_qty'] = implode(',', $request->license_qty);
            }
        }

        // Check Features
        if (in_array(null, $request->features) || in_array(null, $request->colors)) {
            $input['features'] = null;
            $input['colors'] = null;
        } else {
            $input['features'] = implode(',', str_replace(',', ' ', $request->features));
            $input['colors'] = implode(',', str_replace(',', ' ', $request->colors));
        }

        // product tags
        if (!empty($request->tags)) {
            $input['tags'] = implode(',', $request->tags);

            // save tag if not exist
            foreach($request->tags as $tag)
            {
                if (! DB::table('tags')->where('name', trim($tag))->first())
                {
                    DB::table('tags')->insert([
                        'name' => trim($tag)
                    ]);
                }
            }
        }



        // store filtering attributes for physical product
        $attrArr = [];
        if (!empty($request->category_id)) {
            $catAttrs = Attribute::where('attributable_id', $request->category_id)->where('attributable_type', 'App\Models\Category')->get();
            if (!empty($catAttrs)) {
                foreach ($catAttrs as $key => $catAttr) {
                    $in_name = $catAttr->input_name;
                    if ($request->has("$in_name")) {
                        $attrArr["$in_name"]["values"] = $request["$in_name"];
                        $attrArr["$in_name"]["prices"] = $request["$in_name" . "_price"];
                        if ($catAttr->details_status) {
                            $attrArr["$in_name"]["details_status"] = 1;
                        } else {
                            $attrArr["$in_name"]["details_status"] = 0;
                        }
                    }
                }
            }
        }

        if (!empty($request->subcategory_id)) {
            $subAttrs = Attribute::where('attributable_id', $request->subcategory_id)->where('attributable_type', 'App\Models\Subcategory')->get();
            if (!empty($subAttrs)) {
                foreach ($subAttrs as $key => $subAttr) {
                    $in_name = $subAttr->input_name;
                    if ($request->has("$in_name")) {
                        $attrArr["$in_name"]["values"] = $request["$in_name"];
                        $attrArr["$in_name"]["prices"] = $request["$in_name" . "_price"];
                        if ($subAttr->details_status) {
                            $attrArr["$in_name"]["details_status"] = 1;
                        } else {
                            $attrArr["$in_name"]["details_status"] = 0;
                        }
                    }
                }
            }
        }
        if (!empty($request->childcategory_id)) {
            $childAttrs = Attribute::where('attributable_id', $request->childcategory_id)->where('attributable_type', 'App\Models\Childcategory')->get();
            if (!empty($childAttrs)) {
                foreach ($childAttrs as $key => $childAttr) {
                    $in_name = $childAttr->input_name;
                    if ($request->has("$in_name")) {
                        $attrArr["$in_name"]["values"] = $request["$in_name"];
                        $attrArr["$in_name"]["prices"] = $request["$in_name" . "_price"];
                        if ($childAttr->details_status) {
                            $attrArr["$in_name"]["details_status"] = 1;
                        } else {
                            $attrArr["$in_name"]["details_status"] = 0;
                        }
                    }
                }
            }
        }

        if (empty($attrArr)) {
            $input['attributes'] = NULL;
        } else {
            $jsonAttr = json_encode($attrArr);
            $input['attributes'] = $jsonAttr;
        }

        // Save Data
        $data->fill($input)->save();

        // Set SLug
        $prod = Product::find($data->id);
        if ($prod->type != 'Physical') {
            $prod->slug = Str::slug($data->name, '-') . '-' . strtolower(Str::random(3) . $data->id . Str::random(3));
        } else {
            $prod->slug = Str::slug($data->name, '-') . '-' . strtolower($data->sku);
        }

        // Set Thumbnail
        /*  $img = Image::make(public_path().'/assets/images/products/'.$prod->photo)->resize(285, 285);
        $thumbnail = Str::random(10).'.jpg';
        $img->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
        $prod->thumbnail  = $thumbnail;
        $prod->update(); */

        /**Thumbnail image upload from product image */
        //if(Storage::disk('public')->exists($this->destination.'/'.$input['photo']))
        if (Storage::disk('public')->exists($this->destination . '/' . $prod->photo)) {
            $this->destination  = productThumbnailStorageDestination_hd();//'public/thumbnails';
            $this->imageWidth   = productThumbnailWidth_hd();//285;  //its mandatory
            $this->imageHeight  = productThumbnailHeight_hd();//285;  //its nullable
            $this->file         = $prod->photo; //$input['photo'];//asset("storage/public/products/{$input['photo']}");
            $prod->thumbnail    =  $this->imageUploadFromUploadedImage();
        }
        $prod->update();
        /**Thumbnail image upload from product image */


        // Add To Gallery If any
        $lastid = $data->id;
        if ($files = $request->file('gallery')) {
            foreach ($files as  $key => $file) {
                if (in_array($key, $request->galval)) {
                    $gallery = new Gallery;
                    /*  $name = time().str_replace(' ', '', $file->getClientOriginalName());
                    $file->move('assets/images/galleries',$name);
                    $gallery['photo'] = $name; */

                    $this->destination  = productGalleryStorageDestination_hd();//'public/galleries';   //its mandatory
                    $this->imageWidth   = productGalleryWidth_hd();//300; //its mandatory
                    $this->imageHeight  = productGalleryHeight_hd();//NULL; //its nullable
                    $this->file         = $file;  //its mandatory
                    $gallery['photo']     = $this->storeImage();

                    $gallery['product_id'] = $lastid;
                    $gallery->save();
                }
            }
        }
        //logic Section Ends

        //--- Redirect Section
        $msg = 'New Product Added Successfully.<a href="' . route('admin.product.index') . '">View Product Lists.</a>';
        return response()->json($msg);
        //--- Redirect Section Ends
    }


    //*** POST Request
    public function import()
    {
        $cats = Category::all();
        return view('admin.product.productcsv',compact('cats'));
    }

    /**importSubmit */
        public function importSubmit(Request $request)
        {
            $log = '';
            /****validation***/
            $rules = [
                'csvfile'      => 'required|mimes:csv,txt',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                        'status' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ]);
            }
            /****validation***/

            if($request->has('csvfile'))
            {
                /**set total row and inserted row in session for progress bar */
                $pathName = public_path('temp/bulk_upload_summary/');
                file_put_contents($pathName.'/total_row.txt',0);
                file_put_contents($pathName.'/insert_row.txt',0);
                file_put_contents($pathName.'/remaining_insert_row.txt',0);
                /**set total row and inserted row in session for progress bar */

                $data = file($request->file('csvfile'));

                /**set total row and inserted row in session for progress bar */
                file_put_contents($pathName.'/total_row.txt',count($data) - 1);
                /**set total row and inserted row in session for progress bar */

                //------------------------------------------
                $chunks = array_chunk($data,1000);
                $header = [];
                foreach($chunks as $key => $chunk)
                {
                    ##reading the file
                    $data = array_map('str_getcsv',$chunk);
                    if($key == 0)
                    {
                        $header = $data[0];
                        unset($data[0]);
                    }
                    //## call the Job class
                    ProductUploadCsvFileProcess::dispatch($data);
                    //unlink($chunk);
                }
                //------------------------------------------
            }
            return response()->json([
                'status' => true,
            ]);
        }
    /**End importSubmit */


    /*
    |---------------------------------------------------------------------------
    | get processing percentage and show progress bar
    |---------------------------------------------------------------------------
    */
        public function processingBulkProductUploadProgressBar(Request $request)
        {
            $pathName = public_path('temp/bulk_upload_summary/');
            $totalRow       = file_get_contents($pathName.'/total_row.txt');
            $insertedRow    = file_get_contents($pathName.'/insert_row.txt');
            $remainingRow   = file_get_contents($pathName.'/remaining_insert_row.txt');

            $message = 'Bulk Product File Imported Successfully.<a href="'.route('admin.product.index').'" style="margin:0px 10px;">View Product Lists.</a>'.'<a href="'.asset('temp/bulk_upload_summary/error_report/report.csv').'" style="margin:0px 10px;">View Error Report.</a>';
            if(intval($totalRow) > 0)
            {
                return response()->json([
                    "status"                => true,
                    "totalRow"              => intval($totalRow),
                    "insertedRow"           => intval($insertedRow),
                    "remainingInsertRow"    => intval($remainingRow),
                    "message"               => $message,
                ]);
            }
            return response()->json([
                "status"                => true,
                "totalRow"              => 0,
                "insertedRow"           => 0,
                "remainingInsertRow"    => 0,
                "message"               => $message,
            ]);
        }
    /*
    |---------------------------------------------------------------------------
    | The End get processing percentage and show progress bar
    |---------------------------------------------------------------------------
    */
    /** Processing/uploading bulk product */





    //***Request
    public function importEdit()
    {
        $cats = Category::all();
        return view('admin.product.product-edit-csv',compact('cats'));
    }

    /**importUpdate */
        public function importUpdate(Request $request)
        {
            //return $request;
            $log = "";
            //--- Validation Section
            $rules = [
                'csvfile'      => 'required|mimes:csv,txt',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                        'status' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ]);
            }

            if($request->has('csvfile'))
            {
                /**set total row and inserted row in session for progress bar */
                $pathName = public_path('temp/bulk_update_summary/');
                file_put_contents($pathName.'/total_row.txt',0);
                file_put_contents($pathName.'/insert_row.txt',0);
                file_put_contents($pathName.'/remaining_insert_row.txt',0);
                /**set total row and inserted row in session for progress bar */

                $data = file($request->file('csvfile'));

                /**set total row and inserted row in session for progress bar */
                file_put_contents($pathName.'/total_row.txt',count($data) - 1);
                /**set total row and inserted row in session for progress bar */


                $data = file($request->file('csvfile'));
                $chunks = array_chunk($data,1000);

                $header = [];
                foreach($chunks as $key => $chunk)
                {
                    ##reading the file
                    $data = array_map('str_getcsv',$chunk);
                    if($key == 0)
                    {
                        $header = $data[0];
                        unset($data[0]);
                    }
                    //## call the Job class
                    ProductUpdateCsvFileProcess::dispatch($data);
                    //unlink($chunk);
                }
            }
            return response()->json([
                'status' => true,
            ]);
        }
    /**importUpdate */

    /** Processing/uploading bulk product Update*/
        public function processingBulkProductUpdateProgressBar(Request $request)
        {
            $pathName = public_path('temp/bulk_update_summary/');
            $totalRow       = file_get_contents($pathName.'/total_row.txt');
            $insertedRow    = file_get_contents($pathName.'/insert_row.txt');
            $remainingRow   = file_get_contents($pathName.'/remaining_insert_row.txt');

            $message = 'Bulk Product Update File Imported Successfully.<a href="'.route('admin.product.index').'" style="margin:0px 10px;">View Product Lists.</a>'.'<a href="'.asset('temp/bulk_update_summary/error_report/report.csv').'" style="margin:0px 10px;">View Error Report.</a>';
            if(intval($totalRow) > 0)
            {
                return response()->json([
                    "status"                => true,
                    "totalRow"              => intval($totalRow),
                    "insertedRow"           => intval($insertedRow),
                    "remainingInsertRow"    => intval($remainingRow),
                    "message"               => $message,
                ]);
            }
            return response()->json([
                "status"                => true,
                "totalRow"              => 0,
                "insertedRow"           => 0,
                "remainingInsertRow"    => 0,
                "message"               => $message,
            ]);
        }
    /** Processing/uploading bulk product Update*/







    public function getBrandsByMerchant(Request $request)
    {
        $merchant = VendorInformation::where('user_id',$request->merchant_id)->first();
        $ids = $merchant->brands->pluck('id');
        $query = Brand::query();
        if(count($ids) > 0)
        {
            $query->whereIn('id',$ids);
        }
        $datas = $query->whereNull('deleted_at')->latest()->get();
        $html = '';
        foreach($datas as $data)
        {
            $html .= '<option value="'.$data->id.'">'.$data->name .'</option>';
        }
        return response()->json([
            'status' => true,
            'html'  => $html
        ]);
    }

    //*** GET Request
    public function edit($id)
    {
        if(!Product::where('id',$id)->exists())
        {
            return redirect()->route('admin.dashboard')->with('unsuccess',__('Sorry the page does not exist.'));
        }
        $cats       = Category::all();
        $brands     = Brand::whereNull('deleted_at')->get();
        $merchants  = VendorInformation::select('user_id','shop_name')->get();
        $data       = Product::findOrFail($id);

        if($data->type == 'Digital')
            return view('admin.product.edit.digital',compact('cats','data','brands'));
        elseif($data->type == 'License')
            return view('admin.product.edit.license',compact('cats','data','brands'));
        else
            return view('admin.product.edit.physical',compact('cats','data','merchants', 'brands'));
    }

    // public function variationRemove(Request $request)
    // {
    //     $id = $request->id;
    //     $gal = ProductVariant::findOrFail($id);
    //     $gal->delete();
    //     return response()->json([
    //         'status' => true
    //     ]);
            
    // } 
    
    public function variationAtrEdit(Request $request)
    {
        $id = $request->id;
        $atr_name = $request->atr_name;
        $atr_value = $request->atr_value;
        // return $request;
        $atribute = "[";
        foreach ($atr_name as $key => $item) {
            $atribute .= '{"name":"'.$item.'", "value":"'.$atr_value[$key].'"},';
        }
        $atribute = substr_replace($atribute, '', -1); // to get rid of extra comma
        $atribute .= "]";
        $gal = ProductVariant::findOrFail($id);
        $gal->attributes = $atribute;

        $gal->save();
        
        return response()->json([
            'status' => true,
            'data' => $atribute
        ]);
            
    } 

    public function variationDimeEdit(Request $request)
    {
        $id = $request->id;
        $length = $request->length;
        $width = $request->width;
        $height = $request->height;
        $dimension = '{"length":"'.$length.'", "width":"'.$width.'", "height":"'.$height.'"}';
        $gal = ProductVariant::findOrFail($id);
        $gal->variation_dimension = $dimension;
        $gal->save();
        
        return response()->json([
            'status' => true,
            'data' => $dimension
        ]);
            
    } 
    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        // $rules = [
        //     'file'       => 'nullable|mimes:zip'
        // ];

        // $validator = Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        // }
        //--- Validation Section Ends


        //-- Logic Section
        $data = Product::findOrFail($id);
        $input = $request->all();

        //Check Types
        // if ($request->type_check == 1) {
        //     $input['link'] = null;
        // } else {
        //     if ($data->file != null) {
        //         if (file_exists(public_path() . '/assets/files/' . $data->file)) {
        //             unlink(public_path() . '/assets/files/' . $data->file);
        //         }
        //     }
        //     $input['file'] = null;
        // }

        // if ($request->photo && $request->photo != $data->photo) {
        //     $this->destination  = 'public/products';
        //     if (Storage::disk('public')->exists($this->destination . '/' . $data->photo)) {
        //         $this->destination      = productImageStorageDestination_hd();//'public/products';  //its mandatory
        //         $this->imageNameFromDb  = $data->photo;   //its mandatory
        //         $this->imageDelete();
        //     }
        //     $this->destination  = productImageStorageDestination_hd();//'public/products';
        //     $this->imageWidth   = productImageWidth_hd();//400;  //its mandatory
        //     $this->imageHeight  = productImageHeight_hd();//NULL;  //its nullable
        //     $this->file         = $request->photo;
        //     $input['photo'] =   $this->storeBase64Image();

        //     $this->destination  = 'public/thumbnails';
        //     if (Storage::disk('public')->exists($this->destination . '/' . $data->thumbnail)) {
        //         $this->destination      = productThumbnailStorageDestination_hd();//'public/thumbnails';         //its mandatory
        //         $this->imageNameFromDb  = $data->thumbnail;   //its mandatory
        //         $this->imageDelete();
        //     }
        //     $this->destination  = productThumbnailStorageDestination_hd();//'public/thumbnails';
        //     $this->imageWidth   = productThumbnailWidth_hd();//285;  //its mandatory
        //     $this->imageHeight  = productThumbnailHeight_hd();//285;  //its nullable
        //     $this->file         = $input['photo']; //$input['photo'];//asset("storage/public/products/{$input['photo']}");
        //     $input['thumbnail'] =  $this->imageUploadFromUploadedImage();
        // }


        // Check Physical
        if ($data->type == "Physical" || $data->type == "Aliexpress") {
            //--- Validation Section
            $rules = [
                'sku'                   => 'min:8|unique:products,sku,' . $id,
                'price'                 => 'numeric|required',
                'previous_price'        => 'numeric|required',
                'product_commission'    => 'numeric|required',
                'product_photo'         => 'mimes:jpeg,jpg,png,svg',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            //--- Validation Section Ends
            if ($file = $request->file('product_photo'))
            {
                if (isset(parse_url($data->photo)['path']) && Storage::disk('s3')->exists(parse_url($data->photo)['path'])) {
                    Storage::disk('s3')->delete(parse_url($data->photo)['path']);
                }
                $file_info = Storage::disk('s3')->put('banner', $request->file('product_photo'),'public');
                $input['photo']     = Storage::disk('s3')->url($file_info);
                $input['thumbnail'] = $input['photo'];
            }
            
            if(isset($request->vids))
            {
                foreach($request->vids as $verId)
                {
                    if($request->file('v_p_'.$verId))
                    {   
                        $proVar = ProductVariant::find($verId);

                        if (isset(parse_url($proVar->variation_photo)['path']) && Storage::disk('s3')->exists(parse_url($proVar->variation_photo)['path'])) {
                            Storage::disk('s3')->delete(parse_url($proVar->variation_photo)['path']);
                        }
                        $var_file_info = Storage::disk('s3')->put('banner', $request->file('v_p_'.$verId),'public');
                        $uploadedVarFile  = Storage::disk('s3')->url($var_file_info);
                        $proVar->variation_photo = $uploadedVarFile;
                        $proVar->save();
                    }
                }
            }

            //Product commission
            if ($request->product_commission > $request->price && $request->product_commission > $request->previous_price) {
                return response()->json(array('errors' => [0 => 'Product commission should be less than product current price and previous price']));
            } else {
                //$input['commission'] = $request->product_commission;
                $input['commission'] = ($request->product_commission);
            }
            //Product commission

            //Product price
            $input['price'] = $input['price'];
            $input['shipping_cost'] = $request->shipping_cost;
            $input['previous_price'] = $input['previous_price'];

            // Check Condition
            if ($request->product_condition_check == "") {
                $input['product_condition'] = 0;
            }

            // Check Shipping Time
            if ($request->shipping_time_check == "") {
                $input['ship'] = null;
            }

            // Check Size
            if (empty($request->size_check)) {
                $input['size'] = null;
                $input['size_qty'] = null;
                $input['size_price'] = null;
            } else {
                if (in_array(null, $request->size) || in_array(null, $request->size_qty) || in_array(null, $request->size_price)) {
                    $input['size'] = null;
                    $input['size_qty'] = null;
                    $input['size_price'] = null;
                } else {
                    if (in_array(0, $input['size_qty'])) {
                        return response()->json(array('errors' => [0 => 'Size Qty can not be 0.']));
                    }

                    $input['size'] = implode(',', $request->size);
                    $input['size_qty'] = implode(',', $request->size_qty);
                    $size_prices = $request->size_price;
                    $s_price = array();
                    foreach ($size_prices as $key => $sPrice) {
                        $s_price[$key] = $sPrice;
                    }
                    $input['size_price'] = implode(',', $s_price);
                }
            }
            // Check Size


            // Check Whole Sale
            // if (empty($request->whole_check)) {
            //     $input['whole_sell_qty'] = null;
            //     $input['whole_sell_discount'] = null;
            // } else {
            //     if (in_array(null, $request->whole_sell_qty) || in_array(null, $request->whole_sell_discount)) {
            //         $input['whole_sell_qty'] = null;
            //         $input['whole_sell_discount'] = null;
            //     } else {
            //         $input['whole_sell_qty'] = implode(',', $request->whole_sell_qty);
            //         $input['whole_sell_discount'] = implode(',', $request->whole_sell_discount);
            //     }
            // }
            // Check Whole Sale

            // Check Color
            if (empty($request->color_check)) {
                $input['color'] = null;
            } else {
                if (!empty($request->color)) {
                    $input['color'] = implode(',', $request->color);
                }
                if (empty($request->color)) {
                    $input['color'] = null;
                }
            }
            // Check Color

            // Vendor name
            // $input['user_id'] = empty($request->user_id) ? null : $request->user_id;

            //Check Measurement
            // return $request->measure_check;
            
        }
        // Check Physical
        if ($request->measure_check == 1) {     
            $measure_weight = $request->measure_weight == null ? '""': $request->measure_weight;
            $measure_length = $request->measure_length == null ? '""': $request->measure_length;
            $measure_width = $request->measure_width == null ? '""': $request->measure_width;
            $measure_height = $request->measure_height == null ? '""': $request->measure_height;
            $input['measure'] = '{"weight":'.$measure_weight.',"length":'.$measure_length.', "width":'.$measure_width.', "height":'.$measure_height.'}';
        } 
        // Check Seo
        if (empty($request->seo_check)) {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;
        } else {
            if (!empty($request->meta_tag)) {
                $input['meta_tag'] = implode(',', $request->meta_tag);
            }
        }
        // Check Seo

        // Check License
        if ($data->type == "License") {
            if (!in_array(null, $request->license) && !in_array(null, $request->license_qty)) {
                $input['license'] = implode(',,', $request->license);
                $input['license_qty'] = implode(',', $request->license_qty);
            } else {
                if (in_array(null, $request->license) || in_array(null, $request->license_qty)) {
                    $input['license'] = null;
                    $input['license_qty'] = null;
                } else {
                    $license = explode(',,', $request->license);
                    $license_qty = explode(',', $request->license_qty);
                    $input['license'] = implode(',,', $license);
                    $input['license_qty'] = implode(',', $license_qty);
                }
            }
        }
        // Check License

        // Check Features
        // if (!in_array(null, $request->features) && !in_array(null, $request->colors)) {
        //     $input['features'] = implode(',', str_replace(',', ' ', $request->features));
        //     $input['colors'] = implode(',', str_replace(',', ' ', $request->colors));
        // } else {
        //     if (in_array(null, $request->features) || in_array(null, $request->colors)) {
        //         $input['features'] = null;
        //         $input['colors'] = null;
        //     } else {
        //         $features = explode(',', $data->features);
        //         $colors = explode(',', $data->colors);
        //         $input['features'] = implode(',', $features);
        //         $input['colors'] = implode(',', $colors);
        //     }
        // }
        // Check Features

        // Product Tags
        $input['tags'] = null;
        if (!empty($request->tags)) {
            $input['tags'] = implode(',', $request->tags);
            // save tag if not exist
            // foreach($request->tags as $tag)
            // {
            //     if (! DB::table('tags')->where('name', trim($tag))->first())
            //     {
            //         DB::table('tags')->insert([
            //             'name' => trim($tag)
            //         ]);
            //     }
            // }
        }
        if (!empty($request->variation_id)) {
            $variation_price = $request->variation_price;
            $variation_previous_price = $request->variation_previous_price;
            $variation_stock_quantity = $request->variation_stock_quantity;
            $variation_stock_status = $request->variation_stock_status;
            
            foreach ($request->variation_id as $key => $variation) {
                $product_variant = ProductVariant::findOrFail($variation);
                $product_variant->variation_price = $variation_price[$key];
                $product_variant->variation_previous_price = $variation_previous_price[$key];
                $product_variant->variation_stock_quantity = $variation_stock_quantity[$key];
                $product_variant->variation_stock_status = $variation_stock_status[$key];
                $product_variant->save();
            }
        }


        // store filtering attributes for physical product
        $attrArr = [];
        if (!empty($request->category_id)) {
            $catAttrs = Attribute::where('attributable_id', $request->category_id)->where('attributable_type', 'App\Models\Category')->get();
            if (!empty($catAttrs)) {
                foreach ($catAttrs as $key => $catAttr) {
                    $in_name = $catAttr->input_name;
                    if ($request->has("$in_name")) {
                        $attrArr["$in_name"]["values"] = $request["$in_name"];
                        $attrArr["$in_name"]["prices"] = $request["$in_name" . "_price"];
                        if ($catAttr->details_status) {
                            $attrArr["$in_name"]["details_status"] = 1;
                        } else {
                            $attrArr["$in_name"]["details_status"] = 0;
                        }
                    }
                }
            }
        }

        if (!empty($request->subcategory_id)) {
            $subAttrs = Attribute::where('attributable_id', $request->subcategory_id)->where('attributable_type', 'App\Models\Subcategory')->get();
            if (!empty($subAttrs)) {
                foreach ($subAttrs as $key => $subAttr) {
                    $in_name = $subAttr->input_name;
                    if ($request->has("$in_name")) {
                        $attrArr["$in_name"]["values"] = $request["$in_name"];
                        $attrArr["$in_name"]["prices"] = $request["$in_name" . "_price"];
                        if ($subAttr->details_status) {
                            $attrArr["$in_name"]["details_status"] = 1;
                        } else {
                            $attrArr["$in_name"]["details_status"] = 0;
                        }
                    }
                }
            }
        }
        if (!empty($request->childcategory_id)) {
            $childAttrs = Attribute::where('attributable_id', $request->childcategory_id)->where('attributable_type', 'App\Models\Childcategory')->get();
            if (!empty($childAttrs)) {
                foreach ($childAttrs as $key => $childAttr) {
                    $in_name = $childAttr->input_name;
                    if ($request->has("$in_name")) {
                        $attrArr["$in_name"]["values"] = $request["$in_name"];
                        $attrArr["$in_name"]["prices"] = $request["$in_name" . "_price"];
                        if ($childAttr->details_status) {
                            $attrArr["$in_name"]["details_status"] = 1;
                        } else {
                            $attrArr["$in_name"]["details_status"] = 0;
                        }
                    }
                }
            }
        }


        if (empty($attrArr)) {
            $input['attributes'] = NULL;
        } else {
            $jsonAttr = json_encode($attrArr);
            $input['attributes'] = $jsonAttr;
        }


        $data->update($input);
        //-- Logic Section Ends

        if(! $input['slug']){
            $prod = Product::find($data->id);
            // Set SLug
            $prod->slug = Str::slug($data->name, '-') . '-' . strtolower($data->sku);
            $prod->update();
        }
        
        //--- Redirect Section
        $msg = 'Product Updated Successfully.<a href="' . route('admin.product.index') . '">View Product Lists.</a>';
        return response()->json($msg);
        //--- Redirect Section Ends
    }


    //*** GET Request
    public function feature($id)
    {
            $data = Product::findOrFail($id);
            return view('admin.product.highlight',compact('data'));
    }

    //*** POST Request
    public function featuresubmit(Request $request, $id)
    {
        //-- Logic Section
            $data = Product::findOrFail($id);
            $input = $request->all();
            if($request->featured == "")
            {
                $input['featured'] = 0;
            }
            if($request->hot == "")
            {
                $input['hot'] = 0;
            }
            if($request->best == "")
            {
                $input['best'] = 0;
            }
            if($request->top == "")
            {
                $input['top'] = 0;
            }
            if($request->latest == "")
            {
                $input['latest'] = 0;
            }
            if($request->big == "")
            {
                $input['big'] = 0;
            }
            if($request->trending == "")
            {
                $input['trending'] = 0;
            }
            if($request->sale == "")
            {
                $input['sale'] = 0;
            }
            if($request->is_discount == "")
            {
                $input['is_discount'] = 0;
                $input['discount_date'] = null;
            }

            $data->update($input);
        //-- Logic Section Ends

        //--- Redirect Section
        $msg = 'Highlight Updated Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends

    }

    //*** GET Request
    public function destroy($id)
    {

        $data = Product::findOrFail($id);
        // if($data->galleries->count() > 0)
        // {
        //     foreach ($data->galleries as $gal) {
        //         $this->destination  = 'public/galleries';         //its mandatory
        //         $this->imageNameFromDb = $gal->photo;   //its mandatory
        //         $this->imageDelete();
        //         /* if (file_exists(public_path().'/assets/images/galleries/'.$gal->photo)) {
        //                 unlink(public_path().'/assets/images/galleries/'.$gal->photo);
        //             } */
        //         $gal->delete();
        //     }

        // }

        // if($data->reports->count() > 0)
        // {
        //     foreach ($data->reports as $gal) {
        //         $gal->delete();
        //     }
        // }

        // if($data->wishlists->count() > 0)
        // {
        //     foreach ($data->wishlists as $gal) {
        //         $gal->delete();
        //     }
        // }

        // if($data->comments->count() > 0)
        // {
        //     foreach ($data->comments as $gal) {
        //     if($gal->replies->count() > 0)
        //     {
        //         foreach ($gal->replies as $key) {
        //             $key->delete();
        //         }
        //     }
        //         $gal->delete();
        //     }
        // }


        // if (!filter_var($data->photo,FILTER_VALIDATE_URL)){
        //    /*  if (file_exists(public_path().'/assets/images/products/'.$data->photo)) {
        //         unlink(public_path().'/assets/images/products/'.$data->photo);
        //     } */
        //     $this->destination  = 'public/products';         //its mandatory
        //     $this->imageNameFromDb = $data->photo;   //its mandatory
        //     $this->imageDelete();
        // }

        /* if (file_exists(public_path().'/assets/images/thumbnails/'.$data->thumbnail) && $data->thumbnail != "") {
            unlink(public_path().'/assets/images/thumbnails/'.$data->thumbnail);
        } */
        // $this->destination  = 'public/thumbnails';         //its mandatory
        // $this->imageNameFromDb = $data->thumbnail;   //its mandatory
        // $this->imageDelete();


        // if($data->file != null){
        //     if (file_exists(public_path().'/assets/files/'.$data->file)) {
        //         unlink(public_path().'/assets/files/'.$data->file);
        //     }
        // }
        $data->deleted_at = now();
        $data->status = 0;
        $data->save();
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }


    //this use for soft delete. [specific product report]
    public function destroyProduct(Request $request)
    {
        $data = Product::findOrFail($request->id);
        if($data)
        {
            $data->deleted_at = now();
            $data->status = 0;
            $data->save();
            $msg = 'Data Deleted Successfully.';
            return response()->json([
                'status' => true,
                'msg' =>$msg
            ]);
        }else{
            $msg = 'Data is not Deleted.';
            return response()->json([
                'status' => false,
                'msg' =>$msg
            ]);
        }
    }
    //this use for soft delete. [specific product report]


    public function restore($id)
    {
        $data = Product::findOrFail($id);
        $data->deleted_at = null;
        $data->status = $data->stock > 0 ? 1 : 0;
        $data->save();
        $msg = 'Data Restored Successfully.';
        return response()->json($msg);
    }

    public function getAttributes(Request $request) {
      $model = '';
      if ($request->type == 'category') {
        $model = 'App\Models\Category';
      } elseif ($request->type == 'subcategory') {
        $model = 'App\Models\Subcategory';
      } elseif ($request->type == 'childcategory') {
        $model = 'App\Models\Childcategory';
      }

      $attributes = Attribute::where('attributable_id', $request->id)->where('attributable_type', $model)->get();
      $attrOptions = [];
      foreach ($attributes as $key => $attribute) {
        $options = AttributeOption::where('attribute_id', $attribute->id)->get();
        $attrOptions[] = ['attribute' => $attribute, 'options' => $options];
      }
      return response()->json($attrOptions);
    }

    public function getTags(Request $r)
    {
        return DB::table('products')->where('tag', 'like', '%'.$r->search.'%')->limit(30)->get();
    }
}
