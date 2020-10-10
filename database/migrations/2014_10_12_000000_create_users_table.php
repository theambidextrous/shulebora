<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            /** profile */
            $table->string('phone')->unique()->nullable();
            $table->string('gender')->nullable();
            $table->string('school')->nullable();
            $table->string('group')->nullable();/** high=2/primary=1/cbc/college */
            $table->string('level')->nullable();
            $table->boolean('has_profile')->default(false);
            /** perm */
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_teacher')->default(false);
            $table->boolean('is_learner')->default(true);
            /** pay */
            $table->boolean('is_paid')->default(false);
            $table->boolean('can_access_lesson')->default(false);
            /** end */
            $table->boolean('is_active')->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
