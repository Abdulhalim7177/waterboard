<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\Staff\AuthController;
use App\Http\Controllers\API\Staff\CustomerController as StaffCustomerController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Staff Authentication Routes
Route::prefix('v1/staff')->name('api.staff.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/user', [AuthController::class, 'user'])->name('user');
        
        // Staff Customer Management Routes
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/', [StaffCustomerController::class, 'index'])->name('index');
            Route::post('/', [StaffCustomerController::class, 'store'])->name('store');
            Route::get('/pending-customers', [StaffCustomerController::class, 'pendingCustomers'])->name('pending.customers');
            Route::get('/pending-updates', [StaffCustomerController::class, 'pendingUpdates'])->name('pending.updates');
            Route::get('/{customer}', [StaffCustomerController::class, 'show'])->name('show');
            Route::put('/{customer}', [StaffCustomerController::class, 'update'])->name('update');
            Route::delete('/{customer}', [StaffCustomerController::class, 'destroy'])->name('destroy');
            Route::post('/{customer}/approve', [StaffCustomerController::class, 'approve'])->name('approve');
            Route::post('/{customer}/reject', [StaffCustomerController::class, 'reject'])->name('reject');
            Route::post('/pending/{update}/approve', [StaffCustomerController::class, 'approvePending'])->name('pending.approve');
            Route::post('/pending/{update}/reject', [StaffCustomerController::class, 'rejectPending'])->name('pending.reject');
        });
    });
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::prefix('customers')->name('api.customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::post('/export', [CustomerController::class, 'export'])->name('export');
        Route::post('/import', [CustomerController::class, 'import'])->name('import');
        Route::get('/sample', [CustomerController::class, 'downloadSample'])->name('sample');
        Route::get('/pending', [CustomerController::class, 'pending'])->name('pending');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
        Route::post('/{customer}/approve', [CustomerController::class, 'approve'])->name('approve')->middleware('admin');
        Route::post('/{customer}/reject', [CustomerController::class, 'reject'])->name('reject')->middleware('admin');
        Route::post('/pending/{update}/approve', [CustomerController::class, 'approvePending'])->name('pending.approve')->middleware('admin');
        Route::post('/pending/{update}/reject', [CustomerController::class, 'rejectPending'])->name('pending.reject')->middleware('admin');
    });
});
