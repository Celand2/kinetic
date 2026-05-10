@extends('layouts.admin')

@section('title', 'Edit Payment Method')

@section('content')
<h1 style="color:#c9a227; font-size:1.2rem; margin-bottom:1.25rem;">Edit Payment Method</h1>

<form action="{{ route('admin.payment-methods.update', $paymentMethod) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label class="form-label" for="name">Name</label>
        <input class="form-control" id="name" name="name" value="{{ old('name', $paymentMethod->name) }}" required placeholder="e.g. Lumicash">
        @error('name')<span class="form-feedback-error">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="type">Type</label>
        <select class="form-control" id="type" name="type" required>
            <option value="">-- Choose --</option>
            <option value="mobile_money" {{ old('type', $paymentMethod->type) === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
            <option value="crypto" {{ old('type', $paymentMethod->type) === 'crypto' ? 'selected' : '' }}>Crypto</option>
            <option value="bank" {{ old('type', $paymentMethod->type) === 'bank' ? 'selected' : '' }}>Bank</option>
        </select>
        @error('type')<span class="form-feedback-error">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="currency">Accepted Currency</label>
        <select class="form-control" id="currency" name="currency" required>
            <option value="USD" {{ old('currency', $paymentMethod->currency ?? 'USD') === 'USD' ? 'selected' : '' }}>USD — US Dollar</option>
            @foreach(\App\Models\ExchangeRate::all() as $rate)
                <option value="{{ $rate->currency }}" {{ old('currency', $paymentMethod->currency) === $rate->currency ? 'selected' : '' }}>
                    {{ $rate->currency }} — 1 USD = {{ number_format($rate->rate_to_usd, 0, ',', ' ') }} {{ $rate->currency }}
                </option>
            @endforeach
        </select>
        <span class="form-hint">The currency in which users will deposit using this method.</span>
        @error('currency')<span class="form-feedback-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="details">Instructions / Details</label>
        <textarea class="form-control" id="details" name="details" rows="4" required
                  placeholder="Number, IBAN, wallet address...">{{ old('details', $paymentMethod->details) }}</textarea>
        @error('details')<span class="form-feedback-error">{{ $message }}</span>@enderror
    </div>
    <div class="form-group" style="display:flex; align-items:center; gap:0.75rem;">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}>
        <label for="is_active" style="color:#b0bfd9; font-size:0.88rem; cursor:pointer;">Activate this payment method</label>
    </div>
    <button type="submit" class="btn btn-primary" style="width:100%;">Update</button>
</form>
@endsection