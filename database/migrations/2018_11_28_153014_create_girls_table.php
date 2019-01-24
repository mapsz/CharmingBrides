<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGirlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('girls', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user');
            $table->foreign('user')->references('id')->on('users');
            $table->char('name', 35)->nullable();
            $table->date('birth')->nullable();
            $table->char('location', 35)->nullable();
            $table->unsignedTinyInteger('weight')->nullable();
            $table->unsignedTinyInteger('height')->nullable();
            $table->unsignedTinyInteger('hair')->nullable();
            $table->unsignedTinyInteger('eyes')->nullable();
            $table->unsignedTinyInteger('religion')->nullable();
            $table->unsignedTinyInteger('education')->nullable();
            $table->unsignedTinyInteger('maritial')->nullable();
            $table->tinyInteger('children')->nullable();
            $table->unsignedTinyInteger('smoking')->nullable();
            $table->unsignedTinyInteger('alcohol')->nullable();
            $table->unsignedTinyInteger('english')->nullable();
            $table->unsignedTinyInteger('prefferFrom')->nullable();
            $table->unsignedTinyInteger('prefferTo')->nullable();
            $table->string('info', 3500)->nullable();
            $table->string('firstLetterSubject', 255)->nullable();
            $table->string('firstLetter', 3500)->nullable();
            $table->char('forAdminName', 35)->nullable();
            $table->char('forAdminSurname', 35)->nullable();
            $table->char('forAdminFathersName', 35)->nullable();
            $table->char('forAdminPhoneNumber', 35)->nullable();
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
        Schema::dropIfExists('girls');
    }
}