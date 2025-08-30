<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add waitlist and recurring fields to events
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('enable_waitlist')->default(false);
            $table->json('recurring_pattern')->nullable();
            $table->unsignedBigInteger('parent_event_id')->nullable();
            $table->boolean('is_template')->default(false);
            
            $table->foreign('parent_event_id')->references('id')->on('events')->onDelete('cascade');
        });

        // Create waitlist table
        Schema::create('event_waitlist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('event_id');
            $table->integer('position');
            $table->timestamp('joined_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unique(['user_id', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_waitlist');
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['parent_event_id']);
            $table->dropColumn(['enable_waitlist', 'recurring_pattern', 'parent_event_id', 'is_template']);
        });
    }
};