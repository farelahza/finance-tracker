<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SavingsContributionController;
use App\Http\Controllers\SavingsGoalController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

Route::get(
    '/transactions/export',
    [TransactionController::class, 'export']
)->name('transactions.export');

    // Dashboard
    Route::get('/dashboard', [TransactionController::class, 'dashboard'])
        ->name('dashboard');

    // CRUD transaksi
    Route::resource('transactions', TransactionController::class);

    // Target Tabungan (terpisah dari transaksi dan saldo utama)
    Route::get('/savings-goals', [SavingsGoalController::class, 'index'])
        ->name('savings-goals.index');
    Route::get('/savings-goals/create', [SavingsGoalController::class, 'create'])
        ->name('savings-goals.create');
    Route::post('/savings-goals', [SavingsGoalController::class, 'store'])
        ->name('savings-goals.store');

    Route::get('/savings-goals/{goal}', [SavingsGoalController::class, 'show'])
        ->whereNumber('goal')->name('savings-goals.show');
    Route::get('/savings-goals/{goal}/edit', [SavingsGoalController::class, 'edit'])
        ->whereNumber('goal')->name('savings-goals.edit');
    Route::put('/savings-goals/{goal}', [SavingsGoalController::class, 'update'])
        ->whereNumber('goal')->name('savings-goals.update');
    Route::delete('/savings-goals/{goal}', [SavingsGoalController::class, 'destroy'])
        ->whereNumber('goal')->name('savings-goals.destroy');

    Route::post('/savings-goals/{goal}/contributions', [SavingsContributionController::class, 'store'])
        ->whereNumber('goal')->name('savings-goals.contributions.store');
    Route::delete('/savings-goals/{goal}/contributions/{contribution}', [SavingsContributionController::class, 'destroy'])
        ->whereNumber(['goal', 'contribution'])->name('savings-goals.contributions.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('throttle:10,1')
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

Route::get(
    '/export-pdf',
    [TransactionController::class, 'exportPdf']
)->name('export.pdf');

require __DIR__.'/auth.php';