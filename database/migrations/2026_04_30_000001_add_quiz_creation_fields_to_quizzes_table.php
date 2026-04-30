<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            if (! Schema::hasColumn('quizzes', 'course_id')) {
                $table->unsignedBigInteger('course_id')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('quizzes', 'instructions')) {
                $table->text('instructions')->nullable()->after('description');
            }

            if (! Schema::hasColumn('quizzes', 'time_limit_minutes')) {
                $table->unsignedInteger('time_limit_minutes')->nullable()->after('passing_score');
            }

            if (! Schema::hasColumn('quizzes', 'total_marks')) {
                $table->unsignedInteger('total_marks')->default(100)->after('time_limit_minutes');
            }
        });

        DB::statement('ALTER TABLE quizzes MODIFY lesson_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE quizzes MODIFY lesson_id BIGINT UNSIGNED NOT NULL');

        Schema::table('quizzes', function (Blueprint $table) {
            if (Schema::hasColumn('quizzes', 'total_marks')) {
                $table->dropColumn('total_marks');
            }

            if (Schema::hasColumn('quizzes', 'time_limit_minutes')) {
                $table->dropColumn('time_limit_minutes');
            }

            if (Schema::hasColumn('quizzes', 'instructions')) {
                $table->dropColumn('instructions');
            }

            if (Schema::hasColumn('quizzes', 'course_id')) {
                $table->dropColumn('course_id');
            }
        });
    }
};
