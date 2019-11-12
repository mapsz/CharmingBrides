<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaritialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maritials', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name', 35);
        });

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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maritials');
    }
}
