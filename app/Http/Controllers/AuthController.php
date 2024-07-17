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
            "password" => "required",
            'phone_number'=>'required'
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
        $new_user->oracle_id = $this->GenerateOracleID(7);
        $new_user->phone_number = $validatedData['phone_number'];
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

    public function GenerateOracleID(int $length = 7):string
    {
        $oracle_id = $this->RandomString($length);
        $oracleIDExists = User::where("oracle_id", $oracle_id)->first();
        if ($oracleIDExists) {
            $this->RandomString($length);
        } else {
            return $oracle_id;
        }
    }
    public function forgotPassword(Request $request){
        $validated = $request->validate(['email'=>'required|email']);
        $user = User::where(["email" => $validated['email']])->first();
        if (!$user) return response()->json(["message" => "User not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        return response('User found', 200);
    }

    public function changePassword(Request $request){
        $validated = $request->validate(['password'=>'required', 'email'=>'required']);
        $user = User::where(["email" => $validated['email']])->first();
        if (!$user) return response()->json(["message" => "User not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        $user->password = Hash::make($validated['password']);
        $user->save();

        return response('Password changed successfully', 200);


    }

    public function RandomString(int $length): string
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

}