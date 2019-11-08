<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfoToMen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('men', function (Blueprint $table) {

        //Info
        $table->char('country', 35)->nullable();
        $table->char('city', 35)->nullable();        
        $table->char('phoneNumber', 35)->nullable();
        $table->date('birth')->nullable();
        $table->unsignedTinyInteger('education')->nullable();
        $table->string('proffesion', 55)->nullable();
        $table->tinyInteger('children')->nullable();
        $table->unsignedTinyInteger('smoking')->nullable();
        $table->unsignedTinyInteger('alcohol')->nullable();
        $table->text('info')->nullable();
        $table->unsignedTinyInteger('prefferFrom')->nullable();
        $table->unsignedTinyInteger('prefferTo')->nullable();
        $table->unsignedTinyInteger('hair')->nullable();
        $table->unsignedTinyInteger('maritial')->nullable();
        $table->unsignedTinyInteger('eyes')->nullable();
        $table->unsignedTinyInteger('religion')->nullable();
        $table->unsignedTinyInteger('weight')->nullable();
        $table->unsignedTinyInteger('height')->nullable();


        //Girl info
        $table->unsignedTinyInteger('girlHair')->nullable();
        $table->unsignedTinyInteger('girlWeightFrom')->nullable();
        $table->unsignedTinyInteger('girlWeightTo')->nullable();
        $table->unsignedTinyInteger('girlHeightFrom')->nullable();
        $table->unsignedTinyInteger('girlHeightTo')->nullable();
        $table->unsignedTinyInteger('girlSmoking')->nullable();
        $table->unsignedTinyInteger('girlDrink')->nullable();
        $table->unsignedTinyInteger('girlEducation')->nullable();
        $table->string('girlProffesion', 55)->nullable();
        $table->unsignedTinyInteger('girlMaritial')->nullable();
        $table->unsignedTinyInteger('girlChildren')->nullable();
        $table->text('girlInfo')->nullable();

      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('men', function (Blueprint $table) {
        //Info
        $table->dropColumn('country');
        $table->dropColumn('city');
        $table->dropColumn('phoneNumber');
        $table->dropColumn('education');
        $table->dropColumn('proffesion');
        $table->dropColumn('children');
        $table->dropColumn('smoking');
        $table->dropColumn('alcohol');
        $table->dropColumn('info');
        $table->dropColumn('prefferFrom');
        $table->dropColumn('prefferTo');
        $table->dropColumn('hair');
        $table->dropColumn('maritial');
        $table->dropColumn('eyes');
        $table->dropColumn('religion');
        $table->dropColumn('weight');
        $table->dropColumn('height');

        //Girl info
        $table->dropColumn('girlHair');
        $table->dropColumn('girlWeightFrom');
        $table->dropColumn('girlWeightTo');
        $table->dropColumn('girlHeightFrom');
        $table->dropColumn('girlHeightTo');
        $table->dropColumn('girlSmoking');
        $table->dropColumn('girlDrink');
        $table->dropColumn('girlEducation');
        $table->dropColumn('girlProffesion');
        $table->dropColumn('girlMaritial');
        $table->dropColumn('girlChildren');
        $table->dropColumn('girlInfo');
      });
    }
}
