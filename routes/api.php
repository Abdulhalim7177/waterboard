<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\Staff\AuthController;
use App\Http\Controllers\API\Staff\CustomerController as StaffCustomerController;
use App\Http\Controllers\Api\VendorController;

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

// Vendor Authentication and Dashboard Routes
Route::prefix('v1/vendor')->name('api.vendor.')->group(function () {
    Route::post('/register', [VendorController::class, 'register'])->name('register');
    Route::post('/login', [VendorController::class, 'login'])->name('login');

    Route::middleware('vendor.auth')->group(function () {
        Route::post('/logout', [VendorController::class, 'logout'])->name('logout');
        Route::get('/profile', [VendorController::class, 'profile'])->name('profile');
        Route::put('/profile', [VendorController::class, 'updateProfile'])->name('profile.update');
        Route::post('/password', [VendorController::class, 'changePassword'])->name('password.change');
        Route::get('/customer/info/{billingId}', [VendorController::class, 'getCustomerInfo'])->name('customer.info');
        Route::post('/payment', [VendorController::class, 'makePayment'])->name('payment');
    });
});

// Vendor Payment Routes
Route::prefix('v1/vendor/payments')->name('api.vendor.payments.')->middleware('vendor.auth')->group(function () {
    Route::post('/fund', [\App\Http\Controllers\API\VendorPaymentController::class, 'fundAccount'])->name('fund');
    Route::post('/fund/callback', [\App\Http\Controllers\API\VendorPaymentController::class, 'fundCallback'])->name('fund.callback');
    Route::post('/process', [\App\Http\Controllers\API\VendorPaymentController::class, 'initiatePayment'])->name('initiate');
    Route::get('/callback', [\App\Http\Controllers\API\VendorPaymentController::class, 'callback'])->name('callback');
    Route::get('/', [\App\Http\Controllers\API\VendorPaymentController::class, 'index'])->name('index');
    Route::get('/funding', [\App\Http\Controllers\API\VendorPaymentController::class, 'fundingHistory'])->name('funding');
    Route::get('/{id}', [\App\Http\Controllers\API\VendorPaymentController::class, 'show'])->name('show');
    Route::get('/funding/{id}', [\App\Http\Controllers\API\VendorPaymentController::class, 'showFunding'])->name('show.funding');
    Route::get('/payments/{id}', [\App\Http\Controllers\API\VendorPaymentController::class, 'showPayment'])->name('show.payment');
    Route::get('/customer/{customerId}', [\App\Http\Controllers\API\VendorPaymentController::class, 'customerPaymentDetails'])->name('customer.details');
    Route::get('/statistics', [\App\Http\Controllers\API\VendorPaymentController::class, 'statistics'])->name('statistics');
});

use App\Http\Controllers\Api\ReservoirController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\API\StaffVendorController;
use App\Http\Controllers\API\VendorPaymentController;

// Staff Vendor Management Routes
Route::prefix('v1/staff/vendors')->name('api.staff.vendors.')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [StaffVendorController::class, 'index'])->name('index');
    Route::get('/filtered', [StaffVendorController::class, 'filteredIndex'])->name('filtered.index');
    Route::get('/statistics', [StaffVendorController::class, 'statistics'])->name('statistics');
    Route::post('/', [StaffVendorController::class, 'store'])->name('store');
    Route::get('/{vendor}', [StaffVendorController::class, 'show'])->name('show');
    Route::put('/{vendor}', [StaffVendorController::class, 'update'])->name('update');
    Route::delete('/{vendor}', [StaffVendorController::class, 'destroy'])->name('destroy');
    Route::post('/{vendor}/approve', [StaffVendorController::class, 'approve'])->name('approve');
    Route::post('/{vendor}/reject', [StaffVendorController::class, 'reject'])->name('reject');
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

    // Employee Management Routes
    Route::prefix('employees')->name('api.employees.')->group(function () {
        // ... existing routes would go here, but we'll add a custom fetch route
    });
    
    Route::apiResource('reservoirs', ReservoirController::class);
});

// HRM Integration Routes
Route::prefix('v1/hrm')->name('api.hrm.')->middleware('auth:sanctum')->group(function () {
    Route::get('/employees/fetch', [EmployeeController::class, 'fetchFromHrm'])->name('employees.fetch');
});
