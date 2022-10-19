<?php

namespace App\Console\Commands\Tables;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IndexingColumns
{
    public function __construct()
    {
        // return;
        try {
            $table_name  = 'attributes';
            echo "\n<br/><h3> Indexing column in in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('attributable_id');
                $table->index('attributable_type');
            });
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'attribute_options';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('attribute_id');
            });
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'reviews';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
            });
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }


        try {
            $table_name  = 'sliders';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('photo');
            });
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }


        try {
            $table_name  = 'wishlists';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('user_id');
                $table->index('product_id');
            });
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'blogs';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('category_id');
            });
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'blog_categories';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('slug');
            });
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            //code...
            $table_name  = 'banners';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('type');
            });
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            //code...
            $table_name  = 'brands';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('name');
                $table->index('email');
                $table->index('slug'); 
                $table->index('logo');
                $table->index('web_address');
            });
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'brand_merchant';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('brand_id');
                $table->index('vendor_information_id');
            });
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            //code...
            $table_name  = 'categories';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('name');
                $table->index('slug');
            });
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            //code...
            $table_name  = 'childcategories';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('name');
                $table->index('slug');
                $table->index('subcategory_id');
            });
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            //code...
            $table_name  = 'comments';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('user_id');
                $table->index('product_id');
            });
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            //code...
            $table_name  = 'messages';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('conversation_id');
                $table->index('sent_user');
                $table->index('recieved_user');
            });
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'coupons';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('code');
            });

            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'email_templates';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('email_type');
                $table->index('email_subject');
            });

            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
        
        try {
            $table_name  = 'favorite_sellers';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('user_id');
                $table->index('vendor_id');
            });

            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'global_vat_taxes';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('country_code');
                $table->index('country_name');
            });

            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'notifications';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('order_id');
                $table->index('user_id');
                $table->index('vendor_id');
                $table->index('product_id');
                $table->index('conversation_id');
            });

            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'orders';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('user_id');
                $table->index('charge_id');
                $table->index('txnid');
                $table->index('customer_phone');
                $table->index('customer_country');
                $table->index('status');
            });

            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'order_packages';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('merchant_id');
                $table->index('order_id');
                $table->index('alix_order_id');
            });

            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'order_products';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('order_package_id');
                $table->index('product_id');
                $table->index('product_quantity');
                $table->index('per_product_price');
                $table->index('coupon_discount');
                $table->index('	delivery_status');
            });

            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }


        try {
            $table_name  = 'order_tracks';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('order_id');
            });

            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }


        try {
            $table_name  = 'packages';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('user_id');
            });

            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'permissions';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('name');
                $table->index('is_special');
            });

            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'product_variants';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('product_id');
                $table->index('alix_variation_id');
                $table->index('variation_price');
                $table->index('variation_color');
                $table->index('variation_size');
                $table->index('variation_bundle');
                $table->index('variation_stock_quantity');
                $table->index('variation_photo');
            });

            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'ratings';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('user_id');
                $table->index('product_id');
            });

            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'subcategories';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('name');
                $table->index('slug');
                $table->index('category_id');
            });
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'user_notifications';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('user_id');
                $table->index('order_number');
            });

            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'vendor_information';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('shop_name');
                $table->index('slug');
                $table->index('shop_image');
                $table->index('status');
            });
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $table_name  = 'galleries';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('product_id');
            });
        } catch (\Throwable $th) {}

        try {
            $table_name  = 'products';
            echo "\n<br/><h3> Indexing column in $table_name </h3>";
            Schema::table($table_name, function (Blueprint $table) {
                $table->index('id');
                $table->index('user_id');
                $table->index('category_id');
                $table->index('subcategory_id');
                $table->index('childcategory_id');
                $table->index('brand_id');
                $table->index('slug');
                $table->index('status');
                $table->index('type');
                $table->index('thumbnail');
                $table->index('alix_variation_id');
                $table->index('da_product_id');
                $table->index('shipping_cost');
                $table->index('catalog_id');
            });
        } catch (\Throwable $th) {}
    }
}
