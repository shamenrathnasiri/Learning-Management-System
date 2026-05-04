<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_attempt_answers', function (Blueprint $table) {
            $table->unsignedSmallInteger('marks_obtained')->nullable()->after('is_correct');
            $table->text('tutor_feedback')->nullable()->after('marks_obtained');
            $table->timestamp('graded_at')->nullable()->after('tutor_feedback');
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete()->after('graded_at');
            $table->enum('status', ['pending', 'graded'])->default('graded')->after('graded_by');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_attempt_answers', function (Blueprint $table) {
            $table->dropForeign(['graded_by']);
            $table->dropColumn(['marks_obtained', 'tutor_feedback', 'graded_at', 'graded_by', 'status']);
        });
    }
};
