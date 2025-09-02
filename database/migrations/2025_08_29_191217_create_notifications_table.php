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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('info');
            $table->string('icon')->nullable();
            $table->json('data')->nullable(); // Additional data for the notification
            $table->string('action_url')->nullable(); // URL to redirect when clicked
            $table->string('action_text')->nullable(); // Text for action button
            $table->timestamp('expires_at')->nullable(); // When notification expires
            $table->boolean('is_global')->default(false); // Global notifications for all users
            $table->unsignedBigInteger('created_by')->nullable(); // Who created the notification
            $table->timestamps();

            // Foreign key for creator
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
