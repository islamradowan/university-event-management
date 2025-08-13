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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable()->unique();
            $table->text('description')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->string('location')->nullable();
            $table->string('category')->nullable();
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->unsignedBigInteger('organizer_id')->nullable();
            $table->string('poster_path')->nullable();
            $table->integer('capacity')->nullable();
            $table->boolean('featured')->default(false);
            $table->timestamps();

            $table->foreign('organizer_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
