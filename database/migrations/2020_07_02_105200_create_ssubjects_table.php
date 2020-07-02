<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ssubjects', function (Blueprint $table) {
            $table->id();
            $table->string('student');
            $table->string('subject');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['student','subject']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ssubjects');
    }
}
