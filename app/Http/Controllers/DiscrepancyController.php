<?php
namespace App\Http\Controllers;

use App\Models\Discrepancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DiscrepancyController extends Controller
{
    
    public function index(): JsonResponse
    {
        $user = auth()->user();
        if ($user['role']['slug'] === "head-teacher"){
            $discrepancies = Discrepancy::where(['school_id' => $user['school']])->latest()->get();
            return response()->json(["discrepancies" => $discrepancies], Response::HTTP_OK);
        }

        if($user['role']['slug'] === "subeb-user"){
            $discrepancies = Discrepancy::where(['location_id' => $user['location_id']])->latest()->get();
            return response()->json(["discrepancies" => $discrepancies], Response::HTTP_OK);
        }
        $discrepancies = Discrepancy::where('status', 'review')->latest()->get();
        return response()->json(["discrepancies" => $discrepancies], Response::HTTP_OK);
    }

    public function resolved(): JsonResponse
    {
        $discrepancies = Discrepancy::where('status', 'resolved')->get();
        return response()->json(["discrepancies" => $discrepancies], Response::HTTP_OK);
    }


    public function show(int $id): JsonResponse
    {
        $discrepancy = Discrepancy::where(['id' => $id])->first();
        if(!$discrepancy)  return response()->json(["message" => "Discrepancy not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        return response()->json(["discrepancy" => $discrepancy], Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validate($request, [
            "report_id" => 'required',
            "reporter" => 'string|required',
            "item_name" => 'string|required',
            "supplier" => 'string|required',
            "expected_quantity" => 'integer|required',
            "actual_quantity" => 'integer|required',
            "discrepancy_type" => 'string|required',
            "description" => 'string|required',
            "date" => 'string|required',
        ]);

        $reportID = Discrepancy::where("report_id", $validated["report_id"])->first();
        if($reportID)  return response()->json(["message" => "Report ID already exists, please change"], Response::HTTP_NOT_FOUND);
        
        $user = auth()->user();
        if ($user['role']['slug'] === "head-teacher"){
            $validated['school_id'] = $user['school'];
        }

        if ($user['role']['slug'] === "head-teacher"){
            $validated['location_id'] = $user['location_id'];
        }
        
        $discrepancy = new Discrepancy();
        $discrepancy->fill($validated);
        $discrepancy->save();
        return response()->json(["discrepancy" => $discrepancy], Response::HTTP_CREATED);
    }


    public function update_status(Request $request, int $id): JsonResponse
    {
        $discrepancy = Discrepancy::where(['id' => $id])->first();
        if(!$discrepancy)  return response()->json(["message" => "Discrepancy not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        
        $validated = $this->validate($request, [
            "status" => 'string|required',
        ]);

        $discrepancy->update([
            'status' => $request->status
        ]);

        return response()->json(["message" => "Discrepancy status updated successfully"], Response::HTTP_OK);
    }

    public function destroy(int $id): JsonResponse
    {
        $discrepancy = Discrepancy::where(['id' => $id])->first();
        if(!$discrepancy)  return response()->json(["message" => "Discrepancy not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        $discrepancy->delete();
        return response()->json(["message" => "Discrepancy deleted successfully"], Response::HTTP_OK);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $validated = $this->validate($request, [
            "ids" => "array|required",
        ]);

        DB::beginTransaction();
        foreach ($validated['ids'] as $id) {
            $discrepancy = Discrepancy::where(['id' => $id])->first();
            if ($discrepancy) {
                $discrepancy->delete();
            }
        }
        DB::commit();
        return response()->json(["message" => "Discrepancies deleted successfully"], Response::HTTP_OK);
    }
}
