<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reviews\StoreReviewRequest;
use App\Http\Requests\Reviews\UpdateReviewRequest;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Review::query()->orderBy('sort_order')->orderByDesc('created_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('excerpt', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('publication', 'like', "%{$search}%");
            });
        }

        return response()->json($query->get());
    }

    public function store(StoreReviewRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['sort_order'] ??= 0;

        $review = Review::create($data);

        return response()->json($review, 201);
    }

    public function show(Review $review): JsonResponse
    {
        return response()->json($review);
    }

    public function update(UpdateReviewRequest $request, Review $review): JsonResponse
    {
        $review->update($request->validated());

        return response()->json($review);
    }

    public function destroy(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json(null, 204);
    }
}
