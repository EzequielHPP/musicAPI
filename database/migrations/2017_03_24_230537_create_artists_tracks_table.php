<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtistsTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artists_tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('artists_id')->unsigned();
            $table->integer('tracks_id')->unsigned();

            $table->foreign('artists_id')->references('id')->on('artists')->onDelete('cascade');
            $table->foreign('tracks_id')->references('id')->on('tracks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('artists_tracks');
    }
}
