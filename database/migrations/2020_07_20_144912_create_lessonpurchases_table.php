<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonpurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessonpurchases', function (Blueprint $table) {
            $table->id();
            $table->string('orderid', 20)->unique();
            $table->string('buyer', 20);
            $table->string('lesson', 20);
            $table->string('cost', 20);
            $table->string('paid_amount', 20);
            $table->string('payref', 55)->unique();
            $table->boolean('paid')->default(false);
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
        Schema::dropIfExists('lessonpurchases');
    }
}
