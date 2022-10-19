
<li>
    <a href="#menu2" class="accordion-toggle wave-effect @if(request()->is('admin/products/*')) active @endif" data-toggle="collapse" aria-expanded="{{(request()->routeIs('admin/products/*') || request()->is('admin/products/*')) ? 'true':'false' }}">
        <i class="fas fa-shopping-cart"></i>{{ __('Products') }}
    </a>
    <ul class="collapse list-unstyled @if(request()->is('admin/products/edit/*') ||request()->is('admin/products/physical/*')) show @elseif(request()->is('admin/products/import/create')||request()->is('admin/products/import/edit/*')) show @elseif(request()->routeIs('admin/products/*')) show @endif" id="menu2" data-parent="#accordion">
        <li class="@if(request()->is('admin/products/edit/*') ||request()->is('admin/products/physical/*')) active @endif">
            <a href="{{ route('admin.product.index') }}"><span>{{ __('All Products') }}</span></a>
        </li>
        {{-- <li class="@if(request()->is('admin/products/unpublished/list/*') ||request()->is('admin/products/unpublished/list/*')) active @endif">
            <a href="{{ route('admin.products.unpublished.list') }}"><span>{{ __('Un-published Products') }}</span></a>
        </li> --}}
        <li class="@if(request()->is('admin/products/deactive') && request()->input('type')=='deactive')) active @endif">
            <a href="{{ route('admin.product.deactive') }}"><span>{{ __('Deactivated Product') }}</span></a>
        </li>
        
        <li>
            <a href="{{ route('admin_dropship_aliexpress_index') }}"><span>{{ __('Dropshipping Aliexpress Import') }}</span></a>
        </li> 
        <li>
            <a href="{{ route('admin.dropship.aliexpress.update.imported.product.by.page') }}"><span>{{ __('Dropshipping Aliexpress Update') }}</span></a>
        </li>

        <li>
            <a href="{{ route('admin.aliexpress.display.single.product.update.by.sku') }}"><span>{{ __('Dropshipping Aliexpress Update (by sku)') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin.aliexpress.display.single.product.import.by.id') }}"><span>{{ __('Dropshipping Aliexpress Import (by id)') }}</span></a>
        </li>
        <li>
            <a href="{{ route('adminDropshippingBigbuyIndex') }}"><span>{{ __('Dropshipping Bigbuy Import') }}</span></a>
        </li> 
        <li>
            <a href="{{ route('admin.dropship.bigbuy.update.imported.single.product') }}"><span>{{ __('Dropshipping Bigbuy Update') }}</span></a>{{--admin.dropship.bigbuy.update.imported.product.by.page--}}
        </li>
        <li>
            <a href="{{ route('admin.bigbuy.display.single.product.import.by.sku') }}"><span>{{ __('Dropshipping Bigbuy Import (by sku)') }}</span></a>{{--admin.dropship.bigbuy.update.imported.product.by.page--}}
        </li>
    </ul>
</li>


<li>
    <a href="{{ route('admin-cache-clear') }}" class=" wave-effect"><i
            class="fas fa-sync"></i>{{ __('Clear Cache') }}</a>
</li>
