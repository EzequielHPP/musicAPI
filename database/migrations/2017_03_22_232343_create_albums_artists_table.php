<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsArtistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums_artists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('artists_id')->unsigned();
            $table->integer('albums_id')->unsigned();

            $table->foreign('artists_id')->references('id')->on('artists')->onDelete('cascade');
            $table->foreign('albums_id')->references('id')->on('albums')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albums_artists');
    }
}
