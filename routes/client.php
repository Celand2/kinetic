<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('client')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/investments/cycles/{cycle}/tranches', [InvestmentController::class, 'showTranches'])->name('investments.cycle.tranches');
    Route::get('/investments/cycles/{cycle}/tranches/{tranche}/invest', [InvestmentController::class, 'invest'])->name('investments.invest');
    Route::resource('investments', InvestmentController::class);

    Route::get('/transactions/deposit', [TransactionController::class, 'createDeposit'])->name('transactions.deposit');
    Route::post('/transactions/deposit', [TransactionController::class, 'storeDeposit'])->name('transactions.deposit.store');
    Route::get('/transactions/withdraw', [TransactionController::class, 'createWithdrawal'])->name('transactions.withdraw');
    Route::post('/transactions/withdraw', [TransactionController::class, 'storeWithdrawal'])->name('transactions.withdraw.store');
    Route::resource('transactions', TransactionController::class)->only(['index', 'show']);

    Route::get('/referral', [ReferralController::class, 'dashboard'])->name('referral.dashboard');
    Route::get('/referral-link', [ReferralController::class, 'generateLink'])->name('referral.link');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}/reply', [MessageController::class, 'reply'])->name('messages.reply');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

    Route::get('/redeem-bonus', [\App\Http\Controllers\BonusController::class, 'show'])->name('redeem-bonus.show');
    Route::post('/redeem-bonus', [\App\Http\Controllers\BonusController::class, 'redeem'])->name('redeem-bonus.redeem');
});
