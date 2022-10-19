
                       
                        
                        <div class="row" style="margin-top:.5% ">
                            <div class="col-md-6">
                                <button class="publishedAllProduct btn btn-sm btn-primary" style="display: none;">Published All Product</button>
                                <button class="deletedAllProduct btn btn-sm btn-danger" style="display: none;">Delete All Product</button>        
                            </div>
                            <div class="col-md-6"></div>
                        </div>

                        <div class="table-responsiv">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>
                                            <input class="check_all_class " type="checkbox" value="all" name="check_all" style="box-shadow:none;">
                                        </th>
                                        <th>{{ __('SKU') }}</th>
                                        <th>{{ __('Image') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Shipping Cost') }}</th>
                                        <th>{{ __('Stock') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Published Status') }} / <br/>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $item)
                                        @if (restrictedKeyWordExistOrNot_hh($item->name,$item->details))
                                            @continue 
                                        @endif
                                    <tr>
                                        <td>
                                            <input class="check_single_class form-control" type="checkbox"  name="checked_id[]" value="{{ $item->id }}" class="check_single_class" id="{{$item->id}}" style="box-shadow:none;">
                                        </td>
                                        <td>
                                            {{ $item->sku }}
                                        </td>
                                       <td>
                                           <span class="productShortDetail" data-id="{{$item->id}}" style="cursor: pointer">
                                                <img src="{{ $item->photo }}" width="40" height="40" alt="photo" />
                                            </span>
                                       </td>
                                       <td><span class="productShortDetail" data-id="{{$item->id}}">{{$item->name}}</span></td>
                                       <td>{{$item->type}}</td>
                                       <td>{{$item->shipping_cost}}</td>
                                       <td>{{$item->stock}}</td>
                                       <td>{{$item->price}}</td>
                                       <td style="width:10%">
                                            <span class="badge badge-warning" style="color:red;">
                                                <small>{{$item->pub_status == 0 ? "Un-published" : " Published"}}</small>
                                            </span>
                                            <br/>
                                            <span class="badge badge-danger">
                                                {{$item->status == 1 ? "Activate" : "In-active"}}
                                            </span>
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
                        

    

