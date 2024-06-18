<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('profile')->default('default.png');
            $table->string('banner')->default('default.png');
            $table->string('email')->unique();
            $table->string('provider')->default('web');
            $table->string('access_token')->nullable();
            $table->string('number')->unique()->nullable();
            $table->string('user_name')->unique()->nullable();
            $table->string('location')->nullable();
            $table->string('country')->nullable();
            $table->string('about')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_users');
    }
};
