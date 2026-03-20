<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('radio_airplays', function (Blueprint $table) {
            $table->string('link')->nullable()->after('detail');
            $table->string('image')->nullable()->after('link');
            $table->string('thumbnail')->nullable()->after('image');
            $table->string('album_slug')->default('')->index()->after('thumbnail');
        });
    }

    public function down(): void
    {
        Schema::table('radio_airplays', function (Blueprint $table) {
            $table->dropIndex(['album_slug']);
            $table->dropColumn(['link', 'image', 'thumbnail', 'album_slug']);
        });
    }
};
