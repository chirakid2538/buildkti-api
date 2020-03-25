<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomMocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_mocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('custom_group_id');
            $table->unsignedBigInteger('custom_product_id');
            $table->unsignedBigInteger('custom_item_id');

            $table->string('name');
            $table->mediumText('description')->nullable()->default(NULL);
            $table->string('image')->nullable()->default(NULL);
            $table->decimal('cost', 9, 2);
            $table->decimal('price', 9, 2);
            $table->boolean('active');


            $table->foreign('custom_group_id')
                ->references('id')->on('custom_groups')
                ->onDelete('cascade');
            $table->foreign('custom_product_id')
                ->references('id')->on('custom_products')
                ->onDelete('cascade');
            $table->foreign('custom_item_id')
                ->references('id')->on('custom_items')
                ->onDelete('cascade');
            
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
        Schema::dropIfExists('custom_mocks');
    }
}
