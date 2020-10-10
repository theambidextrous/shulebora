<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLivesessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('livesessions', function (Blueprint $table) {
            $table->id();
            $table->text('subjects');
            $table->text('topics');
            $table->string('price', 5);
            $table->string('zoom_link', 255);
            $table->string('zoom_time', 255);
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
        Schema::dropIfExists('livesessions');
    }
}
