<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('created_by');
            $table->string('topic');
            $table->string('teacher');
            $table->string('type');/**live/recorded*/
            $table->string('sub_topic');
            $table->string('introduction');
            $table->text('file_content');
            $table->string('video_content');
            $table->string('audio_content');
            $table->string('zoom_link')->nullable();
            $table->string('zoom_time')->nullable();
            $table->string('zoom_help_note')->nullable();
            $table->boolean('is_paid');
            $table->boolean('is_active')->default(true);
            $table->integer('quorum')->default(0);
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
        Schema::dropIfExists('lessons');
    }
}
