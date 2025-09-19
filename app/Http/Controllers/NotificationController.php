<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        $notifications = $user->notifications()
            ->latest()
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $total = $user->notifications()->count();

        return response()->json([
            'data' => $notifications,
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,
        ]);
    }
}
