                    
                        <div class="table-responsiv">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Sku') }}</th>
                                        <th>{{ __('Buy Pirce') }}</th>
                                        <th>{{ __('Sell Price') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Stock') }}</th>
                                        <th class="not-export">{{ __('Status') }}</th>
                                        <th class="not-export">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $key => $item)
                                    <tr>
                                       <td>
                                            <span class="productShortDetail" data-id="{{$item->id}}">
                                                {!! Str::limit($item->name, 40, ' ...') !!}
                                            </span>
                                           {{-- {{$item->name}} --}}
                                        </td>
                                        <td>
                                            {{ $item->sku }}
                                        </td>
                                            @php
                                                $price = number_format($item->price ,2, '.', '');
                                                $price = '€'.$price;

                                                $dsPrice     = $item->ds_product_price ?? 0;
                                                $dsPPrice   = number_format($dsPrice ,2, '.', '');
                                            @endphp
                                       <td>
                                            {{ $dsPPrice  > 0 ? "€". $dsPPrice :  NULL }}
                                       </td>
                                       <td>
                                           
                                            {{$price}}
                                        </td>
                                       <td>
                                            {{ date('Y-M-d', strtotime($item->created_at)) }}
                                        </td>
                                        <td>
                                            {{-- @php
                                                $totalStock = 0;
                                                $stock = (string)$item->stock;
                                                if($stock == "0")
                                                    $totalStock = "Out Of Stock";
                                                elseif($stock == null)
                                                    $totalStock ="Unlimited";
                                                else
                                                $totalStock = $item->stock;
                                            @endphp --}}
                                            {{$item->stock}}
                                        </td>
                                        
                                       <td style="width:10%">
                                            <div class="action-list">
                                                <select class="process select droplinks  {{$item->status == 1 ? 'drop-success' : 'drop-danger' }} " style="display: none;">
                                                    <option {{$item->status == 1 ? 'selected':''}} data-val="1" value="{{route('admin.product.status',[$item->id,1])}}">Activated</option>
                                                    <option {{$item->status == 0 ? 'selected':''}} data-val="0" value="{{route('admin.product.status',[$item->id,0])}}">Deactivated</option>
                                                </select>
                                                <div class="nice-select process select droplinks  {{$item->status == 1 ? 'drop-success' : 'drop-danger' }} " tabindex="0"><span class="current">{{$item->status == 1 ? 'Activated' : 'Deactivated' }}</span>
                                                    <ul class="list">
                                                        <li data-value="{{route('admin.product.status',[$item->id,1])}}" class="option {{$item->status == 1?'selected':''}} ">Activated</li>
                                                        <li data-value="{{route('admin.product.status',[$item->id,0])}}" class="option {{$item->status == 0?'selected':''}} ">Deactivated</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td> 
                                        <td>
                                            <div class="godropdown">
                                                <button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button>
                                                <div class="action-list" style="display: none;">
                                                    @if (Auth::guard('admin')->user()->role->permissionCheck('products|edit'))    
                                                        <a href="{{route('admin.product.edit',$item->id)}}"> <i class="fas fa-edit"></i> Edit</a>
                                                        <a class="categoryEdit" data-id="{{$item->id}}"> <i class="fas fa-edit"></i>Category Edit</a>
                                                    @endif
                                                    <a href="javascript:;" data-href="{{ route('admin.product.promotion-level') }}" class="promotion_level" data-id="{{ $item->id }}">
                                                        <i class="fas fa-forward"></i> Promotional Level
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                           {{--  {{ $products->links() }} --}}
                        </div>
                        <input type="hidden" class="page_no" name="page" value="{{$page_no}}">


                        
                        <div class="row">
                            <div class="col-md-3">
                                Showing {{$products->count()}} from {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() }} of {{ $products->total() }}  entries 
                            </div>
                            <div class="col-md-9">
                                <div style="float: right">
                                {{ $products->links() }}
                                </div>
                            </div>
                        </div>
                        
