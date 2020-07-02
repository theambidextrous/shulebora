<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTsubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tsubjects', function (Blueprint $table) {
            $table->id();
            $table->string('teacher');
            $table->string('subject')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['teacher','subject']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tsubjects');
    }
}
