<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderCustomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_customs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('custom_group_id');
            $table->unsignedBigInteger('custom_product_id');
            $table->unsignedBigInteger('custom_item_id');

            $table->decimal('cost', 9, 2);
            $table->decimal('discount', 9, 2);
            $table->decimal('price', 9, 2);
            $table->integer('amount');
            
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onDelete('cascade');
            $table->foreign('custom_group_id')
                ->references('id')->on('custom_groups');
            $table->foreign('custom_product_id')
                ->references('id')->on('custom_products');
            $table->foreign('custom_item_id')
                ->references('id')->on('custom_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_customs');
    }
}
