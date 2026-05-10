<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users|max:20',
            'country' => 'required|string|max:100',
            'password' => 'required|string|min:8|confirmed',
            'referral_code' => 'nullable|string|exists:users,referral_code',
        ]);

        $referralCode = 'KTS-' . strtoupper(Str::random(8));
        $referrer = null;

        if ($request->filled('referral_code')) {
            $referrer = User::where('referral_code', $request->input('referral_code'))->first();
        } elseif (session('referrer_id')) {
            $referrer = User::find(session('referrer_id'));
        }

        $user = User::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'country' => $validated['country'],
            'password' => Hash::make($validated['password']),
            'referral_code' => $referralCode,
            'referred_by_id' => $referrer?->id,
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->status !== 'active') {
                Auth::logout();
                return back()->withErrors(['email' => 'Compte bloqué ou gelé.'])->onlyInput('email');
            }

            $redirectRoute = in_array(Auth::user()->role, ['super_admin', 'admin'])
                ? 'admin.dashboard'
                : 'dashboard';

            return redirect()->intended(route($redirectRoute))
                ->with('success', 'Logged in successfully!');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }
}
