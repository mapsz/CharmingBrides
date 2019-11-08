<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('from_id');
            $table->foreign('from_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('to_id');
            $table->foreign('to_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('from_confirmed')->default(0);
            $table->boolean('to_confirmed')->default(0);
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
        Schema::dropIfExists('signs');
    }
}
