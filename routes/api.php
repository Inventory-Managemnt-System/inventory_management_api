<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiscrepancyController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemRequestController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("auth")->controller(AuthController::class)->group(function () {
   Route::post("/register", "signup");
   Route::post("/login", "signin");
});

Route::prefix("item")->middleware(["auth:sanctum"])->controller(ItemController::class)->group(function () {
    Route::get("/", "index");
    Route::get("{id}", "show");
    Route::post("/", "store");
});

Route::prefix("school")->middleware(["auth:sanctum"])->controller(SchoolController::class)->group(function () {
    Route::get("/", "index");
    Route::get("{id}", "show");
    Route::post("/", "store");
    Route::patch("{id}", "update");
    Route::delete("{id}", "destroy");
});

Route::prefix("user")->middleware(["auth:sanctum"])->controller(UserController::class)->group(function () {
    Route::get("/", "index");
    Route::get("/get-roles", "create");
    Route::get("{id}", "show");
    Route::post("/", "store");
    Route::patch("{id}", "update");
    Route::patch("update-status/{id}", "updateStatus");
});

Route::prefix("discrepancy")->middleware(["auth:sanctum"])->controller(DiscrepancyController::class)->group(function () {
    Route::get("/", "index");
    Route::get("{id}", "show");
    Route::post("/", "store");
});

Route::prefix("tracking")->middleware(["auth:sanctum"])->controller(TrackingController::class)->group(function () {
    Route::get("/", "index");
    Route::get("{id}", "show");
    Route::post("/", "store");
});

Route::prefix("item-request")->middleware(["auth:sanctum"])->controller(ItemRequestController::class)->group(function () {
    Route::get("/", "index");
    Route::get("{id}", "show");
    Route::post("/", "store");
});
