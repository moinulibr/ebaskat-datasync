<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;

class TextToVarchar
{
    public function __construct()
    {
        DB::statement('ALTER TABLE `admins` CHANGE COLUMN `shop_name` `shop_name` VARCHAR(100) NULL;');

        DB::statement('ALTER TABLE `admin_user_conversations` CHANGE COLUMN `order_number` `order_number` VARCHAR(200) NULL;');

        DB::statement('ALTER TABLE `admin_user_messages` CHANGE COLUMN `files` `files` VARCHAR(200) NULL;');

        DB::statement('ALTER TABLE `brands` CHANGE COLUMN `email` `email` VARCHAR(100) NULL;');
        DB::statement('ALTER TABLE `email_templates` CHANGE COLUMN `email_subject` `email_subject` VARCHAR(300) NULL;');

        DB::statement('ALTER TABLE `orders` CHANGE COLUMN `pay_id` `pay_id` VARCHAR(150) NULL;');

        DB::statement('ALTER TABLE `order_tracks` CHANGE COLUMN `title` `title` VARCHAR(200) NULL;');

        DB::statement('ALTER TABLE `packages` CHANGE COLUMN `title` `title` VARCHAR(200) NULL;');

        DB::statement('ALTER TABLE `products` CHANGE COLUMN `alix_variation_id` `alix_variation_id` VARCHAR(250) NULL;');
        DB::statement('ALTER TABLE `products` CHANGE COLUMN `link` `link` VARCHAR(500) NULL;');
        DB::statement('ALTER TABLE `products` CHANGE COLUMN `discount_date` `discount_date` VARCHAR(100) NULL;');
        DB::statement('ALTER TABLE `products` CHANGE COLUMN `whole_sell_qty` `whole_sell_qty` INT NULL;');
        DB::statement('ALTER TABLE `products` CHANGE COLUMN `whole_sell_discount` `whole_sell_discount` FLOAT(8,2) NULL;');

        DB::statement('ALTER TABLE `product_variants` CHANGE COLUMN `variation_dimension` `variation_dimension` VARCHAR(250) NULL;');
        DB::statement('ALTER TABLE `product_variants` CHANGE COLUMN `variation_photo` `variation_photo` VARCHAR(250) NULL;');

        DB::statement('ALTER TABLE `reports` CHANGE COLUMN `title` `title` VARCHAR(300) NULL;');

        DB::statement('ALTER TABLE `shippings` CHANGE COLUMN `title` `title` VARCHAR(300) NULL;');
        DB::statement('ALTER TABLE `shippings` CHANGE COLUMN `subtitle` `subtitle` VARCHAR(500) NULL;');

        DB::statement('ALTER TABLE `sliders` CHANGE COLUMN `link` `link` VARCHAR(500) NULL;');

        DB::statement('ALTER TABLE `subscriptions` CHANGE COLUMN `title` `title` VARCHAR(300) NOT NULL;');

        DB::statement('ALTER TABLE `users` CHANGE COLUMN `verification_link` `verification_link` VARCHAR(500) NULL;');
        DB::statement('ALTER TABLE `users` CHANGE COLUMN `f_url` `f_url` VARCHAR(500) NULL;');
        DB::statement('ALTER TABLE `users` CHANGE COLUMN `g_url` `g_url` VARCHAR(500) NULL;');
        DB::statement('ALTER TABLE `users` CHANGE COLUMN `t_url` `t_url` VARCHAR(500) NULL;');
        DB::statement('ALTER TABLE `users` CHANGE COLUMN `l_url` `l_url` VARCHAR(500) NULL;');

        DB::statement('ALTER TABLE `user_notifications` CHANGE COLUMN `order_number` `order_number` VARCHAR(300) NOT NULL;');
        
        DB::statement('ALTER TABLE `user_subscriptions` CHANGE COLUMN `title` `title` VARCHAR(300) NOT NULL;');
        DB::statement('ALTER TABLE `user_subscriptions` CHANGE COLUMN `payment_number` `payment_number` VARCHAR(350) NULL;');

        DB::statement('ALTER TABLE `vendor_information` CHANGE COLUMN `shop_image` `shop_image` VARCHAR(300) NULL;');
        DB::statement('ALTER TABLE `vendor_information` CHANGE COLUMN `attachments` `attachments` VARCHAR(300) NULL;');
    }
}