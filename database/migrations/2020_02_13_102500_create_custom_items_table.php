<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('custom_group_id');
            $table->unsignedBigInteger('custom_product_id');

            $table->string('name');
            $table->decimal('cost', 9, 2);
            $table->decimal('price', 9, 2);
            $table->mediumText('description')->nullable()->default(NULL);
            $table->string('image')->nullable()->default(NULL);
            $table->boolean('active');

            $table->timestamps();

            $table->foreign('custom_group_id')
                ->references('id')->on('custom_groups')
                ->onDelete('cascade');
            $table->foreign('custom_product_id')
                ->references('id')->on('custom_products')
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
        Schema::dropIfExists('custom_items');
    }
}
