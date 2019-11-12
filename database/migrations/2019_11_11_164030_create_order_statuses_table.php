<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->tinyInteger('id');
            $table->char('name', 70)->nullable();
            $table->primary('id');
            $table->unique('id');
        });


        //Order Status
        DB::table('order_statuses')->insert([
          'id' => -1,
          'name' => 'Cancelled ',
        ]);          
        DB::table('order_statuses')->insert([
          'id' => 0,
          'name' => 'Failed',
        ]);        
        DB::table('order_statuses')->insert([
          'id' => 1,
          'name' => 'Completed',
        ]);
        DB::table('order_statuses')->insert([
          'id' => 2,
          'name' => 'Pending payment',
        ]);      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_statuses');
    }
}
