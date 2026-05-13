<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'direction',
        'balance_after',
        'status',
        'reference',
        'investment_id',
        'payment_method',
        'external_reference',
        'fee_amount',
        'processed_by',
        'processed_at',
        'description',
        'admin_notes',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'metadata' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function investment()
    {
        return $this->belongsTo(Investment::class)->withTrashed();
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Montant dans la devise originale (locale pour dépôts/retraits, USD pour le reste)
    public function getDisplayAmountAttribute(): float
    {
        if (isset($this->metadata['local_amount'])) {
            return (float) $this->metadata['local_amount'];
        }
        return (float) $this->amount;
    }

    // Devise d'affichage (locale pour dépôts/retraits, USD pour le reste)
    public function getDisplayCurrencyAttribute(): string
    {
        if (isset($this->metadata['local_currency'])) {
            return $this->metadata['local_currency'];
        }
        return 'USD';
    }

    // Montant formaté avec symbole de devise
    public function getFormattedAmountAttribute(): string
    {
        $currency = $this->display_currency;
        $amount   = $this->display_amount;

        if ($currency === 'USD') {
            return '$' . number_format($amount, 2);
        }
        return number_format($amount, 0, ',', ' ') . ' ' . $currency;
    }
}
