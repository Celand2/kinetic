<?php

namespace App\Models;

use App\Models\Investment;
use App\Models\TradingCycle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tranche extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trading_cycle_id',
        'name',
        'slug',
        'description',
        'min_amount',
        'max_amount',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
    ];

    public function tradingCycle()
    {
        return $this->belongsTo(TradingCycle::class);
    }

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
