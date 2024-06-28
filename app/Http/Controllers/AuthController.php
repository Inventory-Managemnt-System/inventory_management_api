<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    //
    public function signup(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            "role" => "required",
            "email" => "required|email|unique:users",
            "password" => "required"
        ]);

        $user = User::where(["email" => $validatedData['email']])->first();
        if ($user) return response()->json(["message" => "User already exists"], Response::HTTP_UNPROCESSABLE_ENTITY);
        // get role
        $role = Role::where(["slug" => $validatedData['role']])->first();
        if (!$role) return response()->json(["message" => "Role not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        // create user
        $new_user = new User();
        $new_user->role_id = $role->id;
        $new_user->email = $validatedData['email'];
        $new_user->password = Hash::make($validatedData['password']);
        $new_user->oracle_id = "0933j42";
        $new_user->save();

        return response()->json(["message" => "User created successfully"], Response::HTTP_CREATED);
    }

    public function signin(Request $request): JsonResponse
    {
        $request->validate([
            "email" => "sometimes|email",
            "oracle_id" => "sometimes|string",
            "password" => "required"
        ]);

        // find with oracle id or email
        if($request->has("oracle_id")){
            $user = User::where(["oracle_id" => $request["oracle_id"]])->with(["role"])->first();
        } else {
            $user = User::where(["email" => $request['email']])->with(["role"])->first();
        }
        if (!$user) return response()->json(["message" => "User not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        if (!Hash::check($request['password'], $user->password)) return response()->json(["message" => "Wrong password"], Response::HTTP_UNPROCESSABLE_ENTITY);

        // create user auth token
        $token = $user->createToken("Auth_Token-".$user['oracle_id'],  ["*"], Carbon::now()->addMinutes(config('sanctum.expiration')))->plainTextToken;
        return response()->json(["token" => $token, "user" => $user], Response::HTTP_OK);
    }


}
