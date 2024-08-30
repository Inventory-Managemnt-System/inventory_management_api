<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\School;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TrackingController extends Controller
{

    private $item_model;
    private $school_model;

    public function item_model(): string
    {
        return Item::class;
    }

    public function school_model(): string
    {
        return  School::class;
    }
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

    public function find_items(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            "search" => "string|required",
        ]);

        $searchParam = trim($validated['search']);
        $items = Item::where('item_name', 'LIKE', "%{$searchParam}%")->get();

        return response()->json(['count' => count($items), 'items' => $items], 200);
    }

    public function find_schools(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            "search" => "string|required",
        ]);

        $searchParam = trim($validated['search']);
        $schools = School::where('name', 'LIKE', "%{$searchParam}%")
                        ->orWhere('school_id', 'LIKE', "%{$searchParam}%")->get();

        return response()->json(["count" => count($schools), "schools" => $schools], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "item_id" => "numeric|required",
            "school_id" => "numeric|required",
            "priority" => "string|required",
            "action" => "string|required",
            "reference_number" => "string|required",
            "additional_info" => "string|nullable",
            "date_moved" => "date|required",
        ]);

        // store tracking
        $tracking = new Tracking();
        $tracking->fill($validated);
        $tracking->save();

        return response()->json(['item' => $tracking], 201);
    }

    public function update(Request $request, $id){
        $item = Tracking::find($id);
        $item->status = $request->status;
        if($request->action){
            $item->action = $request->action;
        }
        $item->save();
       return response('Tracking updated successfully', 200);

    }
}
