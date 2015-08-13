<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentSentimentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commentSentiment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('joy');
            $table->integer('sadness');
            $table->integer('trust');
            $table->integer('disgust');
            $table->integer('fear');
            $table->integer('anger');
            $table->integer('surprise');
            $table->integer('anticipation');
            $table->string('result');
            $table->integer('comment_id')->unsigned()->nullable();

            $table->foreign('comment_id')
                  ->references('comment_id')
                  ->on('comment')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('commentSentiment');
    }
}
