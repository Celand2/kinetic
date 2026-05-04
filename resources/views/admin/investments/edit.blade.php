@extends('layouts.app')

@section('title', 'Edit Investment - KINETIC Admin')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h1 style="margin-bottom: 1rem; color: #c9a227;">Edit Investment</h1>
    <a href="{{ route('admin.investments') }}" class="back-link">← Retour aux investissements</a>

    <div class="card">
        <form method="POST" action="{{ route('admin.investments.update', $investment) }}">
            @csrf
            @method('PUT')

            <div style="background: rgba(201, 162, 39, 0.05); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <div style="color: #b0bfd9;">
                    <strong>Reference:</strong> {{ $investment->reference }}<br>
                    <strong>User:</strong> {{ $investment->user->full_name }}<br>
                    <strong>Amount:</strong> ${{ number_format($investment->amount, 2) }}<br>
                    <strong>Period:</strong> {{ $investment->started_at->format('M d') }} - {{ $investment->ends_at->format('M d, Y') }}
                </div>
            </div>

            <div class="form-group">
                <label for="total_profit_credited">Total Profit Credited ($)</label>
                <input type="number" id="total_profit_credited" name="total_profit_credited" step="0.01" value="{{ $investment->total_profit_credited }}">
                @error('total_profit_credited')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="pending" {{ $investment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="active" {{ $investment->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ $investment->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $investment->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="admin_notes">Admin Notes</label>
                <textarea class="form-control" id="admin_notes" name="admin_notes" rows="4">{{ $investment->admin_notes }}</textarea>
                @error('admin_notes')<span class="form-feedback-error">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Save Changes</button>
        </form>
    </div>
</div>
@endsection
