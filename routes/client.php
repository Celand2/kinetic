<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::prefix('client')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('investments', InvestmentController::class);

    Route::get('/transactions/deposit', [TransactionController::class, 'createDeposit'])->name('transactions.deposit');
    Route::post('/transactions/deposit', [TransactionController::class, 'storeDeposit'])->name('transactions.deposit.store');
    Route::get('/transactions/withdraw', [TransactionController::class, 'createWithdrawal'])->name('transactions.withdraw');
    Route::post('/transactions/withdraw', [TransactionController::class, 'storeWithdrawal'])->name('transactions.withdraw.store');
    Route::resource('transactions', TransactionController::class)->only(['index', 'show']);

    Route::get('/referral', [ReferralController::class, 'dashboard'])->name('referral.dashboard');
    Route::get('/referral-link', [ReferralController::class, 'generateLink'])->name('referral.link');

    Route::get('/messages/inbox', [MessageController::class, 'inbox'])->name('messages.inbox');
    Route::get('/messages/sent', [MessageController::class, 'sent'])->name('messages.sent');
    Route::resource('messages', MessageController::class)->except(['index']);
    Route::post('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
});
