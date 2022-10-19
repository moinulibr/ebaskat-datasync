
                       
                        
                        <div class="row" style="margin-top:.5% ">
                            <div class="col-md-6">
                                <button class="activateAllCustomer btn btn-sm btn-primary" style="display: none;">Activate</button>
                                <button class="inactivateAllCustomer btn btn-sm btn-danger" style="display: none;">Deactivate</button>        
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
                                        <th>{{ __('Photo') }}</th>
                                        <th>{{ __('Customer Name') }}</th>
                                        <th>{{ __('Emai') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Coupon Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customrs as $item)
                                    <tr>
                                        <td>
                                            <input class="check_single_class form-control" type="checkbox"  name="checked_id[]" value="{{ $item->id }}" class="check_single_class" id="{{$item->id}}" style="box-shadow:none;">
                                        </td>
                                        <td>
                                            <img src="{{ $item->photo }}" width="40" height="40" alt="photo" />
                                        </td>
                                       <td>{{$item->name}}</td>
                                       <td>
                                            {{ $item->email }}
                                       </td>
                                       <td>
                                            {{ $item->phone }}
                                        </td>
                                      
                                       <td style="width:10%">
                                            <div class="action-list">
                                                <select class="process select droplinks  {{$item->coupon_apply == 1 ? 'drop-success' : 'drop-danger' }} " style="display: none;">
                                                    <option {{$item->coupon_apply == 1 ? 'selected':''}} data-val="1" value="{{route('admin.user.couponable.activate.status',[$item->id,1])}}">Activated</option>
                                                    <option {{$item->coupon_apply == 0 ? 'selected':''}} data-val="0" value="{{route('admin.user.couponable.activate.status',[$item->id,0])}}">Deactivated</option>
                                                </select>
                                                <div class="nice-select process select droplinks  {{$item->coupon_apply == 1 ? 'drop-success' : 'drop-danger' }}" tabindex="0"><span class="current">{{$item->coupon_apply == 1 ? 'Activated' : 'Deactivated' }}</span>
                                                    <ul class="list">
                                                        <li data-value="{{route('admin.user.couponable.activate.status',[$item->id,1])}}" class="option selected">Activated</li>
                                                        <li data-value="{{route('admin.user.couponable.activate.status',[$item->id,0])}}" class="option">Deactivated</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{-- <div class="godropdown">
                                                <button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button>
                                                <div class="action-list" style="display: none;">
                                                    <a href="http://localhost/Laravel_8/Akib_bhai/ecom/ebaskat-admin/public/admin/bigbuy/order/13/show"> <i class="fas fa-eye"></i> Details</a>
                                                    <a class="adminOrderToBigbuy" href="#" data-href="http://localhost/Laravel_8/Akib_bhai/ecom/ebaskat-admin/public/admin/bigbuy/order/1/to/bigbuy"> <i class="fas fa-forward"></i> Place To Bigbuy</a>
                                                    <a class="adminOrderDeliveryStatus" href="#" data-href="http://localhost/Laravel_8/Akib_bhai/ecom/ebaskat-admin/public/admin/bigbuy/order/delivery/status" data-id="13"> <i class="fas fa-play"></i> Delivery Status</a>
                                                    <a class="adminOrderTrackingDetails" href="#" data-href="http://localhost/Laravel_8/Akib_bhai/ecom/ebaskat-admin/public/admin/bigbuy/order/tracking/details" data-id="1"> <i class="fas fa-truck"></i>Tracking Details</a>
                                                </div>
                                            </div> --}}
                                            <div class="action-list">
                                                <a href="{{route('admin.user.show',$item->id)}}"> <i class="fas fa-eye"></i> Details</a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $customrs->links() }}
                        </div>



    

