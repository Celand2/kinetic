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

    public $timestamps = false;

    /**
     * Convertit un montant USD vers une devise locale.
     * Ex : fromUSD(100, 'BIF') → 290 000 si 1 USD = 2900 BIF
     */
    public static function fromUSD(float $usdAmount, string $currency): float
    {
        if ($currency === 'USD') return $usdAmount;
        $rate = static::where('currency', strtoupper($currency))->first();
        if (!$rate || $rate->rate_to_usd == 0) return $usdAmount;
        return round($usdAmount * (float) $rate->rate_to_usd, 2);
    }

    /**
     * Convertit un montant en devise locale vers USD.
     * Ex : toUSD(290000, 'BIF') → 100 si 1 USD = 2900 BIF
     */
    public static function toUSD(float $localAmount, string $currency): float
    {
        if ($currency === 'USD') return $localAmount;
        $rate = static::where('currency', strtoupper($currency))->first();
        if (!$rate || $rate->rate_to_usd == 0) return $localAmount;
        return round($localAmount / (float) $rate->rate_to_usd, 8);
    }

    /**
     * Formate un montant USD en devise locale avec symbole.
     * Ex : format(100, 'BIF') → "290 000 BIF"
     */
    public static function format(float $usdAmount, string $currency = 'USD'): string
    {
        if ($currency === 'USD') {
            return '$' . number_format($usdAmount, 2);
        }
        $local = static::fromUSD($usdAmount, $currency);
        return number_format($local, 0, ',', ' ') . ' ' . strtoupper($currency);
    }

    /**
     * Retourne le taux (1 USD = X devise locale).
     */
    public static function rate(string $currency): float
    {
        if ($currency === 'USD') return 1.0;
        $rate = static::where('currency', strtoupper($currency))->first();
        return $rate ? (float) $rate->rate_to_usd : 1.0;
    }
}
