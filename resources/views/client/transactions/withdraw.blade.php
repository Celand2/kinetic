@extends('layouts.client')

@section('title', 'Withdrawal Request - KINETIC')

@section('content')
<div style="max-width: 640px; margin: 0 auto;">
    <h1 style="margin-bottom: 2rem; color: #c9a227;">Withdrawal Request</h1>

    <div class="card">
        <form method="POST" action="{{ route('transactions.withdraw.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="amount">Amount ($)</label>
                <input type="number" id="amount" name="amount" min="1" step="0.01" value="{{ old('amount') }}" required>
                <small style="color: #b0bfd9;">A 10% withdrawal fee will be applied.</small>
                @error('amount')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="payment_method">Withdrawal Method</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="">-- Choose a method --</option>
                    <option value="lumicash" {{ old('payment_method') === 'lumicash' ? 'selected' : '' }}>Lumicash</option>
                    <option value="bancobu_enoti" {{ old('payment_method') === 'bancobu_enoti' ? 'selected' : '' }}>Banque / Banquobu</option>
                </select>
                @error('payment_method')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="wallet_details">Wallet / Account Details</label>
                <textarea id="wallet_details" name="wallet_details" rows="4" required placeholder="Enter your wallet address, IBAN, account number, or other payment details...">{{ old('wallet_details') }}</textarea>
                @error('wallet_details')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            
            

            <button type="submit" class="btn" style="width: 100%;">Submit Withdrawal Request</button>
        </form>
    </div>
</div>
@endsection
