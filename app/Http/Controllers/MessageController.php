<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function inbox()
    {
        $user = Auth::user();
        $messages = Message::where('receiver_id', $user->id)
            ->with('sender')
            ->latest()
            ->paginate(20);

        return view('messages.inbox', compact('messages'));
    }

    public function sent()
    {
        $user = Auth::user();
        $messages = Message::where('sender_id', $user->id)
            ->with('receiver')
            ->latest()
            ->paginate(20);

        return view('messages.sent', compact('messages'));
    }

    public function show(Message $message)
    {
        $this->authorize('view', $message);

        if ($message->receiver_id === Auth::id() && !$message->is_read) {
            $message->update(['is_read' => true, 'read_at' => now()]);
        }

        return view('messages.show', compact('message'));
    }

    public function create()
    {
        return view('messages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'subject' => $validated['subject'],
            'content' => $validated['content'],
        ]);

        return redirect()->route('messages.sent')
            ->with('success', 'Message sent successfully!');
    }

    public function markAsRead(Message $message)
    {
        $this->authorize('view', $message);

        $message->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
