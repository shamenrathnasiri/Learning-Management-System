<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            if (! Schema::hasColumn('lessons', 'course_id')) {
                $table->unsignedBigInteger('course_id')->nullable();
            }

            if (! Schema::hasColumn('lessons', 'module')) {
                $table->string('module')->nullable();
            }

            if (! Schema::hasColumn('lessons', 'video_path')) {
                $table->string('video_path')->nullable();
            }

            if (! Schema::hasColumn('lessons', 'attachment_paths')) {
                $table->json('attachment_paths')->nullable();
            }

            if (! Schema::hasColumn('lessons', 'duration')) {
                $table->unsignedInteger('duration')->nullable();
            }

            if (! Schema::hasColumn('lessons', 'release_date')) {
                $table->date('release_date')->nullable();
            }

            if (! Schema::hasColumn('lessons', 'status')) {
                $table->enum('status', ['draft', 'published'])->default('draft');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            if (Schema::hasColumn('lessons', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('lessons', 'release_date')) {
                $table->dropColumn('release_date');
            }

            if (Schema::hasColumn('lessons', 'duration')) {
                $table->dropColumn('duration');
            }

            if (Schema::hasColumn('lessons', 'attachment_paths')) {
                $table->dropColumn('attachment_paths');
            }

            if (Schema::hasColumn('lessons', 'video_path')) {
                $table->dropColumn('video_path');
            }

            if (Schema::hasColumn('lessons', 'module')) {
                $table->dropColumn('module');
            }

            if (Schema::hasColumn('lessons', 'course_id')) {
                $table->dropColumn('course_id');
            }
        });
    }
};
