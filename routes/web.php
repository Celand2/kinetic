<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\InvestmentController as AdminInvestmentController;
use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    return view('index');
})->name('home');

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Routes (Protected)
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Investments
    Route::resource('investments', InvestmentController::class);
    Route::get('/investments/{investment}', [InvestmentController::class, 'show'])->name('investments.show');

    // Transactions
    Route::resource('transactions', TransactionController::class)->only(['index', 'show']);

    // Referral System
    Route::get('/referral', [ReferralController::class, 'dashboard'])->name('referral.dashboard');
    Route::get('/referral-link', [ReferralController::class, 'generateLink'])->name('referral.link');

    // Messages
    Route::get('/messages/inbox', [MessageController::class, 'inbox'])->name('messages.inbox');
    Route::get('/messages/sent', [MessageController::class, 'sent'])->name('messages.sent');
    Route::resource('messages', MessageController::class)->except(['index']);
    Route::post('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.read');

    // Admin Routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        
        // User Management
        Route::resource('users', AdminUserController::class);
        Route::post('/users/{user}/block', [AdminUserController::class, 'block'])->name('users.block');
        Route::post('/users/{user}/unblock', [AdminUserController::class, 'unblock'])->name('users.unblock');
        Route::delete('/users/{user}', [AdminUserController::class, 'delete'])->name('users.delete');

        // Finance Management
        Route::get('/finance/transactions', [FinanceController::class, 'transactions'])->name('finance.transactions');
        Route::post('/finance/transactions/{transaction}/approve', [FinanceController::class, 'approveTransaction'])->name('finance.approve');
        Route::post('/finance/transactions/{transaction}/reject', [FinanceController::class, 'rejectTransaction'])->name('finance.reject');
        Route::post('/users/{user}/adjust-balance', [FinanceController::class, 'manualAdjustment'])->name('finance.adjust');

        // Investment Management
        Route::get('/cycles', [AdminInvestmentController::class, 'cycles'])->name('cycles');
        Route::get('/cycles/create', [AdminInvestmentController::class, 'createCycle'])->name('cycles.create');
        Route::post('/cycles', [AdminInvestmentController::class, 'storeCycle'])->name('cycles.store');
        Route::get('/cycles/{cycle}/edit', [AdminInvestmentController::class, 'editCycle'])->name('cycles.edit');
        Route::put('/cycles/{cycle}', [AdminInvestmentController::class, 'updateCycle'])->name('cycles.update');
        Route::delete('/cycles/{cycle}', [AdminInvestmentController::class, 'deleteCycle'])->name('cycles.delete');

        // Tranches Management
        Route::get('/cycles/{cycle}/tranches', [AdminInvestmentController::class, 'tranches'])->name('tranches');
        Route::get('/cycles/{cycle}/tranches/create', [AdminInvestmentController::class, 'createTranche'])->name('tranches.create');
        Route::post('/cycles/{cycle}/tranches', [AdminInvestmentController::class, 'storeTranche'])->name('tranches.store');
        Route::get('/tranches/{tranche}/edit', [AdminInvestmentController::class, 'editTranche'])->name('tranches.edit');
        Route::put('/tranches/{tranche}', [AdminInvestmentController::class, 'updateTranche'])->name('tranches.update');
        Route::delete('/tranches/{tranche}', [AdminInvestmentController::class, 'deleteTranche'])->name('tranches.delete');

        // Investments Monitoring
        Route::get('/investments', [AdminInvestmentController::class, 'investments'])->name('investments');
        Route::get('/investments/{investment}/edit', [AdminInvestmentController::class, 'editInvestment'])->name('investments.edit');
        Route::put('/investments/{investment}', [AdminInvestmentController::class, 'updateInvestment'])->name('investments.update');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
        Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
        Route::post('/notifications/send', [NotificationController::class, 'send'])->name('notifications.send');
    });
});
