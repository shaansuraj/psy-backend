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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('by_user_id'); // User who reported
            $table->unsignedBigInteger('user_id'); // User who owns the report
            $table->unsignedBigInteger('reported_item_id'); // ID of the reported item (post, comment, reply)
            $table->string('reported_item_type'); // Type of the reported item (post, comment, reply)
            $table->string('reason'); // Reason for the report
            $table->string('status')->default('pending'); // Status of the report (pending, resolved)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
