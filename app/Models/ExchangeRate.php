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
        'rate_to_usd' => 'decimal:10',
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
        // Pas de round ici — on garde toute la précision
        return (float) bcmul((string)$usdAmount, (string)$rate->rate_to_usd, 10);
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
        // bcdiv pour une division précise sans perte de flottant
        return (float) bcdiv((string)$localAmount, (string)$rate->rate_to_usd, 10);
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
        // Pour les devises à grande valeur (BIF, CDF...) on arrondit à l'entier
        $decimals = $local >= 100 ? 0 : 2;
        return number_format($local, $decimals, ',', ' ') . ' ' . strtoupper($currency);
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