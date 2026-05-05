@extends('layouts.app')

@section('title', 'Edit Payment Method')

@section('content')
<h1>Edit Payment Method</h1>

<form action="{{ route('admin.payment-methods.update', $paymentMethod) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name">Name</label>
        <input id="name" name="name" value="{{ old('name', $paymentMethod->name) }}" required>
        @error('name')<span class="error">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label for="type">Type</label>
        <select id="type" name="type" required>
            <option value="mobile_money" {{ old('type', $paymentMethod->type) === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
            <option value="crypto" {{ old('type', $paymentMethod->type) === 'crypto' ? 'selected' : '' }}>Crypto</option>
            <option value="bank" {{ old('type', $paymentMethod->type) === 'bank' ? 'selected' : '' }}>Bank</option>
        </select>
        @error('type')<span class="error">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label for="details">Details</label>
        <textarea id="details" name="details" rows="4" required>{{ old('details', $paymentMethod->details) }}</textarea>
        @error('details')<span class="error">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label>
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}>
            Active
        </label>
    </div>
    <button type="submit" class="btn">Update</button>
</form>
@endsection