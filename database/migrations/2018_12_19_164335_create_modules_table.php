<?php

use App\Helpers\EnumHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', EnumHelper::values('module.type'));
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('reference')->nullable();
            $table->string('title');
            $table->string('slug', 127)->unique()->nullable();
            $table->text('content');
            $table->string('image')->nullable();
            $table->string('image_title')->nullable();
            $table->text('images')->nullable();
            $table->text('images360')->nullable();
            $table->text('videos')->nullable();
            $table->text('audios')->nullable();
            $table->text('documents')->nullable();
            $table->text('models_3d')->nullable();
            $table->enum('status', EnumHelper::values('general.publish'));
            $table->boolean('featured')->default(0);
            $table->boolean('ra_range')->default(0);
            $table->text('extras')->nullable();
            $table->text('extras_translatable')->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('lft')->unsigned()->nullable();
            $table->integer('rgt')->unsigned()->nullable();
            $table->integer('depth')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id']);
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('set null');
        });

        Schema::create('module_related', function (Blueprint $table) {
            $table->integer('module_id')->unsigned();
            $table->integer('related_id')->unsigned();

            $table->primary(['module_id', 'related_id']);

            $table->index(['module_id']);
            $table->foreign('module_id')
                ->references('id')
                ->on('modules')
                ->onDelete('cascade');

            $table->index(['related_id']);
            $table->foreign('related_id')
                ->references('id')
                ->on('modules')
                ->onDelete('cascade');
        });

        Schema::create('module_children', function (Blueprint $table) {
            $table->integer('module_id')->unsigned();
            $table->integer('child_id')->unsigned();

            $table->primary(['module_id', 'child_id']);

            $table->index(['module_id']);
            $table->foreign('module_id')
                ->references('id')
                ->on('modules')
                ->onDelete('cascade');

            $table->index(['child_id']);
            $table->foreign('child_id')
                ->references('id')
                ->on('modules')
                ->onDelete('cascade');
        });

        Schema::create('module_tag', function (Blueprint $table) {
            $table->integer('module_id')->unsigned();
            $table->integer('tag_id')->unsigned();

            $table->primary(['module_id', 'tag_id']);

            $table->index(['module_id']);
            $table->foreign('module_id')
                ->references('id')
                ->on('modules')
                ->onDelete('cascade');

            $table->index(['tag_id']);
            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')
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
        Schema::dropIfExists('module_related');
        Schema::dropIfExists('module_children');
        Schema::dropIfExists('module_tag');
        Schema::dropIfExists('modules');
    }
}
