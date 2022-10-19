                <div style="width: 100%;">
                    <div style="width: 50%;float:left;border-bottom:1px solid #d1cfcf;">
                        <h6 style="text-align: center;float: clear;">Order Tracking Details</h6>
                    </div>
                    <div style="width: 50%;float: right;border-bottom:1px solid #d1cfcf;">
                        <h6 style="text-align: center;float: clear;">Order No : {{$order_id}}</h6>
                    </div>
                </div>
                

                    <div class="content-area no-padding">
                        <div class="add-product-content1">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="product-description">
                                        <div class="body-area">
                                            <div class="table-responsive show-table ml-3 mr-3">
                                                <table class="table" id="track-load" data-href="https://admin.ebaskat.com/admin/order/11/trackload">
                                                    <thead>
                                                        <tr>
                                                            <th>Title</th>
                                                            <th>Details</th>
                                                            <th>Date</th>
                                                            <th>Time</th>
                                                            {{-- <th>Options</th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($orders as $item)
                                                            <tr data-id="12">
                                                                <td width="30%" class="t-title">
                                                                    {{$item->title}}
                                                                </td>
                                                                <td width="30%" class="t-text">
                                                                    {{$item->text}}
                                                                </td>
                                                                <td>
                                                                    {{date('Y-m-d',strtotime($item->created_at))}}
                                                                </td>
                                                                <td>
                                                                    {{date('h:m:i',strtotime($item->created_at))}}
                                                                </td>
                                                                {{-- <td>
                                                                    <div class="action-list">
                                                                        <a data-href="https://admin.ebaskat.com/admin/ebaskat/order/track/update/12" class="track-edit"> <i class="fas fa-edit"></i>Edit</a>
                                                                        <a href="javascript:;" data-href="https://admin.ebaskat.com/admin/ebaskat/order/track/delete/12" class="track-delete"><i class="fas fa-trash-alt"></i></a>
                                                                    </div>
                                                                </td> --}}
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>