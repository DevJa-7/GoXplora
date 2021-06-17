<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('code')->nullable();
            $table->longText('extras')->nullable();
            $table->timestamps();
        });

        Schema::create('module_marker', function (Blueprint $table) {
            $table->integer('module_id')->unsigned();
            $table->integer('marker_id')->unsigned();

            $table->primary(['module_id', 'marker_id']);

            $table->index(['module_id']);
            $table->foreign('module_id')
                ->references('id')
                ->on('modules')
                ->onDelete('cascade');

            $table->index(['marker_id']);
            $table->foreign('marker_id')
                ->references('id')
                ->on('markers')
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
        Schema::dropIfExists('module_marker');
        Schema::dropIfExists('markers');
    }
}
