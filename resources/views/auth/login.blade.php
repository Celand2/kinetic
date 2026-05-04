@extends('layouts.app')

@section('title', 'Login - KINETIC')

@section('content')
<div style="max-width: 500px; margin: 3rem auto;">
    <div class="card">
        <div class="card-header">Welcome Back</div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
                @error('email')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                @error('password')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>

            <p style="text-align: center; margin-top: 1.5rem; color: #b0bfd9;">
                Don't have an account? <a href="{{ route('register') }}" style="color: #c9a227;">Register here</a>
            </p>
        </form>
    </div>
</div>
@endsection
