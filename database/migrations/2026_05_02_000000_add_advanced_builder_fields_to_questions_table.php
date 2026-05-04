<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('quiz_id');
            $table->string('difficulty', 20)->default('medium')->after('type');
            $table->json('tags')->nullable()->after('difficulty');
            $table->string('media_path')->nullable()->after('tags');
            $table->string('media_type', 100)->nullable()->after('media_path');
            $table->string('media_name')->nullable()->after('media_type');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn([
                'sort_order',
                'difficulty',
                'tags',
                'media_path',
                'media_type',
                'media_name',
            ]);
        });
    }
};