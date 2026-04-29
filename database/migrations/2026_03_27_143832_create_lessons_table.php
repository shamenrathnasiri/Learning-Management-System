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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('module')->nullable();
            $table->text('description');
            $table->string('thumbnail_path')->nullable();
            $table->enum('content_type', ['text', 'video', 'file'])->default('text');
            $table->longText('content')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_path')->nullable();
            $table->string('attachment_path')->nullable();
            $table->json('attachment_paths')->nullable();
            $table->unsignedInteger('duration')->nullable();
            $table->date('release_date')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
