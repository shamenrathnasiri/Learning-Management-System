<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('type')->default('mcq')->after('quiz_id');
            $table->unsignedSmallInteger('marks')->default(1)->after('correct_option');
            $table->text('explanation')->nullable()->after('marks');
            $table->text('correct_answer')->nullable()->after('explanation');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['type', 'marks', 'explanation', 'correct_answer']);
        });
    }
};
