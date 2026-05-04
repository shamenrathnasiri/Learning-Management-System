<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            if (! Schema::hasColumn('quizzes', 'starts_at')) {
                $table->dateTime('starts_at')->nullable()->after('time_limit_minutes');
            }

            if (! Schema::hasColumn('quizzes', 'ends_at')) {
                $table->dateTime('ends_at')->nullable()->after('starts_at');
            }

            if (! Schema::hasColumn('quizzes', 'restrict_to_enrolled_students')) {
                $table->boolean('restrict_to_enrolled_students')->default(false)->after('ends_at');
            }

            if (! Schema::hasColumn('quizzes', 'auto_submit_on_expiry')) {
                $table->boolean('auto_submit_on_expiry')->default(true)->after('restrict_to_enrolled_students');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $columns = [
                'starts_at',
                'ends_at',
                'restrict_to_enrolled_students',
                'auto_submit_on_expiry',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('quizzes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
