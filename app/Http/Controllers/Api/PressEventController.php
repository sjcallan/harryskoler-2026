<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Press\StorePressEventRequest;
use App\Http\Requests\Press\UpdatePressEventRequest;
use App\Models\PressEvent;
use App\Models\PressImage;
use App\Support\ContentVisibility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PressEventController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = PressEvent::with('images')
            ->orderBy('sort_order')
            ->orderByDesc('date');

        ContentVisibility::apply($query, $request);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('publication', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $adminContext = ContentVisibility::isAdminContext($request);

        $events = $query->get()->map(fn (PressEvent $event) => [
            ...$event->toArray(),
            'pdf_url' => $event->pdf_url,
            'images' => $this->mapImages($event->images, $request, $adminContext),
        ]);

        return response()->json($events);
    }

    public function store(StorePressEventRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('pdf')) {
            $data['pdf'] = $request->file('pdf')->store('press/pdfs', 'public');
        }

        unset($data['images'], $data['captions']);

        $event = PressEvent::create($data);

        if ($request->hasFile('images')) {
            $captions = $request->input('captions', []);
            foreach ($request->file('images') as $i => $imageFile) {
                $path = $imageFile->store('press/images', 'public');
                $event->images()->create([
                    'image' => $path,
                    'caption' => $captions[$i] ?? null,
                    'sort_order' => $i,
                ]);
            }
        }

        $event->load('images');

        return response()->json([
            ...$event->toArray(),
            'pdf_url' => $event->pdf_url,
            'images' => $this->mapImages($event->images, $request, true),
        ], 201);
    }

    public function show(Request $request, PressEvent $pressEvent): JsonResponse
    {
        if (! ContentVisibility::canView($request, $pressEvent->status)) {
            throw new NotFoundHttpException();
        }

        $pressEvent->load('images');
        $adminContext = ContentVisibility::isAdminContext($request);

        return response()->json([
            ...$pressEvent->toArray(),
            'pdf_url' => $pressEvent->pdf_url,
            'images' => $this->mapImages($pressEvent->images, $request, $adminContext),
        ]);
    }

    public function update(UpdatePressEventRequest $request, PressEvent $pressEvent): JsonResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('pdf')) {
            if ($pressEvent->pdf && !str_starts_with($pressEvent->pdf, 'http')) {
                Storage::disk('public')->delete($pressEvent->pdf);
            }
            $data['pdf'] = $request->file('pdf')->store('press/pdfs', 'public');
        } elseif ($request->input('remove_pdf') === '1') {
            if ($pressEvent->pdf && !str_starts_with($pressEvent->pdf, 'http')) {
                Storage::disk('public')->delete($pressEvent->pdf);
            }
            $data['pdf'] = null;
        } else {
            unset($data['pdf']);
        }

        unset($data['images'], $data['captions'], $data['existing_images'], $data['remove_pdf']);

        $pressEvent->update($data);

        $existingImages = json_decode($request->input('existing_images', '[]'), true);
        $existingIds = collect($existingImages)->pluck('id')->filter()->toArray();

        $pressEvent->images()->whereNotIn('id', $existingIds)->each(function (PressImage $img) {
            if ($img->image && !str_starts_with($img->image, 'http')) {
                Storage::disk('public')->delete($img->image);
            }
            $img->delete();
        });

        foreach ($existingImages as $i => $imgData) {
            if (isset($imgData['id'])) {
                PressImage::where('id', $imgData['id'])->update([
                    'caption' => $imgData['caption'] ?? null,
                    'sort_order' => $i,
                ]);
            }
        }

        if ($request->hasFile('images')) {
            $captions = $request->input('captions', []);
            $offset = count($existingImages);
            foreach ($request->file('images') as $i => $imageFile) {
                $path = $imageFile->store('press/images', 'public');
                $pressEvent->images()->create([
                    'image' => $path,
                    'caption' => $captions[$i] ?? null,
                    'sort_order' => $offset + $i,
                ]);
            }
        }

        $pressEvent->load('images');

        return response()->json([
            ...$pressEvent->toArray(),
            'pdf_url' => $pressEvent->pdf_url,
            'images' => $this->mapImages($pressEvent->images, $request, true),
        ]);
    }

    public function destroy(PressEvent $pressEvent): JsonResponse
    {
        if ($pressEvent->pdf && !str_starts_with($pressEvent->pdf, 'http')) {
            Storage::disk('public')->delete($pressEvent->pdf);
        }

        foreach ($pressEvent->images as $img) {
            if ($img->image && !str_starts_with($img->image, 'http')) {
                Storage::disk('public')->delete($img->image);
            }
        }

        $pressEvent->delete();

        return response()->json(null, 204);
    }

    /**
     * Transform the nested image collection, honouring per-image visibility rules
     * when the consumer is the public site (logged-out or preview viewer).
     *
     * @param  iterable<PressImage>  $images
     * @return array<int, array<string, mixed>>
     */
    private function mapImages(iterable $images, Request $request, bool $adminContext): array
    {
        return collect($images)
            ->filter(function (PressImage $img) use ($request, $adminContext) {
                if ($adminContext) {
                    return true;
                }

                return ContentVisibility::canView($request, $img->status);
            })
            ->map(fn (PressImage $img) => [
                ...$img->toArray(),
                'image_url' => $img->image_url,
            ])
            ->values()
            ->all();
    }
}
