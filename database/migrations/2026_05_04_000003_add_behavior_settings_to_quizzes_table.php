<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            if (! Schema::hasColumn('quizzes', 'shuffle_questions')) {
                $table->boolean('shuffle_questions')->default(false)->after('passing_score');
            }

            if (! Schema::hasColumn('quizzes', 'shuffle_answers')) {
                $table->boolean('shuffle_answers')->default(false)->after('shuffle_questions');
            }

            if (! Schema::hasColumn('quizzes', 'max_attempts')) {
                $table->unsignedInteger('max_attempts')->nullable()->after('shuffle_answers');
            }

            if (! Schema::hasColumn('quizzes', 'result_visibility')) {
                $table->string('result_visibility', 40)->default('immediate')->after('max_attempts');
            }

            if (! Schema::hasColumn('quizzes', 'show_correct_answers')) {
                $table->boolean('show_correct_answers')->default(true)->after('result_visibility');
            }

            if (! Schema::hasColumn('quizzes', 'show_explanations')) {
                $table->boolean('show_explanations')->default(true)->after('show_correct_answers');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $columns = [
                'shuffle_questions',
                'shuffle_answers',
                'max_attempts',
                'result_visibility',
                'show_correct_answers',
                'show_explanations',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('quizzes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
