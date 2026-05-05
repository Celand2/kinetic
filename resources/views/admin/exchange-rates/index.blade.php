@extends('layouts.app')

@section('title', 'Exchange Rates')

@section('content')
<h1>Exchange Rates</h1>
<a href="{{ route('admin.exchange-rates.create') }}" class="btn">Add Rate</a>

@if(session('success'))
    <div class="alert success">{{ session('success') }}</div>
@endif

<table>
    <thead>
        <tr>
            <th>Currency</th>
            <th>Rate to USD</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($exchangeRates as $rate)
            <tr>
                <td>{{ $rate->currency }}</td>
                <td>{{ number_format($rate->rate_to_usd, 6) }}</td>
                <td>{{ $rate->updated_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.exchange-rates.edit', $rate) }}">Edit</a>
                    <form action="{{ route('admin.exchange-rates.destroy', $rate) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection