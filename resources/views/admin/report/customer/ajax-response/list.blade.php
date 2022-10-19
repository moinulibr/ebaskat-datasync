                    
                        <div class="table-responsive">
                            <table id="geniustable" class="table table-hover table-responsive-md" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Total Order') }}</th>
                                        <th>{{ __('Pending') }}</th>
                                        <th>{{ __('Complete') }}</th>
                                        <th>{{ __('Decline') }}</th>
                                        <th>{{ __('Processing') }}</th>
                                        <th>{{ __('On Delivered') }}</th>
                                        <th>{{ __('Partial Delivered') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $item)
                                    <tr>
                                       <td>
                                            {{ $item->customer_name }}
                                        </td>
                                        <td>
                                            {{ $item->customer_email }}
                                        </td>
                                       <td>
                                            {{$item->total}}
                                       </td>
                                       <td>
                                            {{$item->pending}}
                                        </td>
                                        <td>
                                            {{$item->complete}}
                                        </td>
                                        <td>
                                            {{ $item->declined }}
                                        </td>
                                        <td>
                                            {{ $item->processing }}
                                        </td>
                                        <td>
                                            {{ $item->on_delivery }}
                                        </td>
                                        <td>
                                            {{ $item->partial_delivered }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $customers->links() }}
                        </div>
                        <input type="hidden" class="page_no" name="page" value="{{$page_no}}">

