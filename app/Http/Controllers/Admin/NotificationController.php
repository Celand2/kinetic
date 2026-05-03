<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.notifications.create', compact('users'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'user_ids' => 'required|array|min:1',
        ]);

        foreach ($validated['user_ids'] as $userId) {
            Notification::create([
                'user_id' => $userId,
                'type' => $validated['type'],
                'title' => $validated['title'],
                'message' => $validated['message'],
            ]);
        }

        return redirect()->route('admin.notifications')
            ->with('success', 'Notifications sent successfully!');
    }
}
