<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('men', function (Blueprint $table) {            
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->decimal('balance', 8, 2);
            $table->foreign('user_id')->references('id')->on('users');
            $table->char('name', 35)->nullable();
            $table->char('surname', 35)->nullable();
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
        Schema::dropIfExists('men');
    }
}
