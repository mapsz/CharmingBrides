<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentGirlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_girl', function (Blueprint $table) {
            $table->unsignedInteger('agent_id');            
            $table->unsignedInteger('girl_id');
            $table->foreign('agent_id')->references('id')->on('agents');
            $table->foreign('girl_id')->references('id')->on('girls');
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
        Schema::dropIfExists('agent_girl');
    }
}
