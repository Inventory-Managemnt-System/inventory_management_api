<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends Controller
{
    //
    public function index(): JsonResponse
    {
        $items = Item::latest()->get();
        return response()->json(["items" => $items], Response::HTTP_OK);
    }

    public function show(int $id): JsonResponse
    {
        $item = Item::where(["id" => $id])->first();
        if (!$item) return response()->json(["message" => "Item not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        return response()->json(["item" => $item], Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $request = $this->validate($request, [
            "name" => "required|string",
            "description" => "required|string",
            "brand" => "required|string",
            // "category" => "required|string",
            "value" => "required|string",
            "bar_code" => "required|string",
            "image" => "required",
            "unit_cost" => "required|numeric",
            "quantity" => "required|numeric",
            "reorder_point" => "required|numeric",
            "supplier" => "required|string",
        ]);

        $file = $request['image'];
       
        $imageName = time() . $file->getClientOriginalName();
$filePath = $file->move(public_path('uploads'), $imageName);
$imagePath = "https://f3a5-102-91-93-193.ngrok-free.app/uploads/" . $imageName;
        // create item
        $item = new Item();
        $item->unique_id = $this->UniqueID();
        $item->name = $request["name"];
        $item->description = $request["description"];
        $item->brand = $request["brand"];
        // $item->category = $request["category"];
        $item->value = $request["value"];
        $item->bar_code = $request['bar_code'];
        $item->image = $imagePath;
        $item->unit_cost = $request["unit_cost"];
        $item->quantity = $request["quantity"];
        $item->reorder_point = $request["reorder_point"];
        $item->supplier = $request["supplier"];
        $item->save();

        return response()->json(["item" => $item], Response::HTTP_CREATED);
    }

    public function update()
    {

    }

    public function delete()
    {

    }

    protected function UniqueID(int $length=10):string
    {
        $pool = '0123456789';
        $nonZeroPool = '123456789';

        // Generate the first character from non-zero pool
        $firstChar = $nonZeroPool[random_int(0, strlen($nonZeroPool) - 1)];

        // Generate the remaining characters from the full pool
        $remainingChars = '';
        for ($i = 0; $i < $length - 1; $i++) {
            $remainingChars .= $pool[random_int(0, strlen($pool) - 1)];
        }

        // Combine the first character with the remaining characters
        return $firstChar . $remainingChars;
    }

    public function search(string $id): JsonResponse
    {
        $item = Item::where(["id" => $id])->first();
        if (!$item) return response()->json(["message" => "Item not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        return response()->json(["item" => $item], Response::HTTP_OK);
    }
}
