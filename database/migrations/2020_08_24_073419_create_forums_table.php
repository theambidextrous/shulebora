<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('forums', function (Blueprint $table) {
            $table->id();
            $table->string('asked_by', 9);
            $table->string('answered_by', 9)->nullable();
            $table->string('subject', 9);
            $table->string('topic', 9);
            $table->text('question');
            $table->text('answer')->nullable();
            $table->string('q_image')->nullable();
            $table->string('a_image')->nullable();
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
        Schema::dropIfExists('forums');
    }
}
