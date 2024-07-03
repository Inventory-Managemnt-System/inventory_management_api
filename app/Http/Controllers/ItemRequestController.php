<?php

namespace App\Http\Controllers;

use App\Models\ItemRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ItemRequestController extends Controller
{
    public function index(): JsonResponse
    {
        $itemRequests = ItemRequest::latest()->get();
        return response()->json(["itemRequests" => $itemRequests], Response::HTTP_OK);
    }

    public function show(int $id): JsonResponse
    {
        $itemRequest = ItemRequest::where(["id" => $id])->first();
        if (!$itemRequest) return response()->json(["message" => "Item Request not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        return response()->json(["itemRequest" => $itemRequest], Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validate($request, [
            "school_name" => 'string|required',
            "head_teacher_name" => 'string|required',
            "item_name" => 'string|required',
            "quantity" => 'integer|required',
            "comment" => 'string|required',
        ]);
        $itemRequest = new ItemRequest();
        $itemRequest->fill($validated);
        $itemRequest->save();
        return response()->json(["itemRequest" => $itemRequest], Response::HTTP_CREATED);
    }
}
