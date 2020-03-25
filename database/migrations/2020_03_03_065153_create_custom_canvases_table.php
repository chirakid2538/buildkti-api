<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomCanvasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_canvases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('custom_group_id');
            $table->longText('widthJson');
            $table->longText('heightJson');
            $table->longText('tierJson');

            $table->softDeletes();
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
        Schema::dropIfExists('custom_canvases');
    }
}
