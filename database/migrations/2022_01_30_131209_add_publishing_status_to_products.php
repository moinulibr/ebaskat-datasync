<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublishingStatusToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('products')){
            Schema::table('products', function (Blueprint $table) {
                $table->tinyInteger('pub_status')->default(0)->after('status')->comment('publishing status');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(!Schema::hasTable('products')){
            Schema::table('products', function (Blueprint $table) {
                //
            });
        }
    }
}
