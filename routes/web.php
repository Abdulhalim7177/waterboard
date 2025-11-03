<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerController;

use App\Http\Controllers\Staff\GisController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Staff\AssetController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Staff\TariffController;
use App\Http\Controllers\Customer\BillController;
use App\Http\Controllers\Staff\BillingController;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\VendorPaymentController;
use App\Http\Controllers\Staff\CategoryController;
use App\Http\Controllers\Staff\LocationController;
use App\Http\Controllers\Staff\AnalyticsController;

use App\Http\Controllers\Staff\CustomerCreationController;
use App\Http\Controllers\Web\Staff\ReservoirController;
use App\Http\Controllers\Staff\VendorController as StaffVendorController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('mngr-secure-9374')->name('staff.')->middleware(['auth:staff', 'restrict.login'])->group(function () {
    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');

    // Analytics Routes
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/export/csv', [AnalyticsController::class, 'exportCsv'])->name('analytics.export.csv');
    Route::get('/analytics/export/excel', [AnalyticsController::class, 'exportExcel'])->name('analytics.export.excel');
    Route::get('/analytics/report', [AnalyticsController::class, 'generateReport'])->name('analytics.report');
    Route::get('/analytics/details', [AnalyticsController::class, 'viewDetails'])->name('analytics.details');

    // Staff Management (Role Assignment Only)
    Route::get('/staff', [StaffController::class, 'staff'])->name('staff.index');
    Route::get('/staff/roles', [StaffController::class, 'staffRoles'])->name('staff.roles');
    Route::get('/staff/pending', [StaffController::class, 'pendingStaff'])->name('staff.pending');
    Route::put('/staff/{staff}/approve', [StaffController::class, 'approveStaff'])->name('staff.approve');
    Route::put('/staff/{staff}/reject', [StaffController::class, 'rejectStaff'])->name('staff.reject');
    Route::get('/staff/role-assignment/{staff_id}', [StaffController::class, 'roleAssignment'])->name('staff.role-assignment');
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

    // Zone and District Management
    Route::get('/zones', [LocationController::class, 'zones'])->name('zones.index');
    Route::post('/zones', [LocationController::class, 'storeZone'])->name('zones.store');
    Route::put('/zones/{zone}', [LocationController::class, 'updateZone'])->name('zones.update');
    Route::delete('/zones/{zone}', [LocationController::class, 'destroyZone'])->name('zones.destroy');
    Route::put('/zones/{zone}/approve', [LocationController::class, 'approveZone'])->name('zones.approve');
    Route::put('/zones/{zone}/reject', [LocationController::class, 'rejectZone'])->name('zones.reject');

    Route::get('/districts', [LocationController::class, 'districts'])->name('districts.index');
    Route::post('/districts', [LocationController::class, 'storeDistrict'])->name('districts.store');
    Route::put('/districts/{district}', [LocationController::class, 'updateDistrict'])->name('districts.update');
    Route::delete('/districts/{district}', [LocationController::class, 'destroyDistrict'])->name('districts.destroy');
    Route::put('/districts/{district}/approve', [LocationController::class, 'approveDistrict'])->name('districts.approve');
    Route::put('/districts/{district}/reject', [LocationController::class, 'rejectDistrict'])->name('districts.reject');
    
    // AJAX endpoints for dynamic loading
    // District-Ward Management
    Route::get('/districts/{district}/wards', [LocationController::class, 'manageDistrictWards'])->name('districts.manage-wards');
    Route::post('/districts/{district}/assign-ward', [LocationController::class, 'assignWardToDistrict'])->name('districts.assign-ward');
    Route::delete('/wards/{ward}/remove-from-district', [LocationController::class, 'removeWardFromDistrict'])->name('wards.remove-from-district');
    
    // Location Details
    Route::get('/zones/{zone}/details', [LocationController::class, 'zoneDetails'])->name('zones.details');
    Route::get('/districts/{district}/details', [LocationController::class, 'districtDetails'])->name('districts.details');
    Route::get('/paypoints/{paypoint}/details', [LocationController::class, 'paypointDetails'])->name('paypoints.details');
    
    // Paypoint Management
    Route::get('/paypoints', [LocationController::class, 'paypoints'])->name('paypoints.index');
    Route::post('/paypoints', [LocationController::class, 'storePaypoint'])->name('paypoints.store');
    Route::put('/paypoints/{paypoint}', [LocationController::class, 'updatePaypoint'])->name('paypoints.update');

    Route::get('/filter-wards', [LocationController::class, 'filterWards'])->name('filter.wards');
    Route::get('/filter-areas', [LocationController::class, 'filterAreas'])->name('filter.areas');
    Route::get('/filter-districts', [LocationController::class, 'filterDistricts'])->name('filter.districts');

    // AJAX endpoints for dynamic loading of wards and areas
    Route::get('/get-wards/{lga}', [LocationController::class, 'getWardsByLga'])->name('get.wards');
    Route::get('/get-areas/{ward}', [LocationController::class, 'getAreasByWard'])->name('get.areas');

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
        Route::get('/{customer}/edit', [\App\Http\Controllers\Staff\CustomerController::class, 'edit'])->name('edit');
        Route::post('/{customer}/edit/section', [\App\Http\Controllers\Staff\CustomerController::class, 'editSection'])->name('edit.section');
        Route::put('/{customer}/update', [CustomerCreationController::class, 'update'])->name('update');
        
        // Individual section edit routes
        Route::get('/{customer}/edit/personal', [CustomerCreationController::class, 'editPersonal'])->name('edit.personal');
        Route::get('/{customer}/edit/address', [CustomerCreationController::class, 'editAddress'])->name('edit.address');
        Route::get('/{customer}/edit/billing', [CustomerCreationController::class, 'editBilling'])->name('edit.billing');
        Route::get('/{customer}/edit/location', [CustomerCreationController::class, 'editLocation'])->name('edit.location');
        
        Route::put('/{customer}/update/personal', [CustomerCreationController::class, 'updatePersonal'])->name('update.personal');
        Route::put('/{customer}/update/address', [CustomerCreationController::class, 'updateAddress'])->name('update.address');
        Route::put('/{customer}/update/billing', [CustomerCreationController::class, 'updateBilling'])->name('update.billing');
        Route::put('/{customer}/update/location', [CustomerCreationController::class, 'updateLocation'])->name('update.location');
        
        // Additional AJAX routes for dynamic filtering
        Route::post('/filter-wards', [CustomerCreationController::class, 'filterWards'])->name('filter.wards');
        Route::post('/filter-areas', [CustomerCreationController::class, 'filterAreas'])->name('filter.areas');
        Route::post('/filter-tariffs', [CustomerCreationController::class, 'filterTariffs'])->name('filter.tariffs');
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

    // HR Staff Management Routes (Data Management)
    Route::prefix('hr/staff')->name('hr.staff.')->group(function () {
        Route::get('/', [\App\Http\Controllers\HR\StaffController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\HR\StaffController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\HR\StaffController::class, 'store'])->name('store');
        Route::get('/{staff}/edit', [\App\Http\Controllers\HR\StaffController::class, 'edit'])->name('edit');
        Route::put('/{staff}', [\App\Http\Controllers\HR\StaffController::class, 'update'])->name('update');
        Route::get('/{staff}', [\App\Http\Controllers\HR\StaffController::class, 'show'])->name('show');
        Route::delete('/{staff}', [\App\Http\Controllers\HR\StaffController::class, 'destroy'])->name('destroy');
        Route::put('/{staff}/approve', [\App\Http\Controllers\HR\StaffController::class, 'approve'])->name('approve');
        Route::put('/{staff}/reject', [\App\Http\Controllers\HR\StaffController::class, 'reject'])->name('reject');
        Route::post('/import', [\App\Http\Controllers\HR\StaffController::class, 'import'])->name('import');
        Route::get('/export/excel', [\App\Http\Controllers\HR\StaffController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [\App\Http\Controllers\HR\StaffController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/template', [\App\Http\Controllers\HR\StaffController::class, 'downloadTemplate'])->name('template');
        Route::get('/sync', [\App\Http\Controllers\HR\StaffController::class, 'sync'])->name('sync');
    });

    // Ticket Management
    Route::get('tickets', [\App\Http\Controllers\Staff\TicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/my-tickets', [\App\Http\Controllers\Staff\TicketController::class, 'myTickets'])->name('tickets.my-tickets');
    Route::get('tickets/{ticket}', [\App\Http\Controllers\Staff\TicketController::class, 'show'])->name('tickets.show');
    Route::post('tickets/{ticket}/assign', [\App\Http\Controllers\Staff\TicketController::class, 'assign'])->name('tickets.assign');
    Route::post('tickets/{ticket}/add-followup', [\App\Http\Controllers\Staff\TicketController::class, 'addFollowup'])->name('tickets.add-followup');
    Route::post('tickets/{ticket}/update-status', [\App\Http\Controllers\Staff\TicketController::class, 'updateStatus'])->name('tickets.update-status');
    Route::post('tickets/{ticket}/obtain', [\App\Http\Controllers\Staff\TicketController::class, 'obtainTicket'])->name('tickets.obtain');

    // Asset Management Routes
    Route::resource('assets', AssetController::class);

    // Warehouse Management Routes
    Route::resource('warehouses', \App\Http\Controllers\Staff\WarehouseController::class);

    // Reservoir Management Routes
    Route::resource('reservoirs', ReservoirController::class);

    Route::post('/logout', [LoginController::class, 'staffLogout'])->name('logout');
    Route::get('/audits', [StaffController::class, 'auditTrail'])->name('audits.index');
    Route::get('approvals', [\App\Http\Controllers\Staff\ApprovalsController::class, 'index'])->name('approvals.index');
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

    Route::get('/tickets', [\App\Http\Controllers\TicketController::class, 'index'])->name('customer.tickets.index');
    Route::get('/tickets/create', [\App\Http\Controllers\TicketController::class, 'create'])->name('customer.tickets.create');
    Route::post('/tickets', [\App\Http\Controllers\TicketController::class, 'store'])->name('customer.tickets.store');
    Route::get('/tickets/{ticket}', [\App\Http\Controllers\TicketController::class, 'show'])->name('customer.tickets.show');
    Route::post('/tickets/{ticket}/refresh', [\App\Http\Controllers\RefreshTicketStatusController::class, 'refresh'])->name('customer.tickets.refresh');

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

Route::get('/display-customers', function () {
    $customers = \App\Models\Customer::all();
    return view('display-customers', ['customers' => $customers]);
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
