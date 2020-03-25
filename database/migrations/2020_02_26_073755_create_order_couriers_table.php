<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderCouriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_couriers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('courier_id');
            $table->string('code', 20);
            $table->string('tracking_code',100)->nullable()->default(NULL);
            $table->string('courier_tracking_code',100)->nullable()->default(NULL);
            $table->decimal('cost', 9, 2);
            $table->decimal('price', 9, 2);
            $table->string('state', 50);
            $table->dateTime('datetime_booking')->nullable()->default(NULL);
            $table->dateTime('datetime_confirm')->nullable()->default(NULL);
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onDelete('cascade');
            $table->foreign('courier_id')
                ->references('id')->on('couriers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_couriers');
    }
}
