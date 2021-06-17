<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->longText('options')->nullable();
            $table->enum('type', ['free', 'option', 'rating'])->default('free');

            $table->integer('parent_id')->nullable();
            $table->integer('lft')->nullable();
            $table->integer('rgt')->nullable();
            $table->integer('depth')->nullable();

            $table->timestamps();
        });

        Schema::create('survey_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('answer')->nullable();
            $table->integer('rating')->nullable()->default(0);
            $table->integer('question_id')->index()->unsigned();
            $table->integer('user_id')->index()->unsigned();
            $table->timestamps();

            $table->foreign('question_id')
                ->references('id')
                ->on('survey_questions')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::create('module_survey', function (Blueprint $table) {
            $table->integer('module_id')->unsigned();
            $table->integer('survey_id')->unsigned();

            $table->primary(['module_id', 'survey_id']);

            $table->index(['module_id']);
            $table->foreign('module_id')
                ->references('id')
                ->on('modules')
                ->onDelete('cascade');

            $table->index(['survey_id']);
            $table->foreign('survey_id')
                ->references('id')
                ->on('survey_questions')
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
        Schema::dropIfExists('module_survey');
        Schema::dropIfExists('survey_answers');
        Schema::dropIfExists('survey_questions');
    }
}
