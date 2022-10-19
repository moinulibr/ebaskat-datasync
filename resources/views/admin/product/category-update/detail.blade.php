<div class="container">
    <div class="card">
        <div class="container-fliud">
            <div class="wrapper row">
                <div class="preview col-md-6">
                    
                    <div class="preview-pic tab-content">
                    <div class="tab-pane active" id="pic-1"><img src="{{ $product->photo }}" /></div>
                    </div>
                    <ul class="preview-thumbnail nav nav-tabs"></ul>
                    
                </div>
                <div class="details col-md-6">
                    <h3 class="product-title">{{ $product->name }}</h3>
                    <div class="rating">
                        {{-- <div class="stars">
                            <strong class="review-no">SKU : </strong>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>
                        </div> --}}
                            <div class="stars">
                                <strong class="review-no">SKU : </strong>
                               <strong> {{ $product->sku }}</strong>
                            </div>
                    </div>
                    <div class="rating">
                        <div class="stars">
                            <strong class="review-no">Category : </strong>
                            <strong class="categoryName"> {{ $product->category?$product->category->name : NULL }}</strong>
                        </div>
                    </div>
                    <div class="rating">
                        <div class="stars">
                            <strong class="review-no">Sub-Category : </strong>
                            <strong class="subCategoryName"> {{ $product->subcategory?$product->subcategory->name : NULL }}</strong>
                        </div>
                    </div>
                    <div class="rating">
                        <div class="stars">
                            <strong class="review-no">Status : </strong>
                            <strong> {{ $product->status == 1 ? "Activate" : "Inactive" }}</strong>
                        </div>
                    </div>
                    <div class="rating">
                        <div class="stars">
                            <strong class="review-no">Published Status : </strong>
                            <strong> {{ $product->pub_status == 1 ? "Published" : "Non-published" }}</strong>
                        </div>
                    </div> 
                    <div class="rating">
                        <div class="stars">
                            <strong class="review-no">Product Type : </strong>
                            <strong> {{ ucfirst($product->product_type ) }}</strong>
                        </div>
                    </div> 
                    <div class="rating">
                        <div class="stars">
                            <strong class="review-no">Promotional Lebel : </strong>
                            <strong> 
                                <a href="javascript:;" data-href="' . route('admin.main.order.show.delivery.status.details') . '" class="promotion_level" data-id="{{$product->id}}">
                                    <i class="fas fa-eye"></i>View
                                </a>    
                            </strong>
                        </div>
                    </div>
                    
                    <div class="action" style="margin-top:30px;margin-right:20px;background-color:aliceblue;padding:5px;">
                        <strong style="padding-bottom:3px;border-bottom: 1px solid rgb(182, 179, 179);">Update Category</strong>
                        <form action="{{route('admin.product.category.update')}}" method="POST" style="margin-top: 15px;" class="updateSingleCategory">
                            @csrf
                            <input type="hidden" name="product_id" value="{{$product->id}}">
                            <div class="row">
                                <div class="col-12">
                                    <label>Category</label>
                                    <select name="category_id" id="category_id_from_edit" class="categoryId_from_edit form-control select2">
                                        @foreach ($categories as $item)
                                            <option {{$product->category_id == $item->id ?'selected':'' }} value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label>Sub Category</label>
                                    <select name="subcategory_id" id="subcategory_id_from_edit" class="form-control">
                                        @foreach ($subCategories as $item)
                                        <option {{$product->subcategory_id == $item->id ?'selected':'' }}  value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8"></div>
                                <div class="col-4">
                                   <input type="submit" value="Update" class="form-control btn btn-primary btn-sm">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-12">
                    <hr/>
                    <h4><b><u>Details</u></b></h4>
                    <p class="product-description">{!! $product->details !!}</p>
                </div>
            </div>
        </div>
    </div>
</div>