                    
                        <div class="table-responsive">
                            <table id="geniustable" class="table table-hover table-responsive-md" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Order Date') }}</th>
                                        <th>{{ __('Order Number') }}</th>
                                        <th>{{ __('Merchant Name') }}</th>
                                        <th>{{ __('Customer Name') }}</th>
                                        <th>{{ __('Customer Email') }}</th>
                                        <th>{{ __('Method') }}</th>
                                        <th>{{ __('Pay_amount') }}</th>
                                        <th>{{ __('Txnid') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $item)
                                    <tr>
                                       <td>
                                        {{ date('Y-M-d', strtotime($item->created_at)) }}
                                        </td>
                                        <td>
                                            {{ $item->order_number }}
                                        </td>
                                       <td>
                                            {{$item->name}}
                                       </td>
                                       <td>
                                            {{$item->customer_name}}
                                        </td>
                                       <td>
                                            {{$item->customer_email}}
                                        </td>
                                       <td>
                                            {{ $item->method }}
                                        </td>
                                        <td>
                                            {{$item->pay_amount}}
                                        </td>
                                        <td>
                                            {{ $item->txnid }}
                                        </td>
                                        <td>
                                            @php
                                                $status = '';
                                                foreach (main_orders_status_hh() as  $index => $sts){
                                                    if ($index == $item->status){
                                                        $status = $sts;
                                                    }
                                                }
                                            @endphp
                                            <span class="badge badge-secondary">{{ $status }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $orders->links() }}
                        </div>
                        <input type="hidden" class="page_no" name="page" value="{{$page_no}}">

