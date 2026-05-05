<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $conversations = Conversation::where('user_id', Auth::id())
            ->withCount('messages')
            ->orderByDesc('last_message_at')
            ->paginate(20);

        return view('client.messages.index', compact('conversations'));
    }

    public function create()
    {
        return view('client.messages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject'  => 'required|string|max:200',
            'category' => 'required|in:support,deposit,withdrawal,investment,referral,dispute,other',
            'body'     => 'required|string|max:5000',
        ]);

        $conversation = Conversation::create([
            'user_id'            => Auth::id(),
            'subject'            => $validated['subject'],
            'category'           => $validated['category'],
            'status'             => 'open',
            'priority'           => 'normal',
            'last_message_at'    => now(),
            'unread_admin_count' => 1,
            'unread_user_count'  => 0,
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => Auth::id(),
            'body'            => $validated['body'],
            'type'            => 'text',
        ]);

        return redirect()->route('messages.show', $conversation)
            ->with('success', 'Votre message a été envoyé.');
    }

    public function show(Conversation $conversation)
    {
        if ($conversation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($conversation->unread_user_count > 0) {
            $conversation->update(['unread_user_count' => 0]);
        }

        $messages = $conversation->messages()->with('sender')->orderBy('created_at')->get();

        return view('client.messages.show', compact('conversation', 'messages'));
    }

    public function reply(Request $request, Conversation $conversation)
    {
        if ($conversation->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => Auth::id(),
            'body'            => $validated['body'],
            'type'            => 'text',
        ]);

        $conversation->update([
            'last_message_at'    => now(),
            'unread_admin_count' => $conversation->unread_admin_count + 1,
            'status'             => 'open',
        ]);

        return redirect()->route('messages.show', $conversation)
            ->with('success', 'Réponse envoyée.');
    }
}
