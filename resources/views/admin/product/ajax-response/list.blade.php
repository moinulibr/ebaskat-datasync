                    
                        <div class="table-responsiv">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        {{-- <th>
                                            <input class="check_all_class " type="checkbox" value="all" name="check_all" style="box-shadow:none;">
                                        </th> --}}
                                        <th>{{ __('Photo') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Stock') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $item)
                                    <tr>
                                        {{-- <td>
                                            <input class="check_single_class form-control" type="checkbox"  name="checked_id[]" value="{{ $item->id }}" class="check_single_class" id="{{$item->id}}" style="box-shadow:none;">
                                        </td> --}}
                                        <td>
                                            <span class="productShortDetail" data-id="{{$item->id}}" style="cursor: pointer;">
                                                <img src="{{ $item->photo }}" width="40" height="40" alt="photo" />
                                            </span>
                                        </td>
                                       <td>
                                            <span class="productShortDetail" data-id="{{$item->id}}" style="cursor: pointer;">
                                                {!! Str::limit($item->name, 40, ' ...') !!}
                                            </span>
                                           {{-- {{$item->name}} --}}
                                        </td>
                                       <td>
                                            <span class="productShortDetail" data-id="{{$item->id}}" style="cursor: pointer;">
                                            {{ $item->categories ? $item->categories->name: "No Category" }}
                                            </span>
                                       </td>
                                       <td>
                                            {{ ucfirst($item->product_from) }} <br/>
                                            <span style="color:rgb(16, 16, 87)">{{$item->sku}}</span>
                                        </td>
                                        <td>
                                            @php
                                                $totalStock = 0;
                                                $stock = (string)$item->stock_quantity;
                                                if($stock == "0")
                                                    $totalStock = "Out Of Stock";
                                                elseif($stock == null)
                                                    $totalStock ="Unlimited";
                                                else
                                                $totalStock = $item->stock_quantity;
                                            @endphp
                                            {{$totalStock}}
                                        </td>
                                        <td>
                                            @php
                                                $price = number_format($item->current_price ,2, '.', '');
                                                $price = '€'.$price;
                                            @endphp
                                            {{$price}}
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
                                            <div style="text-align: center">
                                                @if ($item->pub_status == 1)
                                                <span class="badge" style="background-color:#2d3274;color:white;">Published</span>
                                                    @else
                                                    <span class="badge badge-warning">Un-published</span>
                                                @endif
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
                                                    
                                                    @if(!$item->deleted_at)
                                                            @if (Auth::guard('admin')->user()->role->permissionCheck('products|delete'))
                                                                <a href="javascript:;" data-href="{{route('admin.product.delete',$item->id)}}" data-toggle="modal" data-target="#delete_modal" class="delete"><i class="fas fa-trash-alt"></i> Delete</a>
                                                            @endif                
                                                        @else
                                                        @if (Auth::guard('admin')->user()->role->permissionCheck('recover_delete'))
                                                            <a href="javascript:;" data-href="{{route('admin.prod.restore', $item->id) }}" data-toggle="modal" data-target="#restore_modal" title="restore"><i class="fas fa-reply-all"></i> Restore</a>            
                                                        @endif                
                                                    @endif

                                                    @if ($item->pub_status == 1 && $item->status == 1 && !$item->deleted_at)
                                                            @if (Auth::guard('admin')->user()->role->permissionCheck('products|delete'))
                                                            <a href="javascript:;" data-href="{{route('admin.published.product.unpublishing',$item->id) }}" data-toggle="modal" data-target="#unpublished_modal" class="delete">
                                                                <i class="fas fa-reply-all"></i> Un-published
                                                            </a>
                                                            @endif   
                                                        @elseif ($item->pub_status == 0  && !$item->deleted_at)
                                                            @if (Auth::guard('admin')->user()->role->permissionCheck('recover_delete'))
                                                            <a href="javascript:;" data-href="{{ route('admin.unpublished.product.unpublishing', $item->id) }}" data-toggle="modal" data-target="#publishing_modal" title="published"><i class="fas fa-forward"></i> Published</a>
                                                            @endif                  
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- {{ $products->links() }} --}}
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
                        
