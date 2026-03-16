<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('album_slug')->after('id')->default('');
            $table->index('album_slug');
        });

        $jsonPath = resource_path('data/reviews.json');

        if (! file_exists($jsonPath)) {
            return;
        }

        $reviews = json_decode(file_get_contents($jsonPath), true);
        $now = now();

        foreach ($reviews as $i => $review) {
            DB::table('reviews')->insert([
                'album_slug'  => $review['album_slug'],
                'excerpt'     => $review['excerpt'],
                'body'        => $review['body'],
                'author'      => $review['author'],
                'publication' => $review['publication'] ?? '',
                'source_url'  => $review['source_url'],
                'sort_order'  => $i,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['album_slug']);
            $table->dropColumn('album_slug');
        });
    }
};
