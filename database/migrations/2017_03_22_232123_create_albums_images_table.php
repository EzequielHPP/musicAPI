<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('albums_id')->unsigned();
            $table->integer('images_id')->unsigned();

            $table->foreign('albums_id')->references('id')->on('albums')->onDelete('cascade');
            $table->foreign('images_id')->references('id')->on('images')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albums_images');
    }
}
