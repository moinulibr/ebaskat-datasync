<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('alix_variation_id')->nullable();
            $table->float('variation_price',20,2)->default(0);
            $table->float('variation_sale_price',20,2)->default(0);
            $table->integer('variation_stock_quantity')->default(0);
            $table->string('variation_stock_status')->nullable();
            $table->text('attributes')->nullable();
            $table->text('variation_dimension')->nullable();
            $table->text('variation_photo')->nullable();
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
        Schema::dropIfExists('product_variants');
    }
}
