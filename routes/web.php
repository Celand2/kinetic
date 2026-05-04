<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    return view('index');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Convenience: keep old dashboard path working for authenticated clients.
Route::redirect('/dashboard', '/client/dashboard');

require __DIR__.'/client.php';
require __DIR__.'/admin.php';
