<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\SoftDeletes;

class ReferralCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'source_user_id',
        'level',
        'source_amount',
        'rate_percent',
        'commission_amount',
        'source_transaction_id',
        'credit_transaction_id',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'source_amount' => 'decimal:2',
        'rate_percent' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function beneficiary()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function sourceUser()
    {
        return $this->belongsTo(User::class, 'source_user_id');
    }

    public function sourceTransaction()
    {
        return $this->belongsTo(Transaction::class, 'source_transaction_id');
    }

    public function creditTransaction()
    {
        return $this->belongsTo(Transaction::class, 'credit_transaction_id');
    }
}
