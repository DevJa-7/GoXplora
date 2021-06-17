<?php

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->truncate();

        $settings = [
            [
                'key' => 'status',
                'name' => 'Expirience status',
                'description' => 'Expirience status',
                'value' => 1,
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Value',
                    'type' => 'checkbox',
                ]),
                'active' => 1,
            ],
            [
                'key' => 'login',
                'name' => 'Login',
                'description' => 'Login Enabled',
                'value' => 1,
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Value',
                    'type' => 'checkbox',
                ]),
                'active' => 1,
            ],
            [
                'key' => 'login_guest',
                'name' => 'Login Guest',
                'description' => 'Guest Login Enabled',
                'value' => 1,
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Value',
                    'type' => 'checkbox',
                ]),
                'active' => 1,
            ],
            [
                'key' => 'offline',
                'name' => 'Offline availability',
                'description' => 'Contents available offline',
                'value' => 1,
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Value',
                    'type' => 'checkbox',
                ]),
                'active' => 1,
            ],
            [
                'key' => 'contact',
                'name' => 'Contact',
                'description' => 'Contact Email',
                'value' => 'geral@mail.com',
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Value',
                    'type' => 'email',
                ]),
                'active' => 1,
            ],
            [
                'key' => 'initial_image',
                'name' => 'Initital Image',
                'description' => 'Initital Image',
                'value' => '/uploads/app/init.jpg',
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Value',
                    'type' => 'browse',
                ]),
                'active' => 1,
            ],
            [
                'key' => 'bluetooth_status',
                'name' => 'Bluetooth',
                'description' => 'Bluetooth available',
                'value' => 1,
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Value',
                    'type' => 'checkbox',
                ]),
                'active' => 1,
            ],
            [
                'key' => 'gps_status',
                'name' => 'GPS',
                'description' => 'GPS available',
                'value' => 1,
                'field' => json_encode([
                    'name' => 'value',
                    'label' => 'Value',
                    'type' => 'checkbox',
                ]),
                'active' => 1,
            ],
        ];

        foreach ($settings as $index => $setting) {
            $result = DB::table('settings')->insert($setting);

            if (!$result) {
                $this->command->info("Insert failed at record $index.");
                return;
            }
        }
    }
}
