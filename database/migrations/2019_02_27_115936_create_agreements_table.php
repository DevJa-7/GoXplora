<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->timestamps();
        });

        Schema::create('agreements_toggles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agreements_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->boolean('required')->default(0);

            $table->index(['agreements_id']);
            $table->foreign('agreements_id')
                ->references('id')
                ->on('agreements')
                ->onDelete('cascade');

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
        Schema::dropIfExists('agreements_toggles');
        Schema::dropIfExists('agreements');
    }
}
