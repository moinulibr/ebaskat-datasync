<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBigbuyEnumParameterInTypeFieldToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            \DB::statement("ALTER TABLE `products` CHANGE `type` `type` ENUM('Physical','Digital','License','Aliexpress','Bigbuy') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
            //\DB::statement("ALTER TABLE `products` CHANGE `type` `type` ENUM('Physical','Digital','License','Aliexpress','') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal';");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
}
