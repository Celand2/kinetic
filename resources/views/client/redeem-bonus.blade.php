@extends('layouts.client')

@section('title', __('bonus.title'))

@section('content')
<div style="max-width: 400px; margin: 0 auto;">
    <h1 style="color:#c9a227; font-size:1.2rem; margin-bottom:1.25rem;">{{ __('bonus.heading') }}</h1>

    <div class="card">
        <form method="POST" action="{{ route('redeem-bonus.redeem') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="code">{{ __('bonus.code') }}</label>
                <input type="text" class="form-control" id="code" name="code" required placeholder="{{ __('bonus.placeholder') }}" style="text-transform: uppercase;">
                @error('code')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">{{ __('bonus.submit') }}</button>
        </form>
    </div>

    <p style="text-align:center; margin-top:1.5rem; color:#b0bfd9; font-size:0.88rem;">
        {{ __('bonus.help') }}
    </p>
</div>
@endsection
