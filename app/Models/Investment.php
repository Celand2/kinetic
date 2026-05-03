<?php

namespace App\Models;

use App\Models\TradingCycle;
use App\Models\Tranche;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'trading_cycle_id',
        'tranche_id',
        'amount',
        'daily_profit_rate',
        'total_return_rate',
        'duration_days',
        'total_profit_credited',
        'days_credited',
        'started_at',
        'ends_at',
        'last_profit_credited_at',
        'status',
        'reference',
        'admin_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'daily_profit_rate' => 'decimal:2',
        'total_return_rate' => 'decimal:2',
        'total_profit_credited' => 'decimal:2',
        'started_at' => 'datetime',
        'ends_at' => 'datetime',
        'last_profit_credited_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tradingCycle()
    {
        return $this->belongsTo(TradingCycle::class);
    }

    public function tranche()
    {
        return $this->belongsTo(Tranche::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
