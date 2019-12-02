<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHiddenToGirls extends Migration
{
  public function up()
  {
    Schema::table('girls', function (Blueprint $table) {
      $table->tinyInteger('hidden')->default(0);
    });
  }

  public function down()
  {
    Schema::table('girls', function (Blueprint $table) {
      $table->dropColumn('hidden');
    });
  }
}
