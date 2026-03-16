<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RadioAirplays\StoreRadioAirplayRequest;
use App\Http\Requests\RadioAirplays\UpdateRadioAirplayRequest;
use App\Models\RadioAirplay;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RadioAirplayController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = RadioAirplay::query()->orderBy('sort_order')->orderBy('rank');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('chart', 'like', "%{$search}%")
                  ->orWhere('detail', 'like', "%{$search}%");
            });
        }

        return response()->json($query->get());
    }

    public function store(StoreRadioAirplayRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['sort_order'] ??= 0;

        $airplay = RadioAirplay::create($data);

        return response()->json($airplay, 201);
    }

    public function show(RadioAirplay $radioAirplay): JsonResponse
    {
        return response()->json($radioAirplay);
    }

    public function update(UpdateRadioAirplayRequest $request, RadioAirplay $radioAirplay): JsonResponse
    {
        $radioAirplay->update($request->validated());

        return response()->json($radioAirplay);
    }

    public function destroy(RadioAirplay $radioAirplay): JsonResponse
    {
        $radioAirplay->delete();

        return response()->json(null, 204);
    }
}
