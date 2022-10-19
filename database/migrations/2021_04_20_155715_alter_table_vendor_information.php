<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableVendorInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_information', function (Blueprint $table) {
            $table->string('shop_name')->nullable()->after('user_id');
            $table->string('owner_name')->nullable()->after('shop_name');
            $table->string('shop_number')->nullable()->after('owner_name');
            $table->string('shop_address')->nullable()->after('shop_number');
            $table->string('reg_number')->nullable()->after('shop_address');
            $table->string('shop_message')->nullable()->after('reg_number');
            $table->string('shop_details')->nullable()->after('shop_message');
            $table->text('shop_image')->nullable()->after('shop_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_information', function (Blueprint $table) {
            $table->dropColumn('shop_name');
            $table->dropColumn('owner_name');
            $table->dropColumn('shop_number');
            $table->dropColumn('shop_address');
            $table->dropColumn('reg_number');
            $table->dropColumn('shop_message');
            $table->dropColumn('shop_details');
            $table->dropColumn('shop_image');
        });
    }
}
