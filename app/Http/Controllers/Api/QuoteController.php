<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quotes\StoreQuoteRequest;
use App\Http\Requests\Quotes\UpdateQuoteRequest;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Quote::query()->orderBy('sort_order')->orderByDesc('created_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('quote', 'like', "%{$search}%")
                  ->orWhere('person', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        return response()->json($query->get());
    }

    public function active(): JsonResponse
    {
        $quotes = Quote::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'quote', 'person', 'company']);

        return response()->json($quotes);
    }

    public function store(StoreQuoteRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['is_active'] ??= true;
        $data['sort_order'] ??= 0;

        $quote = Quote::create($data);

        return response()->json($quote, 201);
    }

    public function show(Quote $quote): JsonResponse
    {
        return response()->json($quote);
    }

    public function update(UpdateQuoteRequest $request, Quote $quote): JsonResponse
    {
        $quote->update($request->validated());

        return response()->json($quote);
    }

    public function destroy(Quote $quote): JsonResponse
    {
        $quote->delete();

        return response()->json(null, 204);
    }
}
