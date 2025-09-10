<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Staff\GisController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Staff\TariffController;
use App\Http\Controllers\Customer\BillController;
use App\Http\Controllers\Staff\BillingController;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Staff\CategoryController;
use App\Http\Controllers\Staff\LocationController;
use App\Http\Controllers\Staff\ComplaintController;
use App\Http\Controllers\Staff\AnalyticsController;
use App\Http\Controllers\Staff\CustomerCreationController;
use App\Http\Controllers\Staff\VendorController as StaffVendorController;
use App\Http\Controllers\VendorPaymentController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('mngr-secure-9374')->name('staff.')->middleware(['auth:staff', 'restrict.login'])->group(function () {
    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');

    // Analytics Routes
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // Staff Management
    Route::get('/staff', [StaffController::class, 'staff'])->name('staff.index');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::put('/staff/{staff}/approve', [StaffController::class, 'approve'])->name('staff.approve');
    Route::put('/staff/{staff}/reject', [StaffController::class, 'reject'])->name('staff.reject');
    Route::put('/staff/{staff}/assign-roles', [StaffController::class, 'assignRoles'])->name('staff.assign-roles');
    Route::put('/staff/{staff}/remove-roles', [StaffController::class, 'removeRoles'])->name('staff.remove-roles');

    // Role Management
    Route::get('/roles', [StaffController::class, 'roles'])->name('roles.index');
    Route::post('/roles', [StaffController::class, 'storeRole'])->name('roles.store');
    Route::put('/roles/{role}', [StaffController::class, 'updateRole'])->name('roles.update');
    Route::delete('/roles/{role}', [StaffController::class, 'destroyRole'])->name('roles.destroy');

    // Permission Management
    Route::get('/permissions', [StaffController::class, 'permissions'])->name('permissions.index');
    Route::post('/permissions', [StaffController::class, 'storePermission'])->name('permissions.store');
    Route::put('/permissions/{permission}', [StaffController::class, 'updatePermission'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [StaffController::class, 'destroyPermission'])->name('permissions.destroy');

    // Location Management
    Route::get('/lgas', [LocationController::class, 'lgas'])->name('lgas.index');
    Route::post('/lgas', [LocationController::class, 'storeLga'])->name('lgas.store');
    Route::put('/lgas/{lga}', [LocationController::class, 'updateLga'])->name('lgas.update');
    Route::delete('/lgas/{lga}', [LocationController::class, 'destroyLga'])->name('lgas.destroy');
    Route::put('/lgas/{lga}/approve', [LocationController::class, 'approveLga'])->name('lgas.approve');
    Route::put('/lgas/{lga}/reject', [LocationController::class, 'rejectLga'])->name('lgas.reject');

    Route::get('/wards', [LocationController::class, 'wards'])->name('wards.index');
    Route::post('/wards', [LocationController::class, 'storeWard'])->name('wards.store');
    Route::put('/wards/{ward}', [LocationController::class, 'updateWard'])->name('wards.update');
    Route::delete('/wards/{ward}', [LocationController::class, 'destroyWard'])->name('wards.destroy');
    Route::put('/wards/{ward}/approve', [LocationController::class, 'approveWard'])->name('wards.approve');
    Route::put('/wards/{ward}/reject', [LocationController::class, 'rejectWard'])->name('wards.reject');

    Route::get('/areas', [LocationController::class, 'areas'])->name('areas.index');
    Route::post('/areas', [LocationController::class, 'storeArea'])->name('areas.store');
    Route::put('/areas/{area}', [LocationController::class, 'updateArea'])->name('areas.update');
    Route::delete('/areas/{area}', [LocationController::class, 'destroyArea'])->name('areas.destroy');
    Route::put('/areas/{area}/approve', [LocationController::class, 'approveArea'])->name('areas.approve');
    Route::put('/areas/{area}/reject', [LocationController::class, 'rejectArea'])->name('areas.reject');

    // Category Management
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::put('/categories/{category}/approve', [CategoryController::class, 'approve'])->name('categories.approve');
    Route::put('/categories/{category}/reject', [CategoryController::class, 'reject'])->name('categories.reject');

    // Tariff Management
    Route::get('/tariffs', [TariffController::class, 'index'])->name('tariffs.index');
    Route::post('/tariffs', [TariffController::class, 'store'])->name('tariffs.store');
    Route::put('/tariffs/{tariff}', [TariffController::class, 'update'])->name('tariffs.update');
    Route::delete('/tariffs/{tariff}', [TariffController::class, 'destroy'])->name('tariffs.destroy');
    Route::put('/tariffs/{tariff}/approve', [TariffController::class, 'approve'])->name('tariffs.approve');
    Route::put('/tariffs/{tariff}/reject', [TariffController::class, 'reject'])->name('tariffs.reject');

    // Customer Management
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerCreationController::class, 'index'])->name('index');
        Route::post('/export', [CustomerCreationController::class, 'export'])->name('export');
        Route::post('/import', [CustomerCreationController::class, 'import'])->name('import');
        Route::get('/sample', [CustomerCreationController::class, 'downloadSample'])->name('sample');
        Route::get('/pending', [CustomerCreationController::class, 'pending'])->name('pending');
        Route::get('/create/personal', [CustomerCreationController::class, 'personal'])->name('create.personal');
        Route::post('/create/personal', [CustomerCreationController::class, 'storePersonal'])->name('store.personal');
        Route::get('/create/address', [CustomerCreationController::class, 'address'])->name('create.address');
        Route::post('/create/address', [CustomerCreationController::class, 'storeAddress'])->name('store.address');
        Route::get('/create/billing', [CustomerCreationController::class, 'billing'])->name('create.billing');
        Route::post('/create/billing', [CustomerCreationController::class, 'storeBilling'])->name('store.billing');
        Route::get('/create/location', [CustomerCreationController::class, 'location'])->name('create.location');
        Route::post('/create/location', [CustomerCreationController::class, 'storeLocation'])->name('store.location');
        Route::get('/{customer}', [CustomerCreationController::class, 'show'])->name('show');
        Route::delete('/{customer}', [CustomerCreationController::class, 'destroy'])->name('destroy');
        Route::post('/{customer}/approve', [CustomerCreationController::class, 'approve'])->name('approve');
        Route::post('/{customer}/reject', [CustomerCreationController::class, 'reject'])->name('reject');
        Route::post('/pending/{update}/approve', [CustomerCreationController::class, 'approvePending'])->name('pending.approve');
        Route::post('/pending/{update}/reject', [CustomerCreationController::class, 'rejectPending'])->name('pending.reject');

        // Edit Routes
        Route::get('/{customer}/edit', [CustomerCreationController::class, 'edit'])->name('edit');
        Route::post('/{customer}/edit/section', [CustomerCreationController::class, 'getEditSection'])->name('edit.section');
        Route::put('/{customer}/update', [CustomerCreationController::class, 'update'])->name('update');
    });

    // Billing and Payments route
    Route::get('bills', [BillingController::class, 'index'])->name('bills.index');
    Route::post('bills/generate', [BillingController::class, 'generateBills'])->name('bills.generate');
    Route::post('bills/{bill}/approve', [BillingController::class, 'approve'])->name('bills.approve');
    Route::post('bills/{bill}/reject', [BillingController::class, 'reject'])->name('bills.reject');
    Route::get('payments', [BillingController::class, 'payments'])->name('payments.index');
    Route::get('bills/{bill}/download-pdf', [BillingController::class, 'downloadPdf'])->name('bills.download-pdf');
    Route::get('reports/combined', [BillingController::class, 'generateCombinedReport'])->name('reports.combined');
    Route::get('reports/billing', [BillingController::class, 'generateBillingReport'])->name('reports.billing');
    Route::get('reports/payment', [BillingController::class, 'generatePaymentReport'])->name('reports.payment');

    // Complaint Management
    Route::get('complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::post('complaints/{complaint}/assign', [ComplaintController::class, 'assign'])->name('complaints.assign');
    Route::put('complaints/{complaint}', [ComplaintController::class, 'update'])->name('complaints.update');
    Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');

    // Vendor Management Routes
    Route::prefix('vendors')->name('vendors.')->group(function () {
        Route::get('/', [StaffVendorController::class, 'index'])->name('index');
        Route::get('/create', [StaffVendorController::class, 'create'])->name('create');
        Route::post('/', [StaffVendorController::class, 'store'])->name('store');
        Route::get('/{vendor}', [StaffVendorController::class, 'show'])->name('show');
        Route::get('/{vendor}/edit', [StaffVendorController::class, 'edit'])->name('edit');
        Route::put('/{vendor}', [StaffVendorController::class, 'update'])->name('update');
        Route::delete('/{vendor}', [StaffVendorController::class, 'destroy'])->name('destroy');
        Route::post('/{vendor}/approve', [StaffVendorController::class, 'approve'])->name('approve');
        Route::post('/{vendor}/reject', [StaffVendorController::class, 'reject'])->name('reject');
    });

    Route::post('/logout', [LoginController::class, 'staffLogout'])->name('logout');
    Route::get('/audits', [StaffController::class, 'auditTrail'])->name('audits.index');
});


// Move these outside the staff group
Route::prefix('mngr-secure-9374')->middleware(['auth:staff', 'restrict.login'])->group(function () {
    Route::get('bills', [BillingController::class, 'index'])->name('staff.bills.index');
    Route::post('bills/generate', [BillingController::class, 'generateBills'])->name('staff.bills.generate');
    Route::post('bills/approve-all', [BillingController::class, 'approveAll'])->name('staff.bills.approve-all');
    Route::post('bills/{bill}/approve', [BillingController::class, 'approve'])->name('staff.bills.approve');
    Route::post('bills/{bill}/reject', [BillingController::class, 'reject'])->name('staff.bills.reject');
    Route::get('bills/download-bulk', [BillingController::class, 'downloadBulkPdf'])->name('staff.bills.download-bulk');
    Route::get('gis', [App\Http\Controllers\GisController::class, 'index'])->name('staff.gis');
    Route::get('gis/filter', [App\Http\Controllers\GisController::class, 'filter'])->name('staff.gis.filter');
    Route::get('gis/export/csv', [App\Http\Controllers\GisController::class, 'exportCsv'])->name('staff.gis.export.csv');
    Route::get('gis/export/excel', [App\Http\Controllers\GisController::class, 'exportExcel'])->name('staff.gis.export.excel');
});

Route::prefix('customer')->middleware(['auth:customer', 'restrict.login'])->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::put('/profile', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');

    // Bill and payment routes
    Route::get('/bills', [CustomerController::class, 'bills'])->name('customer.bills');
    Route::post('/bills/pay', [CustomerController::class, 'initiateNABRollPayment'])->name('customer.bills.pay');
    Route::get('/payments/callback', [PaymentController::class, 'callback'])->name('payments.callback');
    Route::get('/payments', [CustomerController::class, 'payments'])->name('customer.payments');
    Route::get('bills/{bill}/download-pdf', [BillController::class, 'downloadPdf'])->name('customer.bills.download-pdf');

    // Complaint routes
    Route::get('/complaints', [CustomerController::class, 'complaints'])->name('customer.complaints');
    Route::post('/complaints', [CustomerController::class, 'storeComplaint'])->name('customer.complaints.store');

    Route::post('/logout', [LoginController::class, 'customerLogout'])->name('customer.logout');
});

Route::prefix('vendor')->middleware(['auth:vendor', 'restrict.login'])->group(function () {
    Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('vendor.dashboard');
    Route::get('/customer/info/{billingId}', [VendorController::class, 'getCustomerInfo'])->name('vendor.customer.info');
    Route::post('/payment/fund', [VendorPaymentController::class, 'fundAccount'])->name('vendor.payments.fund');
    Route::get('/payment/fund/callback', [VendorPaymentController::class, 'fundCallback'])->name('vendor.payments.fund.callback');
    Route::post('/payment/process', [VendorPaymentController::class, 'initiatePayment'])->name('vendor.payments.initiate');
    Route::get('/payments/callback', [VendorPaymentController::class, 'callback'])->name('vendor.payments.callback');
    Route::get('/payments', [VendorPaymentController::class, 'index'])->name('vendor.payments.index');
    Route::get('/payments/funding', [VendorPaymentController::class, 'fundingHistory'])->name('vendor.payments.funding');
    Route::post('/logout', [LoginController::class, 'vendorLogout'])->name('vendor.logout');
});

Route::get('customer/login', [LoginController::class, 'showCustomerLoginForm'])->name('customer.login')->middleware('restrict.login');
Route::post('customer/login', [LoginController::class, 'customerLogin'])->name('customer.login.submit')->middleware('restrict.login');
Route::get('vendor/login', [LoginController::class, 'showVendorLoginForm'])->name('vendor.login')->middleware('restrict.login');
Route::post('vendor/login', [LoginController::class, 'vendorLogin'])->name('vendor.login.submit')->middleware('restrict.login');

Route::get('mngr-secure-9374/login', [LoginController::class, 'showStaffLoginForm'])
    ->name('staff.login')
    ->middleware(['restrict.login', 'throttle:5,1']);

Route::post('mngr-secure-9374/login', [LoginController::class, 'staffLogin'])
    ->name('staff.login.submit')
    ->middleware(['restrict.login', 'throttle:5,1']);