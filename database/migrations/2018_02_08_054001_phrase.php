<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Phrase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phrases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('background_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('content',255);
            $table->string('fonts',255);
            $table->string('color_text',17);
                        $table->string('url_phrase');
                         $table->integer('approved')->unsigned();
            $table->integer('user_approved')->unsigned()->nullable();
            $table->datetime('date_approved')->nullable();
            $table->foreign('user_approved')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('background_id')->references('id')->on('backgrounds');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phrases');
    }
}
