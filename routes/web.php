<?php
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Fallback GET /logout : redirige vers login proprement
Route::get('/logout', function () {
    if (auth()->check()) {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
    return redirect()->route('login')->with('info', 'Vous avez été déconnecté.');
});

// Convenience: keep old dashboard path working for authenticated clients.
Route::redirect('/dashboard', '/client/dashboard');

require __DIR__.'/client.php';
require __DIR__.'/admin.php';


Route::get('/run-profits-cron-secure-9x27', function () {
    // On donne 5 minutes au script pour s'exécuter au cas où, pour éviter les coupures
    set_time_limit(300); 
    
    // On lance notre commande optimisée
    Artisan::call('profits:credit');
    
    return response()->json([
        'status' => 'success',
        'message' => 'Les profits ont été traités.',
        'output' => Artisan::output()
    ]);
});