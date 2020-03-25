<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderCustomMocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_custom_mocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_custom_id');
            $table->unsignedBigInteger('custom_mock_id');

            $table->decimal('cost', 9, 2);
            $table->decimal('discount', 9, 2);
            $table->decimal('price', 9, 2);

            $table->string('image');
            $table->string('image_original');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('order_custom_id')->references('id')->on('order_customs')->onDelete('cascade');
            $table->foreign('custom_mock_id')->references('id')->on('custom_mocks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_custom_mocks');
    }
}
