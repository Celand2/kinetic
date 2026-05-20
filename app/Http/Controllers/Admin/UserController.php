<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['investments', 'transactions', 'referrals']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|unique:users,phone,' . $user->id,
            'country' => 'required|string',
            'balance' => 'nullable|numeric|min:0',
            'profit_balance' => 'nullable|numeric|min:0',
            'referral_balance' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,frozen,blocked',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully!');
    }

    public function block(User $user)
    {
        $user->update(['status' => 'blocked']);
        return back()->with('success', 'User blocked successfully!');
    }

    public function unblock(User $user)
    {
        $user->update(['status' => 'active']);
        return back()->with('success', 'User unblocked successfully!');
    }

    public function delete(User $user)
    {
        $user->forceDelete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted permanently!');
    }
}
