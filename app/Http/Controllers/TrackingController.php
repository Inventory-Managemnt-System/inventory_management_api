<?php

namespace App\Http\Controllers;

use App\Models\Tracking;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TrackingController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Tracking::latest()->get();
        return response()->json(['items' => $items], 200);
    }

    public function show(int $id): JsonResponse
    {
        $item = Tracking::where(['id' => $id])->first();
        if (is_null($item)) return response()->json(['message' => 'Tracking not found'], 422);
        return response()->json(['item' => $item], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "item_name" => "string|required",
            "item_description" => "string|required",
            "brand" => "string|required",
            "category" => "string|required",
            "priority" => "string|required",
            "address" => "string|required",
            "picking_area" => "string|required",
            "building_number" => "string|required",
            "action" => "string|required",
            "reference_number" => "string|required",
            "additional_info" => "string|nullable",
            "time_moved" => "string|required",
            "date_moved" => "string|required",
        ]);

        // store tracking
        $tracking = new Tracking();
        $tracking->fill($validated);
        $tracking->save();

        return response()->json(['item' => $tracking], 201);
    }
}
