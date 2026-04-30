<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            if (! Schema::hasColumn('lessons', 'live_class_provider')) {
                $table->enum('live_class_provider', ['zoom', 'google_meet'])->nullable()->after('status');
            }

            if (! Schema::hasColumn('lessons', 'live_class_title')) {
                $table->string('live_class_title')->nullable()->after('live_class_provider');
            }

            if (! Schema::hasColumn('lessons', 'live_class_start_at')) {
                $table->dateTime('live_class_start_at')->nullable()->after('live_class_title');
            }

            if (! Schema::hasColumn('lessons', 'live_class_duration')) {
                $table->unsignedInteger('live_class_duration')->nullable()->after('live_class_start_at');
            }

            if (! Schema::hasColumn('lessons', 'live_class_meeting_url')) {
                $table->string('live_class_meeting_url')->nullable()->after('live_class_duration');
            }

            if (! Schema::hasColumn('lessons', 'live_class_meeting_code')) {
                $table->string('live_class_meeting_code')->nullable()->after('live_class_meeting_url');
            }

            if (! Schema::hasColumn('lessons', 'live_class_passcode')) {
                $table->string('live_class_passcode')->nullable()->after('live_class_meeting_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            foreach (['live_class_passcode', 'live_class_meeting_code', 'live_class_meeting_url', 'live_class_duration', 'live_class_start_at', 'live_class_title', 'live_class_provider'] as $column) {
                if (Schema::hasColumn('lessons', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
