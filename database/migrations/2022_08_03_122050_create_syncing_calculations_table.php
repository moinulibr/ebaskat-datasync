<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyncingCalculationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syncing_calculations', function (Blueprint $table) {
            $table->id();
            $table->string('product_from',50)->nullable();
            $table->integer('callable_no')->nullable()->comment('how many times to call this url');
            $table->integer('skipable')->default(0)->comment('skipable data: always start from this value');
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
        Schema::dropIfExists('syncing_calculations');
    }
}
