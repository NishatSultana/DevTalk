<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{

    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('user_id');
            $table->text('body');
            $table->integer('votes_count')->default(0);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
