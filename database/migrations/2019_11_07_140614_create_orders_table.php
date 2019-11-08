<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');            
            $table->foreign('user_id')->references('id')->on('users');
            $table->char('name', 70);
            $table->char('category', 70);
            $table->char('product_id', 70);
            $table->char('method', 70);
            $table->char('transaction', 80)->nullable();
            $table->unsignedTinyInteger('status')->nullable();
            $table->decimal('price', 8, 2)->nullable();
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
        Schema::dropIfExists('orders');
    }
}
