<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('tweet_id');
            $table->text('tweet_url')->nullable();
            $table->text('text');
            $table->integer('quoted')->nullable();
            $table->string('twitter_user');
            $table->string('twitter_user_name');
            $table->string('twitter_user_id');
            $table->string('twitter_user_image')->nullable();
            $table->text('tweet_media')->nullable();
            $table->datetime('tweet_created_at');
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
        Schema::dropIfExists('tweets');
    }
}
