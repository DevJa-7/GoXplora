<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGamification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Visited modules
        Schema::create('users_visited_modules', function (Blueprint $table) {
            $table->integer('module_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->primary(['module_id', 'user_id']);

            $table->index(['module_id']);
            $table->foreign('module_id')
                ->references('id')
                ->on('modules')
                ->onDelete('cascade');

            $table->index(['user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        // Game Questions
        Schema::create('game_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_id')->unsigned();
            $table->longText('title');
            $table->longText('content');
            $table->longText('images')->nullable();
            $table->longText('option_a');
            $table->longText('option_b');
            $table->longText('option_c');
            $table->longText('option_d');
            $table->enum('correct', [0, 1, 2, 3]);
            $table->timestamps();

            $table->index(['module_id']);
            $table->foreign('module_id')
                ->references('id')
                ->on('modules')
                ->onDelete('cascade');
        });

        // Game Answers
        Schema::create('game_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('question_id')->index()->unsigned();
            $table->integer('user_id')->index()->unsigned();
            $table->enum('answer', [0, 1, 2, 3]);
            $table->boolean('correct');
            $table->timestamps();

            $table->foreign('question_id')
                ->references('id')
                ->on('game_questions')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        // Ranking
        Schema::create('game_ranking', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index()->unsigned();
            $table->integer('score')->unsigned()->default(0);
            $table->integer('credits')->unsigned()->default(0);
            $table->integer('total_answers')->unsigned()->default(0);
            $table->integer('total_correct')->unsigned()->default(0);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('users_visited_modules');
        Schema::dropIfExists('game_answers');
        Schema::dropIfExists('game_questions');
        Schema::dropIfExists('game_ranking');
    }
}
