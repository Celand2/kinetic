@extends('layouts.client')

@section('title', 'Redeem Bonus Code - KINETIC')

@section('content')
<div style="max-width: 400px; margin: 0 auto;">
    <h1 style="color:#c9a227; font-size:1.2rem; margin-bottom:1.25rem;">Échanger un code bonus</h1>

    <div class="card">
        <form method="POST" action="{{ route('redeem-bonus.redeem') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="code">Code bonus</label>
                <input type="text" class="form-control" id="code" name="code" required placeholder="Ex : BONUS-ABC12345" style="text-transform: uppercase;">
                @error('code')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">Échanger le code</button>
        </form>
    </div>

    <p style="text-align:center; margin-top:1.5rem; color:#b0bfd9; font-size:0.88rem;">
        Les codes bonus peuvent être utilisés une seule fois et expirent selon les conditions définies par l'admin.
    </p>
</div>
@endsection