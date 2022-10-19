                    
                        <div class="table-responsiv">
                            <table id="geniustable" class="table table-hover table-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Just in') }}</th>
                                        <th>{{ __('Weekly Deals') }}</th>
                                        <th>{{ __('Trending Products') }}</th>
                                        <th>{{ __('Top Kids & Baby Products') }}</th>
                                        <th>{{ __('Featured Phones & Accessories') }}</th>
                                        <th>{{ __('The Beauty Editors Pick') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $item)
                                    <tr>
                                        <td>
                                            <span class="productShortDetail" data-id="{{$item->id}}">
                                                {!! Str::limit($item->name, 40, ' ...') !!}
                                            </span>
                                        </td>
                                       <td>
                                            <div class="action-list">
                                                <select class="process select droplinks  {{$item->just_in == 1 ? 'drop-success' : 'drop-danger' }} " style="display: none;">
                                                    <option {{$item->status == 1 ? 'selected':''}} data-val="1" value="{{route('admin.product.status',[$item->id,1])}}">Ac>
                                                    <option {{$item->just_in == 1 ? 'selected':''}} data-val="1" value="{{route('admin.product.promotion_status',[$item->id,'id2' => 'just_in', 'id3' => 1])}}">Activated</option>
                                                    <option {{$item->just_in == 0 ? 'selected':''}} data-val="0" value="{{route('admin.product.promotion_status',[$item->id, 'id2' => 'just_in', 'id3' => 0])}}">Deactivated</option>
                                                </select>
                                                <div class="nice-select process select droplinks  {{$item->just_in == 1 ? 'drop-success' : 'drop-danger' }} " tabindex="0"><span class="current">{{$item->just_in == 1 ? 'Activated' : 'Deactivated' }}</span>
                                                    <ul class="list">
                                                        <li data-value="{{route('admin.product.promotion_status',[$item->id,'id2' => 'just_in', 'id3' => 1])}}" class="option {{$item->just_in == 1?'selected':''}} ">Activated</li>
                                                        <li data-value="{{route('admin.product.promotion_status',[$item->id, 'id2' => 'just_in', 'id3' => 0])}}" class="option {{$item->just_in == 0?'selected':''}} ">Deactivated</li>
                                                    </ul>
                                                </div>
                                            </div>  
                                        </td>
                                       <td>
                                            <div class="action-list">
                                                <select class="process select droplinks  {{$item->weekly_deals == 1 ? 'drop-success' : 'drop-danger' }} " style="display: none;">
                                                    <option {{$item->weekly_deals == 1 ? 'selected':''}} data-val="1" value="{{route('admin.product.promotion_status',[$item->id,'id2' => 'weekly_deals', 'id3' => 1])}}">Activated</option>
                                                    <option {{$item->weekly_deals == 0 ? 'selected':''}} data-val="0" value="{{route('admin.product.promotion_status',[$item->id, 'id2' => 'weekly_deals', 'id3' => 0])}}">Deactivated</option>
                                                </select>
                                                <div class="nice-select process select droplinks  {{$item->weekly_deals == 1 ? 'drop-success' : 'drop-danger' }} " tabindex="0"><span class="current">{{$item->weekly_deals == 1 ? 'Activated' : 'Deactivated' }}</span>
                                                    <ul class="list">
                                                        <li data-value="{{route('admin.product.promotion_status',[$item->id,'id2' => 'weekly_deals', 'id3' => 1])}}" class="option {{$item->weekly_deals == 1?'selected':''}} ">Activated</li>
                                                        <li data-value="{{route('admin.product.promotion_status',[$item->id, 'id2' => 'weekly_deals', 'id3' => 0])}}" class="option {{$item->weekly_deals == 0?'selected':''}} ">Deactivated</li>
                                                    </ul>
                                                </div>
                                            </div>  
                                       </td>
                                       <td>
                                            <div class="action-list">
                                                <select class="process select droplinks  {{$item->trending_products == 1 ? 'drop-success' : 'drop-danger' }} " style="display: none;">
                                                    <option {{$item->trending_products == 1 ? 'selected':''}} data-val="1" value="{{route('admin.product.promotion_status',[$item->id,'id2' => 'trending_products', 'id3' => 1])}}">Activated</option>
                                                    <option {{$item->trending_products == 0 ? 'selected':''}} data-val="0" value="{{route('admin.product.promotion_status',[$item->id, 'id2' => 'trending_products', 'id3' => 0])}}">Deactivated</option>
                                                </select>
                                                <div class="nice-select process select droplinks  {{$item->trending_products == 1 ? 'drop-success' : 'drop-danger' }} " tabindex="0"><span class="current">{{$item->trending_products == 1 ? 'Activated' : 'Deactivated' }}</span>
                                                    <ul class="list">
                                                        <li data-value="{{route('admin.product.promotion_status',[$item->id,'id2' => 'trending_products', 'id3' => 1])}}" class="option {{$item->trending_products == 1?'selected':''}} ">Activated</li>
                                                        <li data-value="{{route('admin.product.promotion_status',[$item->id, 'id2' => 'trending_products', 'id3' => 0])}}" class="option {{$item->trending_products == 0?'selected':''}} ">Deactivated</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-list">
                                                <select class="process select droplinks  {{$item->top_kids_baby_products == 1 ? 'drop-success' : 'drop-danger' }} " style="display: none;">
                                                    <option {{$item->top_kids_baby_products == 1 ? 'selected':''}} data-val="1" value="{{route('admin.product.promotion_status',[$item->id,'id2' => 'top_kids_baby_products', 'id3' => 1])}}">Activated</option>
                                                    <option {{$item->top_kids_baby_products == 0 ? 'selected':''}} data-val="0" value="{{route('admin.product.promotion_status',[$item->id, 'id2' => 'top_kids_baby_products', 'id3' => 0])}}">Deactivated</option>
                                                </select>
                                                <div class="nice-select process select droplinks  {{$item->top_kids_baby_products == 1 ? 'drop-success' : 'drop-danger' }} " tabindex="0"><span class="current">{{$item->top_kids_baby_products == 1 ? 'Activated' : 'Deactivated' }}</span>
                                                    <ul class="list">
                                                        <li data-value="{{route('admin.product.promotion_status',[$item->id,'id2' => 'top_kids_baby_products', 'id3' => 1])}}" class="option {{$item->top_kids_baby_products == 1?'selected':''}} ">Activated</li>
                                                        <li data-value="{{route('admin.product.promotion_status',[$item->id, 'id2' => 'top_kids_baby_products', 'id3' => 0])}}" class="option {{$item->top_kids_baby_products == 0?'selected':''}} ">Deactivated</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-list">
                                                <select class="process select droplinks  {{$item->featured_phones_accessories == 1 ? 'drop-success' : 'drop-danger' }} " style="display: none;">
                                                    <option {{$item->featured_phones_accessories == 1 ? 'selected':''}} data-val="1" value="{{route('admin.product.promotion_status',[$item->id,'id2' => 'featured_phones_accessories', 'id3' => 1])}}">Activated</option>
                                                    <option {{$item->featured_phones_accessories == 0 ? 'selected':''}} data-val="0" value="{{route('admin.product.promotion_status',[$item->id, 'id2' => 'featured_phones_accessories', 'id3' => 0])}}">Deactivated</option>
                                                </select>
                                                <div class="nice-select process select droplinks  {{$item->featured_phones_accessories == 1 ? 'drop-success' : 'drop-danger' }} " tabindex="0"><span class="current">{{$item->featured_phones_accessories == 1 ? 'Activated' : 'Deactivated' }}</span>
                                                    <ul class="list">
                                                        <li data-value="{{route('admin.product.promotion_status',[$item->id,'id2' => 'featured_phones_accessories', 'id3' => 1])}}" class="option {{$item->featured_phones_accessories == 1?'selected':''}} ">Activated</li>
                                                        <li data-value="{{route('admin.product.promotion_status',[$item->id, 'id2' => 'featured_phones_accessories', 'id3' => 0])}}" class="option {{$item->featured_phones_accessories == 0?'selected':''}} ">Deactivated</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-list">
                                                <select class="process select droplinks  {{$item->the_beauty_editors_pick == 1 ? 'drop-success' : 'drop-danger' }} " style="display: none;">
                                                    <option {{$item->the_beauty_editors_pick == 1 ? 'selected':''}} data-val="1" value="{{route('admin.product.promotion_status',[$item->id,'id2' => 'the_beauty_editors_pick', 'id3' => 1])}}">Activated</option>
                                                    <option {{$item->the_beauty_editors_pick == 0 ? 'selected':''}} data-val="0" value="{{route('admin.product.promotion_status',[$item->id, 'id2' => 'the_beauty_editors_pick', 'id3' => 0])}}">Deactivated</option>
                                                </select>
                                                <div class="nice-select process select droplinks  {{$item->the_beauty_editors_pick == 1 ? 'drop-success' : 'drop-danger' }} " tabindex="0"><span class="current">{{$item->the_beauty_editors_pick == 1 ? 'Activated' : 'Deactivated' }}</span>
                                                    <ul class="list">
                                                        <li data-value="{{route('admin.product.promotion_status',[$item->id,'id2' => 'the_beauty_editors_pick', 'id3' => 1])}}" class="option {{$item->the_beauty_editors_pick == 1?'selected':''}} ">Activated</li>
                                                        <li data-value="{{route('admin.product.promotion_status',[$item->id, 'id2' => 'the_beauty_editors_pick', 'id3' => 0])}}" class="option {{$item->the_beauty_editors_pick == 0?'selected':''}} ">Deactivated</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $products->links() }}
                        </div>
                        <input type="hidden" class="page_no" name="page" value="{{$page_no}}">

