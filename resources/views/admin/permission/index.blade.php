@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('Permissions') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Permissions') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-permission-index') }}">{{ __('Manage Permissions') }}</a>
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
                            <table id="permission_table" class="table table-hover dt-responsive" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Sl No') }}</th>
                                        <th width="50%">{{ __('Permission Name') }}</th>
                                        <th>{{ __('Is Special') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')


    <script type="text/javascript">
        // datatable
        var table = $('#permission_table').DataTable({
            ordering: true,
            processing: true,
            ajax: '{{ route('admin-permission-datatable') }}',
            columns: [{
                    data: 'DT_RowIndex',
                    width: '5%'
                },
                {
                    data: 'name',
                },
                {
                    data: 'is_special',
                    render: function(data){
                        if(data)
                        {
                            return 'Yes';
                        }
                        return 'No';
                    }
                }

            ],
            language: {
                processing: '<img src="{{ asset('assets/images/xloading.gif') }}">'
            }
        });

    </script>

@endsection
