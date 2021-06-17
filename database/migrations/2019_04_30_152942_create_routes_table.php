<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->decimal('bounding_top', 10, 8)->nullable();
            $table->decimal('bounding_right', 10, 8)->nullable();
            $table->decimal('bounding_bottom', 10, 8)->nullable();
            $table->decimal('bounding_left', 10, 8)->nullable();
            $table->timestamps();
        });

        Schema::create('module_route', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('route_id')->unsigned();
            $table->integer('module_id')->unsigned();

            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('lft')->unsigned()->nullable();
            $table->integer('rgt')->unsigned()->nullable();
            $table->integer('depth')->unsigned()->nullable();

            $table->unique(['module_id', 'route_id']);

            $table->index(['module_id']);
            $table->foreign('module_id')
                ->references('id')
                ->on('modules')
                ->onDelete('cascade');

            $table->index(['route_id']);
            $table->foreign('route_id')
                ->references('id')
                ->on('routes')
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
        Schema::dropIfExists('module_route');
        Schema::dropIfExists('routes');
    }
}
