<?php

namespace App\Models;

use App\Models\Tranche;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradingCycle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'duration_days',
        'daily_profit_percent',
        'total_return_percent',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_days' => 'integer',
        'daily_profit_percent' => 'decimal:2',
        'total_return_percent' => 'decimal:2',
    ];

    public function tranches()
    {
        return $this->hasMany(Tranche::class);
    }

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
