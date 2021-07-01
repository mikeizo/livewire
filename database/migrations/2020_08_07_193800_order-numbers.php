<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderNumbers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_numbers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->char('phone', 15);
            $table->char('vanity', 15);
            $table->integer('area_code');
            $table->char('state', 3);
            $table->float('price', 10,2);
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
        Schema::dropIfExists('order_numbers');
    }
}
