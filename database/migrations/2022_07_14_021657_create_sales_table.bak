<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('limit_number', 3)->nullable(true);
            $table->tinyInteger('rank', 1)->nullable(false);
            $table->tinyInteger('price', 11)->nullable(true);
            $table->integer('stocks_id', 20)->nullable(false);
            $table->timestamps();

            $table->foreign('stocks_id')->references('id')->on('stocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
