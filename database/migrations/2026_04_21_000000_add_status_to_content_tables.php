<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables that should gain the publish-workflow status column.
     */
    private const TABLES = [
        'news',
        'gallery_images',
        'radio_airplays',
        'reviews',
        'press_events',
        'press_images',
    ];

    public function up(): void
    {
        foreach (self::TABLES as $table) {
            if (! Schema::hasTable($table) || Schema::hasColumn($table, 'status')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->string('status', 20)->default('draft')->after('id');
                $blueprint->index('status');
            });

            // Existing content is considered already published; new content will
            // default to "draft" going forward.
            DB::table($table)->update(['status' => 'published']);
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'status')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                $blueprint->dropIndex([$table.'_status_index']);
                $blueprint->dropColumn('status');
            });
        }
    }
};
