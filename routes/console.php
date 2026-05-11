<?php

use App\Console\Commands\CreditDailyProfits;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Créditer les profits journaliers toutes les 24H à minuit
Schedule::command('profits:credit')->dailyAt('00:00');
