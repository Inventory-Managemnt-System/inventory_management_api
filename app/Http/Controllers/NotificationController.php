<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends Controller
{
    public function get_notifications(): JsonResponse
    {
        $notifications = Notification::where(["user_id" => auth()->user()->id])->latest()->get();
        return response()->json(["notifications" => $notifications, "success" => true], Response::HTTP_OK);
    }

    public function get_notification(int $id): JsonResponse
    {
        $notification = Notification::where(["id" => $id, "user_id" => auth()->user()->id])->first();
        if(!$notification) return response()->json(["success" => false, "message" => "Notification not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        return response()->json(["success" => true, "notification" => $notification], Response::HTTP_OK);
    }
}
