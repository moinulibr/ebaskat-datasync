<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_package_id');
            $table->bigInteger('product_id');
            $table->integer('product_quantity');
            $table->double('per_product_price');
            $table->double('per_product_commission');
            $table->double('per_product_discount');
            $table->double('coupon_discount');
            $table->string('product_status');
            $table->string('delivery_status')->nullable();
            $table->string('cart');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_products');
    }
}
