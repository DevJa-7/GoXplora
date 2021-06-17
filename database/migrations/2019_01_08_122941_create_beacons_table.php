<?php

use App\Helpers\EnumHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeaconsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beacons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference')->nullable();
            $table->integer('minor');
            $table->decimal('range', 5, 2)->default(0);
            $table->string('title');
            $table->string('description');
            $table->tinyInteger('battery')->default(100)->unsigned();
            $table->enum('local', EnumHelper::values('beacon.local'))->default('inside');
            $table->boolean('active', 1)->default(1);

            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('lft')->unsigned()->nullable();
            $table->integer('rgt')->unsigned()->nullable();
            $table->integer('depth')->unsigned()->nullable();

            $table->timestamps();
        });

        Schema::create('module_beacon', function (Blueprint $table) {
            $table->integer('module_id')->unsigned();
            $table->integer('beacon_id')->unsigned();

            $table->primary(['module_id', 'beacon_id']);

            $table->index(['module_id']);
            $table->foreign('module_id')
                ->references('id')
                ->on('modules')
                ->onDelete('cascade');

            $table->index(['beacon_id']);
            $table->foreign('beacon_id')
                ->references('id')
                ->on('beacons')
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
        Schema::dropIfExists('module_beacon');
        Schema::dropIfExists('beacons');
    }
}
