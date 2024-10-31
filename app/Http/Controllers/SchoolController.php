<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use App\Models\Location;
use App\Models\AllSchools;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Whoops\Handler\JsonResponseHandler;
use Symfony\Component\HttpFoundation\Response;

class SchoolController extends Controller
{
    public function index(): JsonResponse
    {
        $schools = School::latest()->paginate(50);
        return response()->json([
            "schools" => $schools->items(),
            'count'=> $schools->total(),
            "message" => "Fetched schools",
            "pagination" => [
                "total" => $schools->total(),
                "per_page" => $schools->perPage(),
                "current_page" => $schools->currentPage(),
                "last_page" => $schools->lastPage(),
                "next_page_url" => $schools->nextPageUrl(),
                "prev_page_url" => $schools->previousPageUrl(),
            ]
        ], Response::HTTP_OK);
    }

    public function allSchools(): JsonResponse
    {
        $schools = School::all();
        return response()->json([
            "schools" => $schools,
        ]);
    }

    public function UploadSchools(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('file');
        $handle = fopen($file, "r");
        $header = true;

        while (($row = fgetcsv($handle, 1000, ",")) !== false) {
            if($header) {
                $header = false;
                continue;
            }
            School::create([
                'name' => $row[0],
                'school_id' => $row[1],
            ]);
        }
        fclose($handle);
        return response()->json(["message" => "Upload success"], Response::HTTP_OK);
    }

    public function show(int $id): JsonResponse
    {
        $school = School::with("newitems")->where(["id" => $id])->firstOrFail();
        if (!$school) return response()->json(["message" => "School not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        // get school items
        $items = $school->newitems;
        return response()->json([
            "message" => "Fetched school",
            "school" => $school,
            "items" => $items,
           
        ], Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "name" => "required|string",
            "website" => "nullable|string|url",
            "email" => "nullable|string|email",
            "phone_number" => "nullable|string",
            "level" => "required|string",
            "logo" => "nullable|string",
            "address" => "required|string",
            "city" => "required|string",
            "lga" => "required|string",
            "postal_code" => "nullable|string",
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
            "email" => "nullable|string|email",
            "phone_number" => "nullable|string",
            "level" => "required|string",
            "logo" => "nullable|string",
            "address" => "required|string",
            "city" => "required|string",
            "lga" => "required|string",
            "postal_code" => "nullable|string",
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

    public function find_schools(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "search" => "string|required",
        ]);

        $searchParam = trim($validated['search']);
        $schools = School::where('name', 'LIKE', "%{$searchParam}%")
            ->orWhere('school_id', 'LIKE', "%{$searchParam}%")->get();

        return response()->json(["count" => count($schools), "schools" => $schools], 200);
    }


    public function lga(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "lga" => "string|required",
        ]);

        $searchParam = trim($validated['lga']);

        $schools = School::where('lga', $searchParam)->get();
        
        return response()->json(["count" => count($schools), "schools" => $schools], 200);
    }

    public function level(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "level" => "string|required",
        ]);

        $searchParam = trim($validated['level']);

        $schools = School::where('level', $searchParam)->get();
        
        return response()->json(["count" => count($schools), "schools" => $schools], 200);
    }


    public function schoolqa(): JsonResponse
    {

        $qa = User::with("schoolqa")->where('role_id', 1)->get();
        
        return response()->json($qa, 200);
    }

    public function qadetails(int $id, ): JsonResponse
    {

        $qa = User::with("schoolqa")->where('id', $id)->first();

        if (!$qa) return response()->json(["message" => "Quality Assurance Officer not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        
        return response()->json($qa, 200);
    }


    public function qaupdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "school_id" => "integer|nullable",
            "location_id" => "integer|nullable",
            "user_id" => "required",
            "assign" => "integer|required",
        ]);

        $qa = User::where('role_id', 1)->where('id', $validated['user_id'])->first();
        if (!$qa) return response()->json(["message" => "Quality Assurance Officer not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        
        $qa_id = ($validated["assign"]) ? $qa->id : null;
        
        if($validated['school_id']){
            $school = School::where("id", $validated['school_id'])->first();
            if (!$school) return response()->json(["message" => "School not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
            $school->update(["qa_id"=>$qa_id]);
        }

        if($validated['location_id']){
            $location = Location::where("id", $validated['location_id'])->first();
            if (!$location) return response()->json(["message" => "Location not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
            $location->update(["qa_id"=>$qa_id]);
        }

        
        return response()->json($qa->refresh(), 200);
    }

}
