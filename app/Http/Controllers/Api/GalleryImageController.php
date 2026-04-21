<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gallery\StoreGalleryImageRequest;
use App\Http\Requests\Gallery\UpdateGalleryImageRequest;
use App\Models\GalleryImage;
use App\Support\ContentVisibility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class GalleryImageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = GalleryImage::query()
            ->orderBy('sort_order')
            ->orderByDesc('created_at');

        ContentVisibility::apply($query, $request);

        $images = $query->get()->map(fn (GalleryImage $img) => [
            ...$img->toArray(),
            'image_url' => $img->image_url,
            'thumbnail_url' => $img->thumbnail_url,
        ]);

        return response()->json($images);
    }

    public function store(StoreGalleryImageRequest $request): JsonResponse
    {
        $created = [];
        $maxSort = GalleryImage::max('sort_order') ?? 0;
        $status = $request->input('status', GalleryImage::STATUS_DRAFT);

        foreach ($request->file('images') as $i => $file) {
            $imagePath = $file->store('gallery/images', 'public');
            $thumbnailPath = $this->generateThumbnail($imagePath);

            $galleryImage = GalleryImage::create([
                'status' => $status,
                'image' => $imagePath,
                'thumbnail' => $thumbnailPath,
                'caption' => $request->input('caption'),
                'alt_text' => $request->input('alt_text'),
                'credit' => $request->input('credit'),
                'sort_order' => $maxSort + $i + 1,
            ]);

            $created[] = [
                ...$galleryImage->toArray(),
                'image_url' => $galleryImage->image_url,
                'thumbnail_url' => $galleryImage->thumbnail_url,
            ];
        }

        return response()->json($created, 201);
    }

    public function update(UpdateGalleryImageRequest $request, GalleryImage $galleryImage): JsonResponse
    {
        $galleryImage->update($request->validated());

        return response()->json([
            ...$galleryImage->toArray(),
            'image_url' => $galleryImage->image_url,
            'thumbnail_url' => $galleryImage->thumbnail_url,
        ]);
    }

    public function destroy(GalleryImage $galleryImage): JsonResponse
    {
        Storage::disk('public')->delete($galleryImage->image);
        Storage::disk('public')->delete($galleryImage->thumbnail);

        $galleryImage->delete();

        return response()->json(null, 204);
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:gallery_images,id'],
        ]);

        foreach ($request->input('ids') as $index => $id) {
            GalleryImage::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['message' => 'Order updated']);
    }

    private function generateThumbnail(string $imagePath): string
    {
        $fullPath = Storage::disk('public')->path($imagePath);
        $image = Image::read($fullPath);
        $image->scaleDown(width: 400);

        $pathInfo = pathinfo($imagePath);
        $thumbPath = $pathInfo['dirname'].'/thumbs/'.$pathInfo['basename'];

        Storage::disk('public')->makeDirectory($pathInfo['dirname'].'/thumbs');
        $image->save(Storage::disk('public')->path($thumbPath));

        return $thumbPath;
    }
}
