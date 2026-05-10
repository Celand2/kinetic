@extends('layouts.admin')

@section('title', 'Add Exchange Rate')

@section('content')
<h1>Add Exchange Rate</h1>

<form action="{{ route('admin.exchange-rates.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="currency">Currency</label>
        <input id="currency" name="currency" value="{{ old('currency') }}" required>
        @error('currency')<span class="error">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label for="rate_to_usd">Rate to USD</label>
        <input id="rate_to_usd" name="rate_to_usd" type="number" step="0.000001" value="{{ old('rate_to_usd') }}" required>
        @error('rate_to_usd')<span class="error">{{ $message }}</span>@enderror
    </div>
    <button type="submit" class="btn">Save</button>
</form>
@endsection