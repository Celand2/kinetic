@extends('layouts.app')

@section('title', 'Create Cycle - KINETIC Admin')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h1 style="margin-bottom: 2rem; color: #c9a227;">Create Trading Cycle</h1>

    <div class="card">
        <form method="POST" action="{{ route('admin.cycles.store') }}">
            @csrf

            <div class="form-group">
                <label for="name">Cycle Name</label>
                <input type="text" id="name" name="name" required>
                @error('name')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" required>
                @error('slug')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"></textarea>
                @error('description')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="duration_days">Duration (Days)</label>
                <input type="number" id="duration_days" name="duration_days" required>
                @error('duration_days')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="daily_profit_percent">Daily Profit %</label>
                <input type="number" id="daily_profit_percent" name="daily_profit_percent" step="0.01" required>
                @error('daily_profit_percent')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="total_return_percent">Total Return %</label>
                <input type="number" id="total_return_percent" name="total_return_percent" step="0.01" required>
                @error('total_return_percent')<span style="color: #ef5350;">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn" style="width: 100%;">Create Cycle</button>
        </form>
    </div>
</div>
@endsection
