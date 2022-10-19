<?php

namespace App\Http\Controllers\Admin;

use Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Category;
use App\Traits\FileStorage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ImportController extends Controller
{
    use FileStorage;
    
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Product::where(function($q){
            if (! Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) {
                $q->whereNull('deleted_at');
            }
        })->where('product_type', '=', 'affiliate')->orderBy('id', 'desc')->get();

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
        ->editColumn('name', function (Product $data) {
            $name = mb_strlen(strip_tags($data->name), 'utf-8') > 50 ? mb_substr(strip_tags($data->name), 0, 50, 'utf-8') . '...' : strip_tags($data->name);
            $id = '<small>Product ID: <a href="#" target="_blank">' . sprintf("%'.08d", $data->id) . '</a></small>';
            return  $name . '<br>' . $id . $data->checkVendor();
        })
            ->editColumn('price', function (Product $data) {
                $price = '€'. $data->price;
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

                $action = '<div class="godropdown">
                <button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button>
                <div class="action-list">';

                if(Auth::guard('admin')->user()->role->permissionCheck('affilate_products|edit'))
                {
                    $action .= '<a href="' . route('admin.import.edit', $data->id) . '"> <i class="fas fa-edit"></i> Edit</a>';
                    $action .= '<a data-href="' . route('admin.product.feature', $data->id) . '" class="feature" data-toggle="modal" data-target="#modal2"> <i class="fas fa-star"></i> Highlight</a>';
                }
                if(! $data->deleted_at)
                {
                    if(Auth::guard('admin')->user()->role->permissionCheck('affilate_products|delete'))
                    {
                        $action .= '<a href="javascript:;" data-href="' . route('admin.affiliate.product.delete', $data->id) . '" data-toggle="modal" data-target="#delete_modal" class="delete"><i class="fas fa-trash-alt"></i> Delete</a>';
                    }
                }
                else
                {
                    if (Auth::guard('admin')->user()->role->permissionCheck('recover_delete')) 
                    {
                        $action .= '<a href="javascript:;" data-href="' . route('admin.affiliate.prod.restore', $data->id) . '" data-toggle="modal" data-target="#restore_modal" title="restore"><i class="fas fa-reply-all"></i> Restore</a>';
                    }
                }
                if(Auth::guard('admin')->user()->role->permissionCheck('affilate_products|view_gellary'))
                {
                    $action .= '<a href="javascript" class="set-gallery" data-toggle="modal" data-target="#setgallery"><input type="hidden" value="' . $data->id . '"><i class="fas fa-eye"></i> View Gallery</a>';
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
        return view('admin.productimport.index');
    }

    //*** GET Request
    public function createImport()
    {
        $data['brands'] = Brand::whereNull('deleted_at')->get();

        $cats = Category::all();
        return view('admin.productimport.createone',compact('cats'));
    }

    //*** GET Request
    public function importCSV()
    {
        $cats = Category::all();
        return view('admin.productimport.importcsv',compact('cats'));
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

        return response()->json(['status'=>true,'file_name' => $image_name]);
    }

    //*** POST Request
    public function store(Request $request)
    {
        if ($request->image_source == 'file') {
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
        }

        //--- Logic Section        
        $data = new Product;
        $input = $request->all();

        // Check File
        if ($file = $request->file('file')) {
            $name = time() . str_replace(' ', '', $file->getClientOriginalName());
            $file->move('assets/files', $name);
            $input['file'] = $name;
        }

        $input['photo'] = "";
        if ($request->photo != ""
        ) {
            /*  $image = $request->photo;
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

            $this->destination  = productThumbnailStorageDestination_hd();//'public/thumbnails';
            $this->imageWidth   = productThumbnailWidth_hd();//285;  //its mandatory
            $this->imageHeight  = productThumbnailHeight_hd();//285;  //its nullable
            $this->file         = $request->photo;
            $input['thumbnail'] =   $this->storeBase64Image();
        } else {
            $input['photo'] = $request->photolink;
            $input['thumbnail'] =  $request->photolink;
        }

        // Check Physical
        if ($request->type == "Physical") {
            //--- Validation Section
            $rules = ['sku'      => 'min:8|unique:products'];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            //--- Validation Section Ends


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

            // Check Color
            if (empty($request->color_check)) {
                $input['color'] = null;
            } else {
                $input['color'] = implode(',', $request->color);
            }

            // Check Measurement
            if ($request->mesasure_check == "") {
                $input['measure'] = null;
            }
        } //end Physical

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

        //tags 
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


        $input['price'] = $input['price'];
        $input['previous_price'] = $input['previous_price'];
        $input['product_type'] = "affiliate";

        // Save Data 
        $data->fill($input)->save();

        // Set SLug
        $prod = Product::find($data->id);
        if ($prod->type != 'Physical') {
            $prod->slug = Str::slug($data->name, '-') . '-' . strtolower(Str::random(3) . $data->id . Str::random(3));
        } else {
            $prod->slug = Str::slug($data->name, '-') . '-' . strtolower($data->sku);
        }

        /* $fimageData = public_path().'/assets/images/products/'.$prod->photo;
            if(filter_var($prod->photo,FILTER_VALIDATE_URL)){
                $fimageData = $prod->photo;
            }

            $img = Image::make($fimageData)->resize(285, 285);
            $thumbnail = Str::random(10).'.jpg';
            $img->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
            $prod->thumbnail  = $thumbnail; */
        $prod->update();

        // Add To Gallery If any
        $lastid = $data->id;
        if ($files = $request->file('gallery')) {
            foreach ($files as  $key => $file) {
                if (in_array($key, $request->galval)) {
                    $gallery = new Gallery;
                    /* $name = time().str_replace(' ', '', $file->getClientOriginalName());
                        $img = Image::make($file->getRealPath())->resize(800, 800);
                        $thumbnail = Str::random(10).'.jpg';
                        $img->save(public_path().'/assets/images/galleries/'.$name); */

                    $this->destination  = productGalleryStorageDestination_hd();//'public/galleries';   //its mandatory
                    $this->imageWidth   = productGalleryWidth_hd();//800; //its mandatory
                    $this->imageHeight  = productGalleryHeight_hd();//800; //its nullable
                    $this->file         = $file;  //its mandatory
                    $gallery['photo']   = $this->storeImage();

                    $gallery['product_id'] = $lastid;
                    $gallery->save();
                }
            }
        }
        //logic Section Ends

        //--- Redirect Section        
        $msg = 'New Affiliate Product Added Successfully.<a href="' . route('admin.import.index') . '">View Product Lists.</a>';
        return response()->json($msg);
        //--- Redirect Section Ends    
    }

    //*** GET Request
    public function edit($id)
    {
        $brands     = Brand::whereNull('deleted_at')->get();
        $cats = Category::all();
        $data = Product::findOrFail($id);
        return view('admin.productimport.editone',compact('cats','data','brands'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        $prod = Product::find($id);
        //--- Validation Section
        $rules = [
            'file'       => 'mimes:zip'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends


        //-- Logic Section
        $data = Product::findOrFail($id);
        $input = $request->all();

        //Check Types 
        if ($request->type_check == 1) {
            $input['link'] = null;
        } else {
            if ($data->file != null) {
                if (file_exists(public_path() . '/assets/files/' . $data->file)) {
                    unlink(public_path() . '/assets/files/' . $data->file);
                }
            }
            $input['file'] = null;
        }

        if ($request->image_source == 'file') {
            //$input['photo'] = $request->photo;
            if ($request->file('photo')) {
                $this->destination  = productImageStorageDestination_hd();//'public/products';   //its mandatory
                $this->imageWidth   = productImageWidth_hd();//400; //its mandatory
                $this->imageHeight  = productImageHeight_hd();//NULL; //its nullable
                $this->file         = $request->file('photo');  //its mandatory
                $this->imageNameFromDb = $prod->photo;  //its mandatory
                $input['photo']     = $this->updateImage();

                $this->destination  = productThumbnailStorageDestination_hd();//'public/thumbnails';   //its mandatory
                $this->imageWidth   = productThumbnailWidth_hd();//300; //its mandatory
                $this->imageHeight  = productThumbnailHeight_hd();//300; //its nullable
                $this->file         = $request->file('photo');  //its mandatory
                $this->imageNameFromDb = $prod->thumbnail;  //its mandatory
                $input['thumbnail']     = $this->updateImage();
            }
        } else {
            $input['photo'] = $request->photolink;
            $input['thumbnail'] = $request->photolink;
        }


        // Check Physical
        if ($data->type == "Physical") {

            //--- Validation Section
            $rules = ['sku' => 'min:8|unique:products,sku,' . $id];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            //--- Validation Section Ends


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

            // Check Measure
            if ($request->measure_check == "") {
                $input['measure'] = null;
            }
        } //end Check Physical

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
        if ($data->type == "License") {
            if (!in_array(null, $request->license) && !in_array(null, $request->license_qty)) {
                $input['license'] = implode(',,', $request->license);
                $input['license_qty'] = implode(',', $request->license_qty);
            } else {
                if (in_array(null, $request->license) || in_array(null, $request->license_qty)) {
                    $input['license'] = null;
                    $input['license_qty'] = null;
                } else {
                    $license = explode(',,', $prod->license);
                    $license_qty = explode(',', $prod->license_qty);
                    $input['license'] = implode(',,', $license);
                    $input['license_qty'] = implode(',', $license_qty);
                }
            }
        } // Check License

        // Check Features
        if (!in_array(null,
            $request->features
        ) && !in_array(null, $request->colors)) {
            $input['features'] = implode(',', str_replace(',', ' ', $request->features));
            $input['colors'] = implode(',', str_replace(',', ' ', $request->colors));
        } else {
            if (in_array(null, $request->features) || in_array(null, $request->colors)) {
                $input['features'] = null;
                $input['colors'] = null;
            } else {
                $features = explode(',', $data->features);
                $colors = explode(',', $data->colors);
                $input['features'] = implode(',', $features);
                $input['colors'] = implode(',', $colors);
            }
        }

        //Product Tags 
        $input['tags'] = null;
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

        $input['price'] = $input['price'];
        $input['previous_price'] = $input['previous_price'];
        $data->update($input);

        // Set SLug
        $prod->slug = Str::slug($data->name, '-') . '-' . strtolower($data->sku);
        //-- Logic Section Ends

        /* if($data->photo != null)
        {
            if (file_exists(public_path().'/assets/images/thumbnails/'.$data->thumbnail)) {
                unlink(public_path().'/assets/images/thumbnails/'.$data->thumbnail);
            }
        }  */


        //$fimageData = public_path().'/assets/images/products/'.$prod->photo;

        /* if(filter_var($prod->photo,FILTER_VALIDATE_URL)){
            $fimageData = $prod->photo;
        }

        $img = Image::make($fimageData)->resize(285, 285);
        $thumbnail = Str::random(10).'.jpg';
        $img->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
        $prod->thumbnail  = $thumbnail; */

        $prod->save();

        //--- Redirect Section        
        $msg = 'Product Updated Successfully.<a href="' . route('admin.import.index') . '">View Product Lists.</a>';
        return response()->json($msg);
        //--- Redirect Section Ends    
    }
}
