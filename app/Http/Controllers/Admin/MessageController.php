<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $query = Conversation::with(['user'])
            ->withCount('messages')
            ->orderByDesc('last_message_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('full_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $conversations = $query->paginate(30)->withQueryString();
        $unreadCount   = Conversation::where('unread_admin_count', '>', 0)->count();

        return view('admin.messages.index', compact('conversations', 'unreadCount'));
    }

    public function show(Conversation $conversation)
    {
        if ($conversation->unread_admin_count > 0) {
            $conversation->update(['unread_admin_count' => 0]);
        }

        $messages = $conversation->messages()->with('sender')->orderBy('created_at')->get();

        return view('admin.messages.show', compact('conversation', 'messages'));
    }

    public function reply(Request $request, Conversation $conversation)
    {
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
            'last_message_at'   => now(),
            'unread_user_count' => $conversation->unread_user_count + 1,
        ]);

        return redirect()->route('admin.messages.show', $conversation)
            ->with('success', 'Réponse envoyée.');
    }

    public function close(Conversation $conversation)
    {
        $conversation->update(['status' => 'resolved']);

        return redirect()->route('admin.messages.show', $conversation)
            ->with('success', 'Conversation marquée comme résolue.');
    }

    public function broadcast()
    {
        $users = User::where('role', '!=', 'super_admin')
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get();

        return view('admin.messages.broadcast', compact('users'));
    }

    public function sendBroadcast(Request $request)
    {
        $validated = $request->validate([
            'subject'    => 'required|string|max:200',
            'body'       => 'required|string|max:5000',
            'target'     => 'required|in:all,selected',
            'user_ids'   => 'required_if:target,selected|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $query = User::where('role', '!=', 'super_admin')->where('status', 'active');

        if ($validated['target'] === 'selected') {
            $query->whereIn('id', $validated['user_ids'] ?? []);
        }

        $users = $query->get();
        $count = 0;

        foreach ($users as $user) {
            $conversation = Conversation::create([
                'user_id'            => $user->id,
                'subject'            => $validated['subject'],
                'category'           => 'other',
                'status'             => 'open',
                'priority'           => 'normal',
                'last_message_at'    => now(),
                'unread_admin_count' => 0,
                'unread_user_count'  => 1,
            ]);

            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id'       => Auth::id(),
                'body'            => $validated['body'],
                'type'            => 'text',
            ]);

            $count++;
        }

        return redirect()->route('admin.messages.index')
            ->with('success', "Message diffusé à {$count} utilisateur(s).");
    }
}
