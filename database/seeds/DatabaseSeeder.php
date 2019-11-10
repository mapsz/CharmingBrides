<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(SettingsLetterLongLength::class);
        

        //Maritial
        DB::table('maritials')->insert([
          'id' => 1,
          'name' => 'Never married',
        ]);
        DB::table('maritials')->insert([
          'id' => 2,
          'name' => 'Divorced',
        ]);
        DB::table('maritials')->insert([
          'id' => 3,
          'name' => 'Widowed',
        ]);
        DB::table('maritials')->insert([
          'id' => 0,
          'name' => 'Other',
        ]);

    }
}
