@extends('layouts.app')

@section('title', 'Deposit Request - KINETIC')

@section('content')
<div style="max-width: 640px; margin: 0 auto;">
    <h1 style="margin-bottom: 2rem; color: #c9a227;">Deposit Request</h1>

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
                    <option value="lumicash" {{ old('payment_method') === 'lumicash' ? 'selected' : '' }}>Lumicash</option>
                    <option value="bancobu_enoti" {{ old('payment_method') === 'bancobu_enoti' ? 'selected' : '' }}>Banque / Banquobu</option>
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
