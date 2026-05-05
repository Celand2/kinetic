<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'currency',
        'rate_to_usd',
        'updated_at',
    ];

    protected $casts = [
        'rate_to_usd' => 'decimal:6',
        'updated_at' => 'datetime',
    ];

    public $timestamps = false; // Only updated_at
}
