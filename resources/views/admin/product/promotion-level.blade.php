<style>
    .success{
        padding: 2%;
        border: .40px solid green;
        background-color: green;
        color: white;
        border-radius: 3px;
    }
    .danger{
        padding: 2%;
        border: .40px solid red;
        background-color: red;
        color: white;
        border-radius: 3px;
    }
</style>
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
                        <div class="stars">
                            <strong class="review-no">SKU : </strong>
                            <strong> {{ $product->sku }}</strong>
                        </div>
                    </div>
                    <div class="rating">
                            <div class="stars">
                                <strong class="review-no">Category : </strong>
                               <strong> {{ $product->category?$product->category->name : NULL }}</strong>
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
                            <strong class="review-no">Product Type : </strong>
                            <strong> {{ ucfirst($product->product_type ) }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <h6>Promotion Level</h6>
                    <table class="table" style="width: 100%">
                        <thead style="border:1px solid #dbd9d9">
                            <tr>
                                <th style="padding:1%;">
                                    Sl.
                                </th>
                                <th style="border-right:1px solid #dbd9d9 !important;padding:1%;text-align: center;">Promotion Level</th>
                                <th style="border-right:1px solid #dbd9d9 !important;padding:1%;text-align: center;">Promotion Status</th>
                                <th style="padding:1%;text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody style="border:1px solid #dbd9d9">
                            <tr>
                                <td style="padding:1%;">
                                    <span class="badge badge-info">
                                        1
                                    </span>
                                </td>
                                <td style="border-right:1px solid #dbd9d9;padding:1%;text-align: center;">
                                    <h6 class="allInactivePackagesWhenChangesStatus">
                                        Just In
                                    </h6>
                                </td>
                                <td style="border-right:1px solid #dbd9d9;padding:1%;text-align: center;">
                                    <strong class="promotion_status_just_in {{ $product->just_in == 0 ? "danger" : "success" }}">
                                        @if ($product->just_in == 0)
                                         Inactive
                                        @else
                                         Active
                                        @endif
                                    </strong>
                                    
                                </td>
                                <td style="padding:1%;text-align: center;">
                                    <button data-href="" data-id="{{$product->id}}" data-value="just_in" class="changePromotionStatus btn btn-info btn-sm btn_active_just_in" {{ $product->just_in == 1 ? "disabled" : "" }}>Active</button>
                                    <button data-href="" data-id="{{$product->id}}" data-value="just_in" class="changePromotionStatus btn btn-danger btn-sm btn_inactive_just_in" {{ $product->just_in == 0 ? "disabled" : "" }}>Inactive</button>
                                </td> 
                            </tr>
                            <tr>
                                <td style="padding:1%;">
                                    <span class="badge badge-info">
                                        2
                                    </span>
                                </td>
                                <td style="border-right:1px solid #dbd9d9;padding:1%;text-align: center;">
                                    <h6 class="allInactivePackagesWhenChangesStatus">
                                        Weekly Deals
                                    </h6>
                                </td>
                                <td style="border-right:1px solid #dbd9d9;padding:1%;text-align: center;">
                                    <strong class="promotion_status_weekly_deals {{ $product->weekly_deals == 0 ? "danger" : "success" }}">
                                        @if ($product->weekly_deals == 0)
                                         Inactive
                                        @else
                                         Active
                                        @endif
                                    </strong>      
                                </td>
                                <td style="padding:1%;text-align: center;">
                                    <button data-href="" data-id="{{$product->id}}" data-value="weekly_deals" class="changePromotionStatus btn btn-info btn-sm btn_active_weekly_deals" {{ $product->weekly_deals == 1 ? "disabled" : "" }}>Active</button>
                                    <button data-href="" data-id="{{$product->id}}" data-value="weekly_deals" class="changePromotionStatus btn btn-danger btn-sm btn_inactive_weekly_deals" {{ $product->weekly_deals == 0 ? "disabled" : "" }}>Inactive</button>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:1%;">
                                    <span class="badge badge-info">
                                        3
                                    </span>
                                </td>
                                <td style="border-right:1px solid #dbd9d9;padding:1%;text-align: center;">
                                    <h6 class="allInactivePackagesWhenChangesStatus">
                                        Trending Products
                                    </h6>
                                </td>
                                <td style="border-right:1px solid #dbd9d9;padding:1%;text-align: center;">
                                    <strong class="promotion_status_trending_products {{ $product->trending_products == 0 ? "danger" : "success" }}">
                                        @if ($product->trending_products == 0)
                                         Inactive
                                        @else
                                         Active
                                        @endif
                                    </strong>      
                                </td>
                                <td style="padding:1%;text-align: center;">
                                    <button data-href="" data-id="{{$product->id}}" data-value="trending_products" class="changePromotionStatus btn btn-info btn-sm btn_active_trending_products" {{ $product->trending_products == 1 ? "disabled" : "" }}>Active</button>
                                    <button data-href="" data-id="{{$product->id}}" data-value="trending_products" class="changePromotionStatus btn btn-danger btn-sm btn_inactive_trending_products" {{ $product->trending_products == 0 ? "disabled" : "" }}>Inactive</button>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:1%;">
                                    <span class="badge badge-info">
                                        4
                                    </span>
                                </td>
                                <td style="border-right:1px solid #dbd9d9;padding:1%;text-align: center;">
                                    <h6 class="allInactivePackagesWhenChangesStatus">
                                        Top Kids & Baby Products
                                    </h6>
                                </td>
                                <td style="border-right:1px solid #dbd9d9;padding:1%;text-align: center;">
                                    <strong class="promotion_status_top_kids_baby_products {{ $product->top_kids_baby_products == 0 ? "danger" : "success" }}">
                                        @if ($product->top_kids_baby_products == 0)
                                         Inactive
                                        @else
                                         Active
                                        @endif
                                    </strong>      
                                </td>
                                <td style="padding:1%;text-align: center;">
                                    <button data-href="" data-id="{{$product->id}}" data-value="top_kids_baby_products" class="changePromotionStatus btn btn-info btn-sm btn_active_top_kids_baby_products" {{ $product->top_kids_baby_products == 1 ? "disabled" : "" }}>Active</button>
                                    <button data-href="" data-id="{{$product->id}}" data-value="top_kids_baby_products" class="changePromotionStatus btn btn-danger btn-sm btn_inactive_top_kids_baby_products" {{ $product->top_kids_baby_products == 0 ? "disabled" : "" }}>Inactive</button>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:1%;">
                                    <span class="badge badge-info">
                                        5
                                    </span>
                                </td>
                                <td style="border-right:1px solid #dbd9d9;padding:1%;text-align: center;">
                                    <h6 class="allInactivePackagesWhenChangesStatus">
                                        Featured Phones & Accessories
                                    </h6>
                                </td>
                                <td style="border-right:1px solid #dbd9d9;padding:1%;text-align: center;">
                                    <strong class="promotion_status_featured_phones_accessories {{ $product->featured_phones_accessories == 0 ? "danger" : "success" }}">
                                        @if ($product->featured_phones_accessories == 0)
                                         Inactive
                                        @else
                                         Active
                                        @endif
                                    </strong>      
                                </td>
                                <td style="padding:1%;text-align: center;">
                                    <button data-href="" data-id="{{$product->id}}" data-value="featured_phones_accessories" class="changePromotionStatus btn btn-info btn-sm btn_active_featured_phones_accessories" {{ $product->featured_phones_accessories == 1 ? "disabled" : "" }}>Active</button>
                                    <button data-href="" data-id="{{$product->id}}" data-value="featured_phones_accessories" class="changePromotionStatus btn btn-danger btn-sm btn_inactive_featured_phones_accessories" {{ $product->featured_phones_accessories == 0 ? "disabled" : "" }}>Inactive</button>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:1%;">
                                    <span class="badge badge-info">
                                        6
                                    </span>
                                </td>
                                <td style="border-right:1px solid #dbd9d9;padding:1%;text-align: center;">
                                    <h6 class="allInactivePackagesWhenChangesStatus">
                                        The Beauty Editor's Pick
                                    </h6>
                                </td>
                                <td style="border-right:1px solid #dbd9d9;padding:1%;text-align: center;">
                                    <strong class="promotion_status_the_beauty_editors_pick {{ $product->the_beauty_editors_pick == 0 ? "danger" : "success" }}">
                                        @if ($product->the_beauty_editors_pick == 0)
                                         Inactive
                                        @else
                                         Active
                                        @endif
                                    </strong>      
                                </td>
                                <td style="padding:1%;text-align: center;">
                                    <button data-href="" data-id="{{$product->id}}" data-value="the_beauty_editors_pick" class="changePromotionStatus btn btn-info btn-sm btn_active_the_beauty_editors_pick" {{ $product->the_beauty_editors_pick == 1 ? "disabled" : "" }}>Active</button>
                                    <button data-href="" data-id="{{$product->id}}" data-value="the_beauty_editors_pick" class="changePromotionStatus btn btn-danger btn-sm btn_inactive_the_beauty_editors_pick" {{ $product->the_beauty_editors_pick == 0 ? "disabled" : "" }}>Inactive</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>