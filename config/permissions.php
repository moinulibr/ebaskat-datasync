<?php

/* ---------------------------------------------------------- */
/*    please read the notes if you want to make any changes   */
/* ---------------------------------------------------------- */

/**
 * 1. Permission name should be all in lower case.
 * 2. Words will be separated by an underscore.
 * 3. If a permission will not have add, edit and delete functions, then the "is_special" attribute will be true.
 */

return [
    [ 'name'=>'orders', 'allowed' => ['edit', 'delete', 'status_update', 'send_mail', 'track']],
    [ 'name'=>'products', 'allowed' => ['add', 'edit', 'delete', 'edit_catalog','aliexpress_add','aliexpress_update','bigbuy_add','bigbuy_update']],
    [ 'name'=>'brands_manage', 'allowed' => ['add', 'edit', 'delete']],
    [ 'name'=>'customers', 'allowed' => ['add', 'edit', 'delete', 'send_email', 'detail']],
    [ 'name'=>'affilate_products', 'allowed' => ['add', 'edit', 'delete', 'view_gellary']],
    [ 'name'=>'merchants', 'allowed' => ['add', 'edit', 'delete', 'email', 'status_update', 'reset_password', 'secret_login']],
    [ 'name'=>'subscription_plans', 'allowed' => ['add', 'edit', 'delete']],
    [ 'name'=>'categories', 'allowed' => ['add', 'edit', 'delete']],
    [ 'name'=>'bulk_product_upload', 'allowed' => []],
    [ 'name'=>'bulk_product_update', 'allowed' => []],
    [ 'name'=>'product_discussion', 'allowed' => []],
    [ 'name'=>'set_coupons', 'allowed' => ['add', 'edit', 'delete']],
    [ 'name'=>'blog', 'allowed' => ['add', 'edit', 'delete']],
    [ 'name'=>'messages', 'allowed' => []],
    [ 'name'=>'general_settings', 'allowed' => ['add', 'edit', 'delete']],
    [ 'name'=>'home_page_settings', 'allowed' => ['slider', 'service', 'banner', 'review']],
    [ 'name'=>'emails_settings', 'allowed' => ['template', 'group_email']],
    [ 'name'=>'payment_settings', 'allowed' => ['add', 'edit', 'delete']],
    [ 'name'=>'language_settings', 'allowed' => ['add', 'edit', 'delete']],
    [ 'name'=>'manage_staffs', 'allowed' => ['add', 'edit', 'delete']],
    [ 'name'=>'subscribers', 'allowed' => []],
    [ 'name'=>'report', 'allowed' => []],
    [ 'name'=>'reset_password', 'allowed' => []],
    [ 'name'=>'recover_delete', 'allowed' => []],
    [ 'name'=>'merchant_subscription', 'allowed' => []],
    [ 'name'=>'vendor_subscription_plans', 'allowed' => []],
    [ 'name'=>'account', 'allowed' => ['add', 'edit', 'delete']],
    [ 'name'=>'order_shipment', 'allowed' => ['add', 'edit', 'delete']],
    [ 'name'=>'global_vat_tax', 'allowed' => ['add', 'edit', 'delete']],
    [ 'name'=>'contact_us', 'allowed' => ['add', 'edit', 'delete']],
];
