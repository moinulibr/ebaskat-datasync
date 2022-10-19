@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('STAFF') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Staffs') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-staff-index') }}">{{ __('Manage Staffs') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        @include('includes.form-success')
                        <div class="table-responsiv">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Role') }}</th>
                                        <th>{{ __('Options') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ADD / EDIT MODAL --}}

    <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="submit-loader">
                    <img src="{{ asset('assets/images/xloading.gif') }}" alt="">
                </div>
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>

    </div>

    {{-- ADD / EDIT MODAL ENDS --}}

    {{-- DELETE MODAL --}}
    @include('includes.delete-modal', ['type' => 'Staffs'])
    {{-- Resote Modal --}}
    @include('includes.restore_modal', ['type' => 'Staffs'])

    {{-- Reset MODAL --}}
    <div class="modal fade" id="reset_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __('Confirm Reset') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <p class="text-center">{{ __('You are about to reset password of this Staff.') }}</p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" id="reset_password_btn" class="btn btn-danger">{{ __('Reset') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Reset MODAL ENDS --}}

	<input type="hidden" id="can_add" value="{{\Auth::guard('admin')->user()->role->permissionCheck('manage_staffs|add')}}">
@endsection



@section('scripts')

    {{-- DATA TABLE --}}


    <script type="text/javascript">
        var table = $('#geniustable').DataTable({
            ordering: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-staff-datatables') }}',
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'role',
                    name: 'role'
                },
                {
                    data: 'action',
                    searchable: false,
                    orderable: false
                }

            ],
            language: {
                processing: '<img src="{{ asset('assets/images/xloading.gif') }}">'
            }
        });

		if(+$('#can_add').val())
        $(function() {
            $(".btn-area").append('<div class="col-sm-4 text-right">' +
                '<a class="add-btn" data-href="{{ route('admin-staff-create') }}" id="add-data" data-toggle="modal" data-target="#modal1">' +
                '<i class="fas fa-plus"></i> {{ __('Add New Staff') }}' +
                '</a>' +
                '</div>');
        });

        // reset password
        function resetOperation(e){
            $("#reset_modal form").attr('action', $(e).data('href'));
            $("#reset_modal").modal('show');
        }

        $("#reset_password_btn").click(function(){
            let url = $("#reset_modal form").attr('action');
            let data = {'_token': $('meta[name="csrf-token"]').attr('content')};
            $.notify("Password reseting....", "info");
            $.post(url, data)
            .done(function(data){
                $.notify("Password reseted and Email sended.", "success");
                $("#reset_modal").modal('hide');
            })
            .fail(function() {
                $.notify("Error occur.", "error");
            });
        });

    </script>

    {{-- DATA TABLE --}}

@endsection