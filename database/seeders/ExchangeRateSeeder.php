<?php

namespace Database\Seeders;

use App\Models\ExchangeRate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Taux de change par rapport à l'USD (1 USD = X devise_locale)
        $rates = [
            'USD' => 1.0,          // 1 USD = 1 USD
            'EUR' => 0.92,         // 1 USD = 0.92 EUR
            'GBP' => 0.79,         // 1 USD = 0.79 GBP
            'JPY' => 149.50,       // 1 USD = 149.50 JPY
            'CAD' => 1.36,         // 1 USD = 1.36 CAD
            'AUD' => 1.53,         // 1 USD = 1.53 AUD
            'CHF' => 0.88,         // 1 USD = 0.88 CHF
            'CNY' => 7.24,         // 1 USD = 7.24 CNY
            'INR' => 83.12,        // 1 USD = 83.12 INR
            'MXN' => 17.05,        // 1 USD = 17.05 MXN
            'BRL' => 4.97,         // 1 USD = 4.97 BRL
            'ZAR' => 18.65,        // 1 USD = 18.65 ZAR
            'KES' => 131.50,       // 1 USD = 131.50 KES
            'NGN' => 486.50,       // 1 USD = 486.50 NGN
            'XOF' => 655.50,       // 1 USD = 655.50 XOF (Franc CFA Ouest Africain)
            'XAF' => 655.50,       // 1 USD = 655.50 XAF (Franc CFA Centrafrique)
            'EGP' => 30.90,        // 1 USD = 30.90 EGP
            'MAD' => 10.15,        // 1 USD = 10.15 MAD
            'TND' => 3.15,         // 1 USD = 3.15 TND
            'SEN' => 655.50,       // 1 USD = 655.50 SEN (Sénégal)
            'CDF' => 2650.00,      // 1 USD = 2650 CDF (Congo)
            'KZT' => 438.50,       // 1 USD = 438.50 KZT
            'PHP' => 55.75,        // 1 USD = 55.75 PHP
            'THB' => 35.30,        // 1 USD = 35.30 THB
            'VND' => 24500.00,     // 1 USD = 24500 VND
            'IDR' => 15750.00,     // 1 USD = 15750 IDR
            'MYR' => 4.70,         // 1 USD = 4.70 MYR
            'SGD' => 1.35,         // 1 USD = 1.35 SGD
        ];

        foreach ($rates as $currency => $rate) {
            ExchangeRate::updateOrCreate(
                ['currency' => $currency],
                [
                    'id' => Str::uuid(),
                    'rate_to_usd' => $rate,
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Taux de change initialisés avec succès !');
    }
}
