<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $category = Category::all();
        return response()->json([
            "message" => "Fetched categories successfully",
            "data" => $category
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $category = Category::where("id", $id)->first();
        if (!$category) return response()->json(["message" => "category not found"], 422);
        return response()->json([
            "message" => "Fetched category successfully",
            "data" => $category
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validate = $this->validate($request, [
            "name" => "required|string",
            "description" => "nullable|string",
        ]);

        $category = new Category();
        $category->name = $validate["name"];
        $category->description = $validate["description"];
        $category->slug = Str::slug($validate['name']);
        $category->save();

        return response()->json([
            "message" => "Category created successfully",
            "data" => $category
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validate = $this->validate($request, [
            "name" => "required|string",
            "description" => "nullable|string",
        ]);
        $category = Category::where("id", $id)->first();
        if (!$category) return response()->json(["message" => "Category not found"], 422);

        $category->name = $validate["name"];
        $category->description = $validate["description"];
        $category->save();

        return response()->json([
            "message" => "Category updated successfully",
            "data" => $category
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $category = Category::where("id", $id)->first();
        if (!$category) return response()->json(["message" => "Category not found"], 422);
        $category->delete();
        return response()->json([
            "message" => "Category deleted successfully"
        ]);
    }
}
