<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['user', 'guess']);
            $table->bigInteger('user_id');
            $table->integer('count');
            $table->decimal('cost', 9, 2);
            $table->decimal('discount', 9, 2);
            $table->decimal('price', 9, 2);
            $table->string('remark')->nullable();
            $table->string('state',50);
            $table->softDeletes();
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
        Schema::dropIfExists('orders');
    }
}
