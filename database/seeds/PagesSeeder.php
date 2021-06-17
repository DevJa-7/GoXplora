<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = Carbon::now();

        DB::table('pages')->truncate();

        DB::table('pages')->insert([
            [
                'title' => json_encode([
                    'pt' => 'Intro',
                    'en' => 'Intro',
                ]),
                'template' => 'intro',
                'name' => 'Intro',
                'slug' => 'intro',
                'extras' => null,
                'extras_translatable' => json_encode([
                    'pt' => ['intro_title' => 'Introdução', 'intro_text' => 'Introdução'],
                    'en' => ['intro_title' => 'Intro', 'intro_text' => 'Intro'],
                ]),
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => json_encode([
                    'pt' => 'Contactos',
                    'en' => 'Contacts',
                ]),
                'template' => 'contacts',
                'name' => 'Contacts',
                'slug' => 'contacts',
                'extras' => null,
                'extras_translatable' => json_encode([
                    'pt' => ['contact_title' => 'Contactos', 'contact_text' => 'Contactos'],
                    'en' => ['contact_title' => 'Contacts', 'contact_text' => 'Contacts'],
                ]),
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => json_encode([
                    'pt' => 'Contratos',
                    'en' => 'Agreement',
                ]),
                'template' => 'agreement',
                'name' => 'Agreement',
                'slug' => 'agreement',
                'extras' => null,
                'extras_translatable' => json_encode([
                    'pt' => ['agreement_title' => 'Contratos', 'agreement_text' => 'Contratos'],
                    'en' => ['agreement_title' => 'Agreement', 'agreement_text' => 'Agreement'],
                ]),
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => json_encode([
                    'pt' => 'História',
                    'en' => 'History',
                ]),
                'template' => 'history',
                'name' => 'History',
                'slug' => 'history',
                'extras' => null,
                'extras_translatable' => json_encode([
                    'pt' => [],
                    'en' => [],
                ]),
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'title' => json_encode([
                    'pt' => 'Tutorial',
                    'en' => 'Tutorial',
                ]),
                'template' => 'tutorial',
                'name' => 'Tutorial',
                'slug' => 'tutorial',
                'extras' => null,
                'extras_translatable' => json_encode([
                    'pt' => [],
                    'en' => [],
                ]),
                'created_at' => $date,
                'updated_at' => $date,
            ],
        ]);
    }
}
