<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Models\News;
use App\Models\PressEvent;
use App\Models\Quote;
use App\Models\RadioAirplay;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        return response()->json([
            'news' => News::count(),
            'reviews' => Review::count(),
            'radio_airplays' => RadioAirplay::count(),
            'press_events' => PressEvent::count(),
            'quotes' => Quote::count(),
            'gallery_images' => GalleryImage::count(),
        ]);
    }

    public function recent(): JsonResponse
    {
        $items = collect();

        $items = $items->merge(
            News::orderByDesc('updated_at')->limit(5)->get()->map(fn ($item) => [
                'type' => 'News',
                'title' => $item->title,
                'updated_at' => $item->updated_at->toIso8601String(),
            ])
        );

        $items = $items->merge(
            Review::orderByDesc('updated_at')->limit(5)->get()->map(fn ($item) => [
                'type' => 'Review',
                'title' => $item->publication.($item->author ? ' — '.$item->author : ''),
                'updated_at' => $item->updated_at->toIso8601String(),
            ])
        );

        $items = $items->merge(
            RadioAirplay::orderByDesc('updated_at')->limit(5)->get()->map(fn ($item) => [
                'type' => 'Radio Airplay',
                'title' => '#'.$item->rank.' '.$item->chart,
                'updated_at' => $item->updated_at->toIso8601String(),
            ])
        );

        $items = $items->merge(
            PressEvent::orderByDesc('updated_at')->limit(5)->get()->map(fn ($item) => [
                'type' => 'Press',
                'title' => $item->title,
                'updated_at' => $item->updated_at->toIso8601String(),
            ])
        );

        $items = $items->merge(
            GalleryImage::orderByDesc('updated_at')->limit(5)->get()->map(fn ($item) => [
                'type' => 'Gallery',
                'title' => $item->caption ?? 'Gallery Image #'.$item->id,
                'updated_at' => $item->updated_at->toIso8601String(),
            ])
        );

        return response()->json(
            $items->sortByDesc('updated_at')->take(10)->values()
        );
    }

    public function storage(): JsonResponse
    {
        $totalSize = 0;
        $fileCount = 0;

        foreach (['gallery', 'radio-airplay', 'press', 'news'] as $dir) {
            $files = Storage::disk('public')->allFiles($dir);
            $fileCount += count($files);
            foreach ($files as $file) {
                $totalSize += Storage::disk('public')->size($file);
            }
        }

        return response()->json([
            'total_files' => $fileCount,
            'total_size_bytes' => $totalSize,
            'total_size_formatted' => $this->formatBytes($totalSize),
        ]);
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes === 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes, 1024));

        return round($bytes / pow(1024, $i), 1).' '.$units[(int) $i];
    }
}
