<?php

use Illuminate\Database\Seeder;
use App\Settings;

class SettingsLetterLongLength extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::create(
          [
            'name' => 'LetterLongLength',
            'value' => 3500,
          ]

        );


        // DB::table('settings')->insert([
        //     'name' => 'LetterLongLength',
        //     'value' => 3500,
        // ]);
    }
}
