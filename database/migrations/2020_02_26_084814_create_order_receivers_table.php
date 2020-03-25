<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderReceiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_receivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');

            $table->string('name');
            $table->string('address');
            $table->string('subDistrict')->nullable()->default(NULL);
            $table->string('district')->nullable()->default(NULL);
            $table->string('province')->nullable()->default(NULL);
            $table->string('postcode');
            $table->string('phone');
            $table->string('email')->nullable()->default(NULL);
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_receivers');
    }
}
