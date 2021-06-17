<?php

use App\Helpers\EnumHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->integer('country')
                ->unsigned()->nullable()->after('avatar');

            $table->foreign('country')
                ->references('id')
                ->on('countries')
                ->onDelete('cascade');

            $table->enum('gender', EnumHelper::values('general.gender'))
                ->nullable()->after('country');

            $table->string('phone', 32)
                ->nullable()->after('gender');

            $table->date('birth_date')
                ->nullable()->after('phone');

            $table->text('data')
                ->nullable()->after('birth_date');

            $table->string('api_token', 60)
                ->nullable()->unique()->after('remember_token');

            $table->boolean('guest')
                ->after('api_token')->default(0);

            $table->string('terms', 127)
                ->after('guest')->nullable();

            $table->boolean('status')
                ->after('terms')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_country_foreign');

            $table->dropColumn('country');
            $table->dropColumn('gender');
            $table->dropColumn('phone');
            $table->dropColumn('birth_date');
            $table->dropColumn('data');
            $table->dropColumn('api_token');
            $table->dropColumn('guest');
            $table->dropColumn('terms');
            $table->dropColumn('status');
        });
    }
}
