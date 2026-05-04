<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            if (! Schema::hasColumn('quizzes', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('auto_submit_on_expiry');
            }

            if (! Schema::hasColumn('quizzes', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('is_published');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            if (Schema::hasColumn('quizzes', 'published_at')) {
                $table->dropColumn('published_at');
            }

            if (Schema::hasColumn('quizzes', 'is_published')) {
                $table->dropColumn('is_published');
            }
        });
    }
};
