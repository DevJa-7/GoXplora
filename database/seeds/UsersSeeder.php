<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('users')->insert([                        
            'name' => 'Jack',
            'email' => 'wang525525@gmail.com',
            'password' => bcrypt('admin1234'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Super Admin Role
        DB::table('model_has_roles')->insert([
            ['role_id' => 1, 'model_type' => 'App\User', 'model_id' => 1, 'model_id' => 1],
        ]);
    }
}
