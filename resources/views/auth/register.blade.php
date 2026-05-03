@extends('layouts.app')

@section('title', 'Register - KINETIC')

@section('content')
<div style="max-width: 500px; margin: 3rem auto;">
    <div class="card">
        <div class="card-header">Create Your Account</div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" required value="{{ old('full_name') }}">
                @error('full_name')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required value="{{ old('email') }}">
                @error('email')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required value="{{ old('phone') }}">
                @error('phone')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" required value="{{ old('country') }}">
                @error('country')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                @error('password')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn" style="width: 100%;">Create Account</button>

            <p style="text-align: center; margin-top: 1.5rem; color: #b0bfd9;">
                Already have an account? <a href="{{ route('login') }}" style="color: #c9a227;">Login here</a>
            </p>
        </form>
    </div>
</div>
@endsection
