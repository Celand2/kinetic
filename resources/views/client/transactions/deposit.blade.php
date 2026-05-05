@extends('layouts.app')

@section('title', 'Deposit Request - KINETIC')

@section('content')
<div style="max-width: 640px; margin: 0 auto;">
    <h1 style="margin-bottom: 2rem; color: #c9a227;">Deposit Request</h1>

    @if($paymentMethods->count())
        <div class="card" style="margin-bottom: 1.5rem;">
            <div style="padding: 1rem;">
                <h2 style="margin:0 0 0.5rem; font-size:1.1rem;">Available Payment Methods</h2>
                <ul style="list-style:none; padding:0; margin:0;">
                    @foreach($paymentMethods as $method)
                        <li style="margin-bottom: 0.75rem; padding: 0.75rem; background:#121212; border:1px solid rgba(255,255,255,.08);">
                            <strong>{{ $method->name }}</strong>
                            <div>{{ ucfirst(str_replace('_', ' ', $method->type)) }}</div>
                            <div style="font-size:0.95rem; color:#bacbcb; margin-top:0.25rem;">{{ $method->details }}</div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('transactions.deposit.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="amount">Amount ($)</label>
                <input type="number" id="amount" name="amount" min="1" step="0.01" value="{{ old('amount') }}" required>
                @error('amount')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="">-- Choose a method --</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->name }}" {{ old('payment_method') === $method->name ? 'selected' : '' }}>
                            {{ $method->name }} ({{ $method->type }})
                        </option>
                    @endforeach
                </select>
                @error('payment_method')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="screenshot">Upload Proof (required)</label>
                <input type="file" id="screenshot" name="screenshot" accept="image/*" required>
                <small class="form-hint">Please upload a clear screenshot of your payment confirmation</small>
                @error('screenshot')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="description">Notes</label>
                <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn" style="width: 100%;">Submit Deposit Request</button>
        </form>
    </div>
</div>
@endsection
