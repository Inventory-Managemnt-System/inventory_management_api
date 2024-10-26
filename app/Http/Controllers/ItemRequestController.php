<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\School;
use App\Models\Location;
use App\Models\QASchool;
use App\Models\ItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ItemRequestController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->user();
        if ($user['role']['slug'] === "head-teacher"){
            $itemRequests = ItemRequest::where(['user_id' => $user['id']])->latest()->with(['school', 'item', 'user'])->get();
            return response()->json(["itemRequests" => $this->collection($itemRequests)], Response::HTTP_OK);
        }

        if($user['role']['slug'] === "admin"){
            $itemRequests = ItemRequest::latest()->with(['school', 'item', 'user'])->get();
             return response()->json(["itemRequests" => $this->collection($itemRequests)], Response::HTTP_OK);
        }

        return response()->json(["itemRequests" => $this->collection(collect([]))], Response::HTTP_OK);
        
    }

    public function qa(): JsonResponse
    {
        $user = auth()->user();
        $schools = School::with('requests')->where('qa_id', $user["id"])->get();
        $locations = Location::with('requests')->where('qa_id', $user["id"])->get();
        $res = [
            "schools" => $schools,
            "locations" => $locations,
            "requests" => [],
        ];

        return response()->json($res, Response::HTTP_OK);
    }

    public function show(int $id): JsonResponse
    {
        $user = auth()->user();
        $itemRequest = ItemRequest::where(["id" => $id, 'user_id' => $user['id']])->with(['school', 'item', 'user'])->first();
        if (!$itemRequest) return response()->json(["message" => "Item Request not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        return response()->json(["itemRequest" => $this->resource($itemRequest)], Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        // get authenticated user
        $user = auth()->user();
        $school = School::where(["school_id" => $user['school']])->first();
        $validated = $this->validate($request, [
            "item_id" => 'numeric|required',
            "quantity" => 'numeric|required',
            "comment" => 'string|required',
        ]);
        // get item by id
        $item = Item::where(["id" => $validated["item_id"]])->first();
        if (!$item) return response()->json(["message" => "Item not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        // store item request
        $itemRequest = new ItemRequest();
        $itemRequest->school_id = $school['id'];
        $itemRequest->item_id = $validated["item_id"];
        $itemRequest->user_id = $user['id'];
        $itemRequest->quantity = $validated["quantity"];
        $itemRequest->comment = $validated["comment"];
        $itemRequest->save();
        return response()->json(["itemRequest" => $itemRequest], Response::HTTP_CREATED);
    }

    public function search(Request $request): JsonResponse
    {
        $validated = $this->validate($request, [
            "school_id" => 'numeric|nullable',
            "location_id" => 'numeric|nullable',
            "status" => 'string|nullable',
            "start_date" => 'string|nullable',
            "end_date" => 'string|nullable'
        ]);

        $school_id = $validated["school_id"];
        $location_id = $validated["location_id"];
        $status = $validated["status"];
        $start_date = $validated["start_date"];
        $end_date = $validated["end_date"];


        $query = ItemRequest::query();

        $query->when($school_id, function($q, $school_id){
            return $q->where("school_id", $school_id);
        });

        $query->when($location_id, function($q, $location_id){
            return $q->where("location_id", $location_id);
        });

        $query->when($status, function($q, $status){
            return $q->where("status", $status);
        });

        $query->when($start_date, function($q, $start_date){
            return $q->where("created_at", ">=", $start_date);
        });

        $query->when($end_date, function($q, $end_date){
            return $q->where("created_at", "<=", $end_date);
        });

        $itemRequest = $query->get();

        return response()->json(["itemRequest" => $this->collection($itemRequest)], Response::HTTP_CREATED);
    }

    public function collection(Collection $collection): array
    {
        return $collection->transform(function ($model) {
            return $this->resource($model);
        })->toArray();
    }

    public function resource(Model $model): array
    {
        return [
            "id" => $model['id'],
            "item" => [
                "id" => $model['item']['id'],
                "name" => $model['item']['item_name'],
                "code" => $model['item']['item_code'],
            ],
            "school" => [
                "id" => $model['school']['id'],
                "name" => $model['school']['name'],
            ],
            "user" => [
                "id" => $model['user']['id'],
                "name" => $model['user']['name'],
            ],
            "comment" => $model['comment'],
            "quantity" => $model['quantity'],
            "status" => $model['status'],
        ];
    }
}
