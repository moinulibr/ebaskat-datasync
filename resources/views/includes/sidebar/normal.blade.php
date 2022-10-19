@if(Auth::guard('admin')->user()->role_id != 0)

    
    @if(Auth::guard('admin')->user()->role->permissionCheck('products'))
        <li>
            <a href="#menu2" class="accordion-toggle wave-effect @if(request()->is('admin/products/*')) active @endif" data-toggle="collapse" aria-expanded="{{(request()->routeIs('admin/products/*') || request()->is('admin/products/*')) ? 'true':'false' }}">
                <i class="fas fa-shopping-cart"></i>{{ __('Products') }}
            </a>
            <ul class="collapse list-unstyled @if(request()->is('admin/products/edit/*') ||request()->is('admin/products/physical/*')) show @elseif(request()->is('admin/products/import/create')||request()->is('admin/products/import/edit/*')) show @elseif(request()->routeIs('admin/products/*')) show @endif" id="menu2" data-parent="#accordion">
                <li class="@if(request()->is('admin/products/edit/*') ||request()->is('admin/products/physical/*')) active @endif">
                    <a href="{{ route('admin.product.index') }}"><span>{{ __('All Products') }}</span></a>
                </li>
                {{-- @if(Auth::guard('admin')->user()->role->permissionCheck('products|edit'))
                <li class="@if(request()->is('admin/products/unpublished/list/*') ||request()->is('admin/products/unpublished/list/*')) active @endif">
                    <a href="{{ route('admin.products.unpublished.list') }}"><span>{{ __('Un-published Products') }}</span></a>
                </li>
                @endif --}}
                @if(Auth::guard('admin')->user()->role->permissionCheck('products'))
                <li class="@if(request()->is('admin/products/deactive') && request()->input('type')=='deactive')) active @endif">
                    <a href="{{ route('admin.product.deactive') }}"><span>{{ __('Deactivated Product') }}</span></a>
                </li>
                @endif
                

                @if(Auth::guard('admin')->user()->role->permissionCheck('products|aliexpress_add'))
                <li>
                    <a href="{{ route('admin_dropship_aliexpress_index') }}"><span>{{ __('Dropshipping Aliexpress Import') }}</span></a>
                </li>
                @endif
                @if(Auth::guard('admin')->user()->role->permissionCheck('products|aliexpress_update'))
                <li>
                    <a href="{{ route('admin.dropship.aliexpress.update.imported.product.by.page') }}"><span>{{ __('Dropshipping Aliexpress Update') }}</span></a>
                </li>
                @endif
                @if(Auth::guard('admin')->user()->role->permissionCheck('products|bigbuy_add'))
                <li>
                    <a href="{{ route('adminDropshippingBigbuyIndex') }}"><span>{{ __('Dropshipping Bigbuy Import') }}</span></a>
                </li>
                @endif
                @if(Auth::guard('admin')->user()->role->permissionCheck('products|bigbuy_update'))
                <li>
                    <a href="{{ route('admin.dropship.bigbuy.update.imported.single.product') }}"><span>{{ __('Dropshipping Bigbuy Update') }}</span></a>{{--admin.dropship.bigbuy.update.imported.product.by.page--}}
                </li>
                @endif
            </ul>
        </li>
    @endif


    @if(Auth::guard('admin')->user()->role->permissionCheck('orders'))
        {{-- <li>
            <a href="{{ route('admin.order.index') }}" class="wave-effect @if(request()->is('admin/orders/*') || request()->is('admin/order/*')) active @endif">
                <i class="fas fa-list-alt"></i>{{ __('All Orders') }}
            </a>
        </li>  --}}
        <li>
            <a href="#order" class="accordion-toggle wave-effect @if(request()->is('aliexpress/order/*')) active @endif" data-toggle="collapse" aria-expanded="false">
                <i class="fas fa-list-alt"></i>{{ __('Orders') }}
            </a>
            <ul class="collapse list-unstyled @if(request()->is('aliexpress/order/*')) show @endif" id="order" data-parent="#accordion">
                {{--  <li class="@if(request()->is('order/*')) active @endif">
                    <a href="{{ route('admin.order.index') }}"><span>{{ __('All Orders') }}</span></a>
                </li>  --}}
                <li class="@if(request()->is('main/order/*')) active @endif">
                    <a href="{{ route('admin.main.order.index') }}"><span>{{ __('All Orders') }}</span></a>
                </li> 
                <li class="@if(request()->is('order/*')) active @endif">
                    <a href="{{ route('ebaskat.admin.order.index') }}"><span>{{ __('Ebasket Orders') }}</span></a>
                </li>
                <li class="@if(request()->is('aliexpress/order/*')) active @endif">
                    <a href="{{ route('aliexpress.admin.order.index') }}"><span>{{ __('Aliexpress Orders') }}</span></a>
                </li> 
                <li class="@if(request()->is('bigbuy/order/*')) active @endif">
                    <a href="{{ route('bigbuy.admin.order.index') }}"><span>{{ __('Bigbuy Orders') }}</span></a>
                </li>
            </ul>
        </li>
    @endif

   
@endif