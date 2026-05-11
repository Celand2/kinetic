<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'bonus_amount',
        'status',
        'used_by',
        'used_at',
        'expires_at',
        'description',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
        'bonus_amount' => 'decimal:2',
    ];

    /**
     * Relationship: User who redeemed this code
     */
    public function usedByUser()
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    /**
     * Check if code is valid and can be redeemed
     */
    public function isValid()
    {
        return $this->status === 'active' && 
               $this->expires_at === null || 
               $this->expires_at->isFuture();
    }

    /**
     * Check if code has already been used
     */
    public function isUsed()
    {
        return $this->status === 'used' && $this->used_by !== null;
    }
}
