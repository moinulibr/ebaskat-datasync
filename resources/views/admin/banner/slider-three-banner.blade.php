@extends('layouts.admin')

@section('styles')
    <style type="text/css">
        td img {
            max-height: 100px;
            max-width: 500px;
        }
    </style>
@endsection

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('BANNER') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-5">
                    <h4 class="heading">{{ __('Main Slider Three Banners') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                        </li>
                        <li>
                            <a href="javascript:">{{ __('Home Page Settings') }} </a>
                        </li>
                        <li>
                            <a href="javascript:">{{ __('Main Slider Three Banners') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-7">
                    <div class="mr-breadcrumb">
                      <div class="row">
                          <div class="col-md-12 mt-3">
                              <div class="btn-group float-right" role="group">
                                <a href="{{ route('slider-three-banner') }}" class="btn btn-info">Slider 3 Banners</a>
                                <a href="{{ route('three-promotional-banner') }}" class="btn btn-secondary">Promotional 3 Banners</a>
                                <a href="{{ route('top-flase-banner') }}" class="btn btn-secondary">Top Flase Banners</a>
                                <a href="{{ route('admin-popup') }}" class="btn btn-secondary">Popup Banners</a>
                                <a href="{{ route('admin-error-banner') }}" class="btn btn-secondary">Error Banners</a>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">

                        @include('includes.form-success')

                        <div class="table-responsiv">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>{{ __('Featured Image') }}</th>
                                    <th>{{ __('Link') }}</th>
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
                    <img src="{{asset('assets/images/xloading.gif')}}" alt="">
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
    @include('includes.delete-modal', ['type' => 'Main Slider Three Banners'])
    {{-- Resote Modal --}}
    @include('includes.restore_modal', ['type' => 'Main Slider Three Banners'])

@endsection



@section('scripts')


    {{-- DATA TABLE --}}

    <script type="text/javascript">

        var table = $('#geniustable').DataTable({
            ordering: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-sb-datatables','SliderThree') }}',
            columns: [
                {data: 'photo', name: 'photo', searchable: false, orderable: false},
                {data: 'link', name: 'link'},
                {data: 'action', searchable: false, orderable: false}
            ],
            language: {
                processing: '<img src="{{asset('assets/images/xloading.gif')}}">'
            }
        });

        $(function () {
            $(".btn-area").append('<div class="col-sm-4 table-contents">' +
                '<a class="add-btn" data-href="{{route('slider-three-create-banner')}}" id="add-data" data-toggle="modal" data-target="#modal1">' +
                '<i class="fas fa-plus"></i> {{ __('Add New Banner') }}' +
                '</a>' +
                '</div>');
        });


        {{-- DATA TABLE ENDS--}}


    </script>





@endsection
