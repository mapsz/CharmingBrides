<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLetterPaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_pays', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('letter_id');
            $table->foreign('letter_id')->references('id')->on('letters'); 
            $table->decimal('price', 8, 2);           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('letter_pays');
    }
}
