<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\News\StoreNewsRequest;
use App\Http\Requests\News\UpdateNewsRequest;
use App\Models\News;
use App\Support\ContentVisibility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NewsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = News::query()->orderByDesc('date');

        ContentVisibility::apply($query, $request);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $news = $query->get()->map(fn (News $item) => [
            ...$item->toArray(),
            'image_url' => $item->image_url,
        ]);

        return response()->json($news);
    }

    public function store(StoreNewsRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $news = News::create($data);

        return response()->json([
            ...$news->toArray(),
            'image_url' => $news->image_url,
        ], 201);
    }

    public function show(Request $request, News $news): JsonResponse
    {
        if (! ContentVisibility::canView($request, $news->status)) {
            throw new NotFoundHttpException();
        }

        return response()->json([
            ...$news->toArray(),
            'image_url' => $news->image_url,
        ]);
    }

    public function update(UpdateNewsRequest $request, News $news): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($news->image && !str_starts_with($news->image, 'http')) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $request->file('image')->store('news', 'public');
        } else {
            unset($data['image']);
        }

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $news->update($data);

        return response()->json([
            ...$news->toArray(),
            'image_url' => $news->image_url,
        ]);
    }

    public function destroy(News $news): JsonResponse
    {
        if ($news->image && !str_starts_with($news->image, 'http')) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return response()->json(null, 204);
    }
}
