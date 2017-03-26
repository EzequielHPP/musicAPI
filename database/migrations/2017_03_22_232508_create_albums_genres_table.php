<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums_genres', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('albums_id')->unsigned();
            $table->integer('genres_id')->unsigned();

            $table->foreign('albums_id')->references('id')->on('albums')->onDelete('cascade');
            $table->foreign('genres_id')->references('id')->on('genres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albums_genres');
    }
}
