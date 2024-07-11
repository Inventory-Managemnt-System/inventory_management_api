<?php

namespace App\Http\Controllers;

use App\Models\AllSchools;
use App\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SchoolController extends Controller
{
    public function index(): JsonResponse
    {
        $schools = AllSchools::all();
        $count = AllSchools::count();
        return response()->json(["schools" => $schools,'count'=>$count, "message" => "Fetched schools"], Response::HTTP_OK);
    }

    public function show(int $id): JsonResponse
    {
        $school = School::where(["id" => $id])->firstOrFail();
        if (!$school) return response()->json(["message" => "School not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        return response()->json(["school" => $school, "message" => "Fetched school"], Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "name" => "required|string",
            "website" => "nullable|string|url",
            "email" => "required|string|email",
            "phone_number" => "required|string",
            "level" => "required|string",
            "logo" => "nullable|string",
            "address" => "required|string",
            "city" => "required|string",
            "lga" => "required|string",
            "postal_code" => "required|string",
        ]);

        // create school record
        $school = School::create($validated);
        return response()->json(["school" => $school, "message" => "School created!"], Response::HTTP_CREATED);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            "name" => "required|string",
            "website" => "nullable|string|url",
            "email" => "required|string|email",
            "phone_number" => "required|string",
            "level" => "required|string",
            "logo" => "nullable|string",
            "address" => "required|string",
            "city" => "required|string",
            "lga" => "required|string",
            "postal_code" => "required|string",
        ]);

        $school = School::where(["id" => $id])->firstOrFail();
        if (!$school) return response()->json(["message" => "School not found"], Response::HTTP_UNPROCESSABLE_ENTITY);

        $school->update($validated);
        return response()->json(["school" => $school, "message" => "School updated!"], Response::HTTP_OK);
    }

    public function destroy(int $id): JsonResponse
    {
        $school = School::where(["id" => $id])->firstOrFail();
        if (!$school) return response()->json(["message" => "School not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        $school->delete();
        return response()->json(["message" => "School deleted!"], Response::HTTP_OK);
    }
}
