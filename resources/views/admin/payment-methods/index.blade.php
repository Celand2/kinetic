@extends('layouts.app')

@section('title', 'Payment Methods')

@section('content')
<h1>Payment Methods</h1>
<a href="{{ route('admin.payment-methods.create') }}" class="btn">Add New Payment Method</a>

@if(session('success'))
    <div class="alert success">{{ session('success') }}</div>
@endif

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Details</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($paymentMethods as $method)
            <tr>
                <td>{{ $method->name }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $method->type)) }}</td>
                <td>{{ $method->details }}</td>
                <td>{{ $method->is_active ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('admin.payment-methods.edit', $method) }}">Edit</a>
                    <form action="{{ route('admin.payment-methods.destroy', $method) }}" method="POST" style="display:inline-block;">
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