<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoordinatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coordinates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 127);
            $table->point('position')->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('radius')->unsigned()->default(0);
            $table->boolean('active', 1)->default(1);
            $table->timestamps();
        });

        Schema::create('module_coordinate', function (Blueprint $table) {
            $table->integer('module_id')->unsigned();
            $table->integer('coordinate_id')->unsigned();

            $table->primary(['module_id', 'coordinate_id']);

            $table->index(['module_id']);
            $table->foreign('module_id')
                ->references('id')
                ->on('modules')
                ->onDelete('cascade');

            $table->index(['coordinate_id']);
            $table->foreign('coordinate_id')
                ->references('id')
                ->on('coordinates')
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
        Schema::dropIfExists('module_coordinate');
        Schema::dropIfExists('coordinates');
    }
}
