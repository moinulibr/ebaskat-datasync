                    
                        <div class="table-responsive">
                            <table id="geniustable" class="table table-hover table-responsive-md" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('City') }}</th>
                                        <th>{{ __('Country') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Is Ban') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $item)
                                    <tr>
                                       <td>
                                            {{ $item->name }}
                                        </td>
                                        <td>
                                            {{ $item->email }}
                                        </td>
                                       <td>
                                            {{$item->phone}}
                                       </td>
                                       <td>
                                            {{$item->city}}
                                        </td>
                                       <td>
                                            {{$item->country}}
                                        </td>
                                       
                                        <td>
                                            {{ date('Y-M-d', strtotime($item->created_at)) }}
                                        </td>
                                        <td>
                                            @if ($item->ban == 0)
                                                <span class="badge badge-success">No</span>
                                            @else
                                                <span class="badge badge-danger">Yes</span>
                                            @endif
                                            
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $customers->links() }}
                        </div>
                        <input type="hidden" class="page_no" name="page" value="{{$page_no}}">

