<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FeePaymentController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MpesaController;
Route::get('/payment/register', [MpesaController::class, 'registerUrls'])->name('mpesa.register');

Route::get('/payment/register',  [MpesaController::class, 'registerUrls'])->name('mpesa.register');
Route::post('/payment/validate', [MpesaController::class, 'validation'])->name('mpesa.validation');
Route::post('/payment/confirm',  [MpesaController::class, 'confirmation'])->name('mpesa.confirmation');
Route::get('/payment/debug',     [MpesaController::class, 'debug'])->name('mpesa.debug');

// Public
Route::get('/', fn() => view('welcome'));
Route::view('/profile', 'profile')->middleware(['auth'])->name('profile');

// Logout — defined early so it's always registered regardless of auth.php load order
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// All authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Students
    Route::resource('students', StudentController::class);

    // Fee Structures — bulk-copy BEFORE resource
    Route::post('/fee-structures/bulk-copy', [FeeStructureController::class, 'bulkCreate'])
        ->name('fee-structures.bulk-copy');
    Route::resource('fee-structures', FeeStructureController::class);

    // Fee Payments — outstanding BEFORE resource
    Route::get('/fee-payments/outstanding', [FeePaymentController::class, 'outstanding'])
        ->name('fee-payments.outstanding');
    Route::resource('fee-payments', FeePaymentController::class);

    // Transactions — custom routes BEFORE resource to avoid {transaction} wildcard
    Route::get('/transactions/income',   [TransactionController::class, 'income'])  ->name('transactions.income');
    Route::get('/transactions/expenses', [TransactionController::class, 'expenses'])->name('transactions.expenses');
    Route::resource('transactions', TransactionController::class);

    // Reports
    Route::get('/reports',                 [ReportController::class, 'index'])         ->name('reports.index');
    Route::get('/reports/fee-collection',  [ReportController::class, 'feeCollection']) ->name('reports.fee-collection');
    Route::get('/reports/defaulters',      [ReportController::class, 'defaulters'])    ->name('reports.defaulters');
    Route::get('/reports/income-expense',  [ReportController::class, 'incomeExpense']) ->name('reports.income-expense');
    Route::get('/reports/daily',           [ReportController::class, 'daily'])         ->name('reports.daily');
    Route::get('/reports/payment-methods', [ReportController::class, 'paymentMethods'])->name('reports.payment-methods');

});



// Auth routes (login, register, password reset, etc.)
// logout is already defined above so auth.php's duplicate won't conflict
require __DIR__.'/auth.php';