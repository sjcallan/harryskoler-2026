<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RadioAirplays\StoreRadioAirplayRequest;
use App\Http\Requests\RadioAirplays\UpdateRadioAirplayRequest;
use App\Models\RadioAirplay;
use App\Support\ContentVisibility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RadioAirplayController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = RadioAirplay::query()->orderBy('created_at', 'desc');

        ContentVisibility::apply($query, $request);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('chart', 'like', "%{$search}%")
                  ->orWhere('detail', 'like', "%{$search}%");
            });
        }

        return response()->json(
            $query->get()->map(fn (RadioAirplay $item) => [
                ...$item->toArray(),
                'image_url' => $item->image_url,
                'thumbnail_url' => $item->thumbnail_url,
            ])
        );
    }

    public function store(StoreRadioAirplayRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['sort_order'] ??= 0;

        unset($data['image']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('radio-airplay/images', 'public');
            $data['thumbnail'] = $this->generateThumbnail($data['image']);
        }

        $airplay = RadioAirplay::create($data);

        return response()->json([
            ...$airplay->toArray(),
            'image_url' => $airplay->image_url,
            'thumbnail_url' => $airplay->thumbnail_url,
        ], 201);
    }

    public function show(Request $request, RadioAirplay $radioAirplay): JsonResponse
    {
        if (! ContentVisibility::canView($request, $radioAirplay->status)) {
            throw new NotFoundHttpException();
        }

        return response()->json([
            ...$radioAirplay->toArray(),
            'image_url' => $radioAirplay->image_url,
            'thumbnail_url' => $radioAirplay->thumbnail_url,
        ]);
    }

    public function update(UpdateRadioAirplayRequest $request, RadioAirplay $radioAirplay): JsonResponse
    {
        $data = $request->validated();

        unset($data['image']);

        if ($request->input('remove_image') === '1' && $radioAirplay->image) {
            Storage::disk('public')->delete($radioAirplay->image);
            if ($radioAirplay->thumbnail) {
                Storage::disk('public')->delete($radioAirplay->thumbnail);
            }
            $data['image'] = null;
            $data['thumbnail'] = null;
        }

        if ($request->hasFile('image')) {
            if ($radioAirplay->image) {
                Storage::disk('public')->delete($radioAirplay->image);
            }
            if ($radioAirplay->thumbnail) {
                Storage::disk('public')->delete($radioAirplay->thumbnail);
            }
            $data['image'] = $request->file('image')->store('radio-airplay/images', 'public');
            $data['thumbnail'] = $this->generateThumbnail($data['image']);
        }

        $radioAirplay->update($data);

        return response()->json([
            ...$radioAirplay->toArray(),
            'image_url' => $radioAirplay->image_url,
            'thumbnail_url' => $radioAirplay->thumbnail_url,
        ]);
    }

    public function destroy(RadioAirplay $radioAirplay): JsonResponse
    {
        if ($radioAirplay->image) {
            Storage::disk('public')->delete($radioAirplay->image);
        }
        if ($radioAirplay->thumbnail) {
            Storage::disk('public')->delete($radioAirplay->thumbnail);
        }

        $radioAirplay->delete();

        return response()->json(null, 204);
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:radio_airplays,id'],
        ]);

        foreach ($request->input('ids') as $index => $id) {
            RadioAirplay::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['message' => 'Order updated']);
    }

    private function generateThumbnail(string $imagePath): string
    {
        $fullPath = Storage::disk('public')->path($imagePath);
        $image = Image::read($fullPath);
        $image->scaleDown(width: 300);

        $pathInfo = pathinfo($imagePath);
        $thumbPath = $pathInfo['dirname'].'/thumbs/'.$pathInfo['basename'];

        Storage::disk('public')->makeDirectory($pathInfo['dirname'].'/thumbs');
        $image->save(Storage::disk('public')->path($thumbPath));

        return $thumbPath;
    }
}
