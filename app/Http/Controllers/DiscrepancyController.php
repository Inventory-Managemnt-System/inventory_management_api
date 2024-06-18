<?php

namespace App\Http\Controllers;

use App\Models\Discrepancy;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DiscrepancyController extends Controller
{
    //
    public function index(): JsonResponse
    {
        $discrepancies = Discrepancy::all();
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
            "report_id" => 'string|required',
            "reporter" => 'string|required',
            "item_name" => 'string|required',
            "supplier" => 'string|required',
            "expected_quantity" => 'integer|required',
            "actual_quantity" => 'integer|required',
            "discrepancy_type" => 'string|required',
            "description" => 'string|required',
            "date" => 'string|required',
        ]);

        $discrepancy = new Discrepancy();
        $discrepancy->fill($validated);
        $discrepancy->save();
        return response()->json(["discrepancy" => $discrepancy], Response::HTTP_CREATED);
    }
}
