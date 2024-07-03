<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::latest()->get();
        return response()->json(["users" => $users, "message" => "Users fetched"], Response::HTTP_OK);
    }

    public function create(): JsonResponse
    {
        $roles = Role::latest()->get();
        return response()->json(["roles" => $roles], Response::HTTP_OK);
    }

    public function show(int $id): JsonResponse
    {
        $user = User::where(["id" => $id])->firstOrFail();
        if(!$user) return response()->json(["message" => "User not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        return response()->json(["user" => $user, "message" => "Fetched user"], Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "name" => "required|string",
            "username" => "required|string",
            "oracle_id" => "required|string",
            "email" => "required|string",
            "phone_number" => "required|string",
            "level" => "required|string",
            "image" => "required|string",
            "role" => "required|string",
            "department" => "required|string",
        ]);

        // find role
        $role = Role::where(["slug" => $validated["role"]])->firstOrFail();
        if (!$role) return response()->json(["message" => "Role not found"], Response::HTTP_UNPROCESSABLE_ENTITY);

        // create user
        $user = User::create([
            "role_id" => $role->id,
            "name" => $validated["name"],
            "username" => $validated["username"],
            "oracle_id" => $validated["oracle_id"],
            "email" => $validated["email"],
            "phone_number" => $validated["phone_number"],
            "level" => $validated["level"],
            "image" => $validated["image"],
            "department" => $validated["department"],
            "password" => Hash::make("password@1234")
        ]);
        return response()->json(["message" => "User created", "user" => $user], Response::HTTP_CREATED);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            "name" => "required|string",
            "username" => "required|string",
            "oracle_id" => "required|string",
            "email" => "required|string",
            "phone_number" => "required|string",
            "level" => "required|string",
            "image" => "required|string",
            "role" => "required|string",
            "department" => "required|string",
        ]);

        $user = User::where(["id" => $id])->firstOrFail();
        if(!$user) return response()->json(["message" => "User not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        // find role
        $role = Role::where(["slug" => $validated["role"]])->firstOrFail();
        if (!$role) return response()->json(["message" => "Role not found"], Response::HTTP_UNPROCESSABLE_ENTITY);

        // update status
        $user = $user->update([
            "role_id" => $role['id'],
            "name" => $validated["name"],
            "username" => $validated["username"],
            "oracle_id" => $validated["oracle_id"],
            "email" => $validated["email"],
            "phone_number" => $validated["phone_number"],
            "level" => $validated["level"],
            "image" => $validated["image"],
            "department" => $validated["department"],
        ]);
        return response()->json(["message" => "User updated", "user" => $user], Response::HTTP_OK);
    }

    public function updateStatus(int $id): JsonResponse
    {
        $user = User::where(["id" => $id])->firstOrFail();
        if(!$user) return response()->json(["message" => "User not found"], Response::HTTP_UNPROCESSABLE_ENTITY);

        $user->status = $user->status == 'active' ? 'inactive' : 'active';
        $user->save();
        return response()->json(["message" => "User status updated", "user" => $user], Response::HTTP_OK);
    }
}
