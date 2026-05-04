<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\InvestmentController as AdminInvestmentController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', AdminUserController::class);
    Route::post('/users/{user}/block', [AdminUserController::class, 'block'])->name('users.block');
    Route::post('/users/{user}/unblock', [AdminUserController::class, 'unblock'])->name('users.unblock');
    Route::delete('/users/{user}', [AdminUserController::class, 'delete'])->name('users.delete');

    Route::get('/finance/transactions', [FinanceController::class, 'transactions'])->name('finance.transactions');
    Route::post('/finance/transactions/{transaction}/approve', [FinanceController::class, 'approveTransaction'])->name('finance.approve');
    Route::post('/finance/transactions/{transaction}/reject', [FinanceController::class, 'rejectTransaction'])->name('finance.reject');
    Route::post('/users/{user}/adjust-balance', [FinanceController::class, 'manualAdjustment'])->name('finance.adjust');

    Route::get('/cycles', [AdminInvestmentController::class, 'cycles'])->name('cycles');
    Route::get('/cycles/create', [AdminInvestmentController::class, 'createCycle'])->name('cycles.create');
    Route::post('/cycles', [AdminInvestmentController::class, 'storeCycle'])->name('cycles.store');
    Route::get('/cycles/{cycle}/edit', [AdminInvestmentController::class, 'editCycle'])->name('cycles.edit');
    Route::put('/cycles/{cycle}', [AdminInvestmentController::class, 'updateCycle'])->name('cycles.update');
    Route::delete('/cycles/{cycle}', [AdminInvestmentController::class, 'deleteCycle'])->name('cycles.delete');

    Route::get('/cycles/{cycle}/tranches', [AdminInvestmentController::class, 'tranches'])->name('tranches');
    Route::get('/cycles/{cycle}/tranches/create', [AdminInvestmentController::class, 'createTranche'])->name('tranches.create');
    Route::post('/cycles/{cycle}/tranches', [AdminInvestmentController::class, 'storeTranche'])->name('tranches.store');
    Route::get('/tranches/{tranche}/edit', [AdminInvestmentController::class, 'editTranche'])->name('tranches.edit');
    Route::put('/tranches/{tranche}', [AdminInvestmentController::class, 'updateTranche'])->name('tranches.update');
    Route::delete('/tranches/{tranche}', [AdminInvestmentController::class, 'deleteTranche'])->name('tranches.delete');

    Route::get('/investments', [AdminInvestmentController::class, 'investments'])->name('investments');
    Route::get('/investments/{investment}/edit', [AdminInvestmentController::class, 'editInvestment'])->name('investments.edit');
    Route::put('/investments/{investment}', [AdminInvestmentController::class, 'updateInvestment'])->name('investments.update');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications/send', [NotificationController::class, 'send'])->name('notifications.send');
});
