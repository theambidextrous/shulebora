<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaylogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paylogs', function (Blueprint $table) {
            $table->id();
            $table->string('order', 30)->nullable();
            $table->string('buyer', 30)->nullable();
            $table->string('payer', 55)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('payref', 55)->nullable();
            $table->string('amount', 30)->nullable();
            $table->string('time', 30)->nullable();
            $table->string('method', 30)->nullable();
            $table->text('paystring')->nullable();
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
        Schema::dropIfExists('paylogs');
    }
}
