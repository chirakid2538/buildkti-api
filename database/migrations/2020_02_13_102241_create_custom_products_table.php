<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('custom_group_id');
            $table->string('name');
            $table->mediumText('description')->nullable()->default(NULL);
            $table->string('image')->nullable()->default(NULL);
            $table->boolean('active');
            $table->timestamps();

            $table->foreign('custom_group_id')
                ->references('id')->on('custom_groups')
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
        Schema::dropIfExists('custom_products');
    }
}
