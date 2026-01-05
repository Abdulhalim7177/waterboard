<?php

namespace App\Http\Controllers\Staff;

use App\Models\Lga;
use App\Models\Area;
use App\Models\Ward;
use App\Models\Tariff;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PendingCustomerUpdate;
use App\Exports\Staff\CustomersExport;
use App\Imports\CustomerImport;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Access\AuthorizationException;
use App\Services\BreadcrumbService;

class CustomerCreationController extends Controller
{
    public function index(Request $request)
    {
        // Set breadcrumbs
        $breadcrumb = app(BreadcrumbService::class);
        $breadcrumb->addHome()->add('Customer Management');

        $staff = auth()->guard('staff')->user();
        $accessibleWardIds = $staff->getAccessibleWardIds();

        // Stats should also be filtered by accessible wards if applicable
        $baseQuery = empty($accessibleWardIds) ? Customer::query() : Customer::whereIn('ward_id', $accessibleWardIds);

        $stats = [
            'total' => $baseQuery->count(),
            'pending' => $baseQuery->where('status', 'pending')->count(),
            'approved' => $baseQuery->where('status', 'approved')->count(),
            'rejected' => $baseQuery->where('status', 'rejected')->count(),
        ];

        $customersQuery = Customer::when($request->search_customer, function ($query, $search) {
            return $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('surname', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('billing_id', 'like', "%{$search}%");
        })->when($request->status_filter, function ($query, $status) {
            return $query->where('status', $status);
        })->when($request->lga_filter, function ($query, $lgaId) {
            return $query->where('lga_id', $lgaId);
        })->when($request->ward_filter, function ($query, $wardId) {
            return $query->where('ward_id', $wardId);
        })->when($request->area_filter, function ($query, $areaId) {
            return $query->where('area_id', $areaId);
        })->when($request->category_filter, function ($query, $categoryId) {
            return $query->where('category_id', $categoryId);
        })->when($request->tariff_filter, function ($query, $tariffId) {
            return $query->where('tariff_id', $tariffId);
        });

        // If staff has restricted access based on paypoint, filter by accessible wards
        if (!empty($accessibleWardIds)) {
            $customersQuery->whereIn('ward_id', $accessibleWardIds);
        }

        $perPage = $request->input('per_page', 10);
        if ($perPage == 'all') {
            $customers = $customersQuery->with(['category', 'tariff', 'lga', 'ward', 'area'])->orderBy('created_at', 'desc')->get();
        } else {
            $customers = $customersQuery->with(['category', 'tariff', 'lga', 'ward', 'area'])->orderBy('created_at', 'desc')->paginate($perPage);
        }

        $lgas = Lga::where('status', 'approved')->get();
        $wards = Ward::where('status', 'approved')->get();
        $areas = Area::where('status', 'approved')->get();
        $categories = Category::where('status', 'approved')->get();
        $tariffs = Tariff::where('status', 'approved')->get();

        return view('staff.customers.index', compact('stats', 'customers', 'lgas', 'wards', 'areas', 'categories', 'tariffs'));
    }

    public function import(Request $request)
    {
        try {
            Log::info('Customer import attempt started', [
                'staff_id' => Auth::guard('staff')->id(),
                'has_file' => $request->hasFile('file'),
                'all_files_count' => $request->files->count(),
                'all_request_data_keys' => array_keys($request->all()),
                'files_keys' => array_keys($request->files->all()),
                'file_input_raw' => $_FILES['file'] ?? 'NOT_SET_IN_PHP_FILES',
                'php_upload_errors' => [
                    'upload_max_filesize' => ini_get('upload_max_filesize'),
                    'post_max_size' => ini_get('post_max_size'),
                    'max_input_vars' => ini_get('max_input_vars'),
                    'file_uploads' => ini_get('file_uploads'),
                    'max_execution_time' => ini_get('max_execution_time'),
                    'memory_limit' => ini_get('memory_limit')
                ]
            ]);

            $this->authorize('create-customer', Customer::class);

            // Check if file exists before validation
            if (!$request->hasFile('file')) {
                Log::error('No file detected in request', [
                    'staff_id' => Auth::guard('staff')->id(),
                    'request_headers' => $request->headers->all(),
                    'content_type' => $request->header('Content-Type'),
                    'content_length' => $request->header('Content-Length'),
                    'request_method' => $request->method(),
                    'php_files_global' => $_FILES,
                    'post_global_size' => strlen(serialize($_POST)),
                    'server_vars' => [
                        'CONTENT_LENGTH' => $_SERVER['CONTENT_LENGTH'] ?? 'NOT_SET',
                        'CONTENT_TYPE' => $_SERVER['CONTENT_TYPE'] ?? 'NOT_SET',
                        'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'] ?? 'NOT_SET'
                    ]
                ]);
                return redirect()->route('staff.customers.index')
                    ->with('error', 'No file was uploaded. Please select a file to import.');
            }

            $uploadedFile = $request->file('file');
            Log::info('File details', [
                'original_name' => $uploadedFile->getClientOriginalName(),
                'mime_type' => $uploadedFile->getMimeType(),
                'size' => $uploadedFile->getSize(),
                'error' => $uploadedFile->getError(),
                'error_message' => $uploadedFile->getErrorMessage(),
                'is_valid' => $uploadedFile->isValid(),
                'max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size')
            ]);

            if (!$uploadedFile->isValid()) {
                Log::error('Uploaded file is not valid', [
                    'error_code' => $uploadedFile->getError(),
                    'error_message' => $uploadedFile->getErrorMessage(),
                    'file_info' => [
                        'name' => $uploadedFile->getClientOriginalName(),
                        'size' => $uploadedFile->getSize(),
                        'mime_type' => $uploadedFile->getMimeType()
                    ]
                ]);
                return redirect()->route('staff.customers.index')
                    ->with('error', 'File upload failed: ' . $uploadedFile->getErrorMessage());
            }

            $request->validate([
                'file' => 'required|mimes:csv,xlsx|max:51200', // Max 50MB
            ]);

            // Increase the execution time limit for large imports
            set_time_limit(300); // 5 minutes

            Log::info('Starting Excel import', ['file_name' => $uploadedFile->getClientOriginalName()]);
            $import = new CustomerImport();
            Excel::import($import, $uploadedFile);

            $errors = $import->getErrors();
            if (!empty($errors)) {
                Log::warning('Customer import completed with errors', [
                    'staff_id' => Auth::guard('staff')->id(),
                    'errors' => $errors
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Import completed with errors: ' . implode(', ', $errors),
                        'success' => false,
                        'errors' => $errors
                    ]);
                }

                return redirect()->route('staff.customers.index')
                    ->with('warning', 'Import completed with errors: ' . implode(', ', $errors));
            }

            Log::info('Customer import completed successfully', ['staff_id' => Auth::guard('staff')->id()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Customers imported successfully and are pending approval.',
                    'success' => true
                ]);
            }

            return redirect()->route('staff.customers.index')
                ->with('success', 'Customers imported successfully and are pending approval.');
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to import customers', ['user_id' => Auth::guard('staff')->id()]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'You are not authorized to import customers.'], 403);
            }

            return redirect()->route('staff.customers.index')
                ->with('error', 'You are not authorized to import customers.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed during import', [
                'user_id' => Auth::guard('staff')->id(),
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            // Flatten the validation errors array
            $errorMessages = [];
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $errorMessages[] = $message;
                }
            }
            $errorMessageString = implode(', ', $errorMessages);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Validation failed: ' . $errorMessageString,
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->route('staff.customers.index')
                ->with('error', 'Validation failed: ' . $errorMessageString);
        } catch (\Exception $e) {
            Log::error('Customer import failed', [
                'user_id' => Auth::guard('staff')->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'file_info' => $request->hasFile('file') ? [
                    'name' => $request->file('file')->getClientOriginalName(),
                    'size' => $request->file('file')->getSize(),
                    'mime' => $request->file('file')->getMimeType(),
                    'error' => $request->file('file')->getError()
                ] : 'No file'
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Failed to import customers: ' . $e->getMessage()], 500);
            }

            return redirect()->route('staff.customers.index')
                ->with('error', 'Failed to import customers: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $this->authorize('view-customers', Customer::class);

            $filters = [
                'status' => $request->input('status'),
                'lga' => $request->input('lga_filter'),
                'ward' => $request->input('ward_filter'),
                'area' => $request->input('area_filter'),
                'category' => $request->input('category_filter'),
                'tariff' => $request->input('tariff_filter'),
                'search' => $request->input('search_customer'),
            ];

            $format = $request->input('format', 'csv');
            if (!in_array($format, ['csv', 'xlsx'])) {
                return response()->json(['error' => 'Invalid export format. Only CSV and Excel are supported.'], 400);
            }

            $extension = $format === 'csv' ? 'csv' : 'xlsx';
            $filename = 'customers_' . now()->format('Ymd_His') . '.' . $extension;

            Log::info('Customer export initiated', [
                'staff_id' => Auth::guard('staff')->id(),
                'filters' => $filters,
                'format' => $format
            ]);

            return Excel::download(new CustomersExport($filters), $filename);
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to export customers', ['user_id' => Auth::guard('staff')->id()]);
            return response()->json(['error' => 'You are not authorized to export customers.'], 403);
        } catch (\Exception $e) {
            Log::error('Customer export failed', ['user_id' => Auth::guard('staff')->id(), 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to export customers: ' . $e->getMessage()], 500);
        }
    }

    public function downloadSample()
    {
        $this->authorize('create-customer', \App\Models\Customer::class);

        $filePath = public_path('samples/customer_import_sample.csv');

        if (!file_exists($filePath)) {
            // Create a sample file if it doesn't exist
            $sampleData = [
                ['first_name', 'surname', 'middle_name', 'email', 'phone_number', 'alternate_phone_number', 'street_name', 'house_number', 'landmark', 'lga', 'ward', 'area', 'category', 'tariff', 'delivery_code', 'billing_condition', 'water_supply_status', 'latitude', 'longitude', 'altitude', 'pipe_path', 'polygon_coordinates', 'password', 'account_balance', 'created_at'],
                ['John', 'Doe', 'Smith', 'john.doe@example.com', '1234567890', '0987654321', 'Main Street', '123', 'Near Market', 'Lagos Island', 'Ikeja', 'GRA', 'Residential', 'Basic', 'DEL001', 'Non-Metered', 'Functional', '6.4566', '3.3912', '50', null, null, 'password123', '0', now()],
                ['Jane', 'Smith', '', 'jane.smith@example.com', '1122334455', '', 'N/A', 'N/A', 'School Junction', 'Eti-Osa', 'Victoria Island', 'Ikoyi', 'Commercial', 'Premium', 'DEL002', 'Metered', 'Functional', '6.4345', '3.4162', '45', null, null, 'password123', '5000', now()],
            ];

            // Create directory if it doesn't exist
            $directory = dirname($filePath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $file = fopen($filePath, 'w');
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }

        if (!file_exists($filePath)) {
            return redirect()->route('staff.customers.index')->with('error', 'Sample file could not be created.');
        }

        return response()->download($filePath);
    }

     public function edit(Customer $customer)
    {
        try {
            $this->authorize('edit-customer', $customer);
            $lgas = Lga::where('status', 'approved')->get();
            $categories = Category::where('status', 'approved')->get();
            return view('staff.customers.edit', compact('customer', 'lgas', 'categories'));
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized access attempt to edit customer', ['user_id' => Auth::guard('staff')->id(), 'customer_id' => $customer->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to edit this customer.');
        }
    }
       public function getEditSection(Request $request, Customer $customer)
    {
        try {
            $this->authorize('edit-customer', $customer);
            $request->validate([
                'part' => 'required|in:personal,address,billing,location',
                'lga_id' => 'nullable|exists:lgas,id',
                'ward_id' => 'nullable|exists:wards,id',
                'category_id' => 'nullable|exists:categories,id',
            ]);

            $part = $request->input('part');
            $data = compact('customer');

            if ($part === 'address') {
                // Load all data upfront for client-side filtering
                $staff = auth()->guard('staff')->user();
                $accessibleLgaIds = $staff->getAccessibleLgaIdsAttribute;
                $accessibleWardIds = $staff->getAccessibleWardIdsAttribute;
                $accessibleAreaIds = $staff->getAccessibleAreaIdsAttribute;

                $lgaQuery = Lga::where('status', 'approved');
                $wardQuery = Ward::where('status', 'approved');
                $areaQuery = Area::where('status', 'approved');

                if (!empty($accessibleLgaIds)) {
                    $lgaQuery->whereIn('id', $accessibleLgaIds);
                }
                if (!empty($accessibleWardIds)) {
                    $wardQuery->whereIn('id', $accessibleWardIds);
                }
                if (!empty($accessibleAreaIds)) {
                    $areaQuery->whereIn('id', $accessibleAreaIds);
                }

                $lgas = $lgaQuery->get();
                $wards = $wardQuery->get();
                $areas = $areaQuery->get();
                $selectedLgaId = $request->lga_id ?? $customer->lga_id;
                $selectedWardId = $request->ward_id ?? $customer->ward_id;
                $data = array_merge($data, compact('lgas', 'wards', 'areas', 'selectedLgaId', 'selectedWardId'));
            } elseif ($part === 'billing') {
                // Load all data upfront for client-side filtering
                $categories = Category::where('status', 'approved')->get();
                $tariffs = Tariff::where('status', 'approved')->get();
                $selectedCategoryId = $request->category_id ?? $customer->category_id;
                $data = array_merge($data, compact('categories', 'tariffs', 'selectedCategoryId'));
            }

            return response()->json([
                'html' => view("staff.customers.partials.edit_{$part}", $data)->render(),
            ]);
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to get edit section', ['user_id' => Auth::guard('staff')->id(), 'customer_id' => $customer->id]);
            return response()->json(['error' => 'You are not authorized to edit this customer.'], 403);
        } catch (\Exception $e) {
            Log::error('Error fetching edit section', ['customer_id' => $customer->id, 'part' => $part, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching the section: ' . $e->getMessage()], 500);
        }
    }



    public function redirectEdit(Request $request, Customer $customer)
    {
        try {
            $this->authorize('edit-customer', $customer);
            $request->validate([
                'part' => 'required|in:personal,address,billing,location',
            ]);

            $part = $request->input('part');
            return redirect()->route("staff.customers.edit.{$part}", $customer);
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to redirect edit', ['user_id' => Auth::guard('staff')->id(), 'customer_id' => $customer->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to edit this customer.');
        }
    }

  public function update(Request $request, Customer $customer)
    {
        try {
            $this->authorize('edit-customer', $customer);
            $request->validate([
                'part' => 'required|in:personal,address,billing,location',
            ]);

            $part = $request->input('part');
            $rules = $this->getValidationRules($part);
            if (!$rules) {
                return response()->json(['error' => 'Invalid section selected.', 'status' => 'error'], 400);
            }

            $validated = $request->validate($rules['rules']);
            if (isset($rules['extraValidation'])) {
                $extraResult = $rules['extraValidation']($validated);
                if ($extraResult && isset($extraResult['error'])) {
                    return response()->json(['errors' => $extraResult['error'], 'status' => 'error'], 422);
                }
            }

            $updatesCreated = false;
            foreach ($validated as $field => $newValue) {
                if (in_array($field, ['password_confirmation', 'status', 'lga_id', 'ward_id', 'category_id'])) {
                    continue; // Skip non-updatable fields
                }
                $oldValue = $customer->$field ?? null;
                if ($field === 'password' && !$newValue) {
                    continue; // Skip empty password
                }
                if ($newValue != $oldValue) { // Use != to handle null comparisons
                    try {
                        PendingCustomerUpdate::create([
                            'customer_id' => $customer->id,
                            'field' => $field,
                            'old_value' => is_array($oldValue) ? json_encode($oldValue) : $oldValue,
                            'new_value' => is_array($newValue) ? json_encode($newValue) : ($field === 'password' ? Hash::make($newValue) : $newValue),
                            'updated_by' => Auth::guard('staff')->id(),
                        ]);
                        $updatesCreated = true;
                        Log::debug('Pending update created', [
                            'customer_id' => $customer->id,
                            'field' => $field,
                            'old_value' => $oldValue,
                            'new_value' => $newValue,
                            'updated_by' => Auth::guard('staff')->id(),
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to create pending update', [
                            'customer_id' => $customer->id,
                            'field' => $field,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ]);
                        return response()->json(['error' => "Failed to create pending update for {$field}: {$e->getMessage()}", 'status' => 'error'], 500);
                    }
                }
            }

            if (!$updatesCreated) {
                return response()->json(['message' => 'No changes detected to submit for approval.', 'status' => 'info'], 200);
            }

            Log::info('Customer update submitted', [
                'customer_id' => $customer->id,
                'part' => $part,
                'updated_by' => Auth::guard('staff')->id(),
            ]);
            return response()->json(['message' => 'Update submitted for approval.', 'status' => 'success'], 200);
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to update customer', [
                'user_id' => Auth::guard('staff')->id(),
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'You are not authorized to edit this customer.', 'status' => 'error'], 403);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed for customer update', [
                'customer_id' => $customer->id,
                'part' => $part,
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            return response()->json(['errors' => $e->errors(), 'status' => 'error'], 422);
        } catch (\Exception $e) {
            Log::error('Error submitting customer update', [
                'customer_id' => $customer->id,
                'part' => $part,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'An unexpected error occurred while submitting the update: ' . $e->getMessage(), 'status' => 'error'], 500);
        }
    }

    public function personal()
    {
        // Set breadcrumbs
        $breadcrumb = app(BreadcrumbService::class);
        $breadcrumb->addHome()->add('Customer Management', route('staff.customers.index'))->add('Create Customer')->add('Personal Information');

        $staff = auth()->guard('staff')->user();
        $accessibleLgaIds = $staff->getAccessibleLgaIds();
        $accessibleWardIds = $staff->getAccessibleWardIds();
        $accessibleAreaIds = $staff->getAccessibleAreaIds();

        $lgaQuery = Lga::where('status', 'approved');
        $wardQuery = Ward::where('status', 'approved');
        $areaQuery = Area::where('status', 'approved');

        if (!empty($accessibleLgaIds)) {
            $lgaQuery->whereIn('id', $accessibleLgaIds);
        }
        if (!empty($accessibleWardIds)) {
            $wardQuery->whereIn('id', $accessibleWardIds);
        }
        if (!empty($accessibleAreaIds)) {
            $areaQuery->whereIn('id', $accessibleAreaIds);
        }

        $lgas = $lgaQuery->get();
        $wards = $wardQuery->get();
        $areas = $areaQuery->get();
        $categories = Category::where('status', 'approved')->get();
        $tariffs = Tariff::where('status', 'approved')->get();

        return view('staff.customers.create.personal', compact('lgas', 'wards', 'areas', 'categories', 'tariffs'));
    }

    public function storePersonal(Request $request)
    {
        try {
            $this->authorize('create-customer', Customer::class);
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'email' => 'required|email|unique:customers,email',
                'phone_number' => 'required|string|min:10|regex:/^[0-9]+$/|unique:customers,phone_number',
                'alternate_phone_number' => 'nullable|string|min:10|regex:/^[0-9]+$/|unique:customers,alternate_phone_number',
            ]);

            Session::put('customer_creation.personal', $validated);
            return redirect()->route('staff.customers.create.address')->with('success', 'Personal information saved.');
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to store personal info', ['user_id' => Auth::guard('staff')->id()]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to create customers.');
        } catch (\Exception $e) {
            Log::error('Error storing personal info', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while saving personal information: ' . $e->getMessage())->withInput();
        }
    }

    public function address(Request $request)
    {
        try {
            $this->authorize('create-customer', Customer::class);
            // Load all data upfront for client-side filtering
            $staff = auth()->guard('staff')->user();
            $accessibleLgaIds = $staff->getAccessibleLgaIdsAttribute;
            $accessibleWardIds = $staff->getAccessibleWardIdsAttribute;
            $accessibleAreaIds = $staff->getAccessibleAreaIdsAttribute;

            $lgaQuery = Lga::where('status', 'approved');
            $wardQuery = Ward::where('status', 'approved');
            $areaQuery = Area::where('status', 'approved');

            if (!empty($accessibleLgaIds)) {
                $lgaQuery->whereIn('id', $accessibleLgaIds);
            }
            if (!empty($accessibleWardIds)) {
                $wardQuery->whereIn('id', $accessibleWardIds);
            }
            if (!empty($accessibleAreaIds)) {
                $areaQuery->whereIn('id', $accessibleAreaIds);
            }

            $lgas = $lgaQuery->get();
            $wards = $wardQuery->get();
            $areas = $areaQuery->get();
            $selectedLgaId = $request->lga_id;
            $selectedWardId = $request->ward_id;
            return view('staff.customers.create.address', compact('lgas', 'wards', 'areas', 'selectedLgaId', 'selectedWardId'));
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized access attempt to customer address form', ['user_id' => Auth::guard('staff')->id()]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to create customers.');
        }
    }

    public function storeAddress(Request $request)
    {
        try {
            $this->authorize('create-customer', Customer::class);
            $validated = $request->validate([
                'lga_id' => 'required|exists:lgas,id',
                'ward_id' => 'required|exists:wards,id',
                'area_id' => 'required|exists:areas,id',
                'street_name' => 'nullable|string|max:255',
                'house_number' => 'nullable|string|max:255',
                'landmark' => 'required|string|max:255',
            ]);

            $ward = Ward::where('id', $validated['ward_id'])->where('lga_id', $validated['lga_id'])->first();
            if (!$ward) {
                return back()->withErrors(['ward_id' => 'Selected ward does not belong to the chosen LGA.'])->withInput();
            }

            $area = Area::where('id', $validated['area_id'])->where('ward_id', $validated['ward_id'])->first();
            if (!$area) {
                return back()->withErrors(['area_id' => 'Selected area does not belong to the chosen ward.'])->withInput();
            }

            Session::put('customer_creation.address', $validated);
            return redirect()->route('staff.customers.create.billing')->with('success', 'Address information saved.');
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to store address info', ['user_id' => Auth::guard('staff')->id()]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to create customers.');
        } catch (\Exception $e) {
            Log::error('Error storing address info', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while saving address information: ' . $e->getMessage())->withInput();
        }
    }

    public function billing(Request $request)
    {
        try {
            $this->authorize('create-customer', Customer::class);
            // Load all data upfront for client-side filtering
            $categories = Category::where('status', 'approved')->get();
            $tariffs = Tariff::where('status', 'approved')->get();
            $selectedCategoryId = $request->category_id;
            return view('staff.customers.create.billing', compact('categories', 'tariffs', 'selectedCategoryId'));
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized access attempt to customer billing form', ['user_id' => Auth::guard('staff')->id()]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to create customers.');
        }
    }

    public function storeBilling(Request $request)
    {
        try {
            $this->authorize('create-customer', Customer::class);
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'tariff_id' => 'required|exists:tariffs,id',
                'delivery_code' => 'nullable|string|max:255',
                'billing_condition' => 'required|in:Metered,Non-Metered',
                'water_supply_status' => 'required|in:Functional,Non-Functional',
            ]);

            $tariff = Tariff::where('id', $validated['tariff_id'])->where('category_id', $validated['category_id'])->first();
            if (!$tariff) {
                return back()->withErrors(['tariff_id' => 'Selected tariff does not belong to the chosen category.'])->withInput();
            }

            Session::put('customer_creation.billing', $validated);
            return redirect()->route('staff.customers.create.location')->with('success', 'Billing information saved.');
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to store billing info', ['user_id' => Auth::guard('staff')->id()]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to create customers.');
        } catch (\Exception $e) {
            Log::error('Error storing billing info', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while saving billing information: ' . $e->getMessage())->withInput();
        }
    }

 public function filterTariffs(Request $request)
    {
        try {
            $this->authorize('edit-customer', Customer::class);
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'customer_id' => 'required|exists:customers,id',
            ]);

            $customer = Customer::findOrFail($request->customer_id);
            $selectedCategoryId = $request->category_id;
            $categories = Category::where('status', 'approved')->get();
            $tariffs = Tariff::where('category_id', $selectedCategoryId)->where('status', 'approved')->get();
            return response()->json([
                'html' => view('staff.customers.partials.edit_billing', compact('customer', 'categories', 'tariffs', 'selectedCategoryId'))->render(),
            ]);
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to filter tariffs', ['user_id' => Auth::guard('staff')->id()]);
            return response()->json(['error' => 'You are not authorized to perform this action.'], 403);
        } catch (\Exception $e) {
            Log::error('Error filtering tariffs', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while filtering tariffs: ' . $e->getMessage()], 500);
        }
    }



    public function filterTariffsForCreate(Request $request)
    {
        try {
            $this->authorize('create-customer', Customer::class);
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
            ]);

            Session::put('customer_creation.billing.category_id', $validated['category_id']);
            Session::forget('customer_creation.billing.tariff_id');

            return redirect()->route('staff.customers.create.billing', ['category_id' => $validated['category_id']]);
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to filter tariffs for create', ['user_id' => Auth::guard('staff')->id()]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to perform this action.');
        } catch (\Exception $e) {
            Log::error('Error filtering tariffs for create', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while filtering tariffs: ' . $e->getMessage())->withInput();
        }
    }

    public function location()
    {
        try {
            $this->authorize('create-customer', Customer::class);
            if (
                !Session::has('customer_creation.personal') ||
                !Session::has('customer_creation.address') ||
                !Session::has('customer_creation.billing')
            ) {
                return redirect()->route('staff.customers.create.personal')
                    ->with('error', 'Please complete all previous steps.');
            }

            return view('staff.customers.create.location');
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized access attempt to customer location form', ['user_id' => Auth::guard('staff')->id()]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to create customers.');
        }
    }

    public function storeLocation(Request $request)
    {
        try {
            $this->authorize('create-customer', Customer::class);
            if (
                !Session::has('customer_creation.personal') ||
                !Session::has('customer_creation.address') ||
                !Session::has('customer_creation.billing')
            ) {
                return redirect()->route('staff.customers.create.personal')
                    ->with('error', 'Please complete all previous steps.');
            }

            $validated = $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'altitude' => 'nullable|numeric',
                'pipe_path' => 'nullable|json',
                'polygon_coordinates' => 'nullable|json',
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            if ($validated['polygon_coordinates']) {
                $coords = json_decode($validated['polygon_coordinates'], true);
                $invalid = false;
                if (!is_array($coords)) {
                    $invalid = true;
                } elseif (!empty($coords)) {
                    foreach ($coords as $point) {
                        if (!is_array($point) || count($point) !== 2 || !is_numeric($point[0]) || !is_numeric($point[1])) {
                            $invalid = true;
                            break;
                        }
                    }
                }
                if ($invalid) {
                    return back()->withErrors(['polygon_coordinates' => 'Invalid polygon coordinates format. Must be an array of [lat, lng] pairs.'])->withInput();
                }
            }

            if ($validated['pipe_path']) {
                $pipePath = json_decode($validated['pipe_path'], true);
                $invalid = false;
                if (!is_array($pipePath)) {
                    $invalid = true;
                } elseif (!empty($pipePath)) {
                    foreach ($pipePath as $point) {
                        if (!is_array($point) || count($point) !== 2 || !is_numeric($point[0]) || !is_numeric($point[1])) {
                            $invalid = true;
                            break;
                        }
                    }
                }
                if ($invalid) {
                    return back()->withErrors(['pipe_path' => 'Invalid pipe path format. Must be an array of [lat, lng] pairs.'])->withInput();
                }
            }

            $customerData = array_merge(
                Session::get('customer_creation.personal', []),
                Session::get('customer_creation.address', []),
                Session::get('customer_creation.billing', []),
                $validated
            );

            $customerData['password'] = $customerData['password'] ? Hash::make($customerData['password']) : Hash::make('default123');
            $customerData['status'] = 'pending';
            $customerData['created_by'] = Auth::guard('staff')->id();

            $customer = Customer::create($customerData);

            if ($customer->status === 'approved') {
                $customer->billing_id = Customer::generateBillingId($customer);
                $customer->save();
            }

            Session::forget('customer_creation');
            Log::info('Customer created successfully', ['customer_id' => $customer->id]);
            return redirect()->route('staff.customers.index')->with('success', 'Customer created successfully and is pending approval.');
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to store location info', ['user_id' => Auth::guard('staff')->id()]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to create customers.');
        } catch (\Exception $e) {
            Log::error('Error creating customer', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while creating the customer: ' . $e->getMessage())->withInput();
        }
    }

    public function editPersonal(Customer $customer)
    {
        try {
            $this->authorize('edit-customer', $customer);
            return view('staff.customers.edit_personal', compact('customer'));
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized access attempt to edit personal info', ['user_id' => Auth::guard('staff')->id(), 'customer_id' => $customer->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to edit this customer.');
        }
    }

    public function updatePersonal(Request $request, Customer $customer)
    {
        try {
            $this->authorize('edit-customer', $customer);
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'email' => 'required|email|max:255|unique:customers,email,' . $customer->id,
                'phone_number' => 'required|string|min:10|regex:/^[0-9]+$/|unique:customers,phone_number,' . $customer->id,
                'alternate_phone_number' => 'nullable|string|min:10|regex:/^[0-9]+$/|unique:customers,alternate_phone_number,' . $customer->id . ',id',
            ]);

            $updatesCreated = false;
            foreach ($validated as $field => $newValue) {
                if ($field === 'password_confirmation' || $field === 'status') continue;
                $oldValue = $customer->$field ?? null;
                if ($newValue != $oldValue) {
                    try {
                        PendingCustomerUpdate::create([
                            'customer_id' => $customer->id,
                            'field' => $field,
                            'old_value' => is_array($oldValue) ? json_encode($oldValue) : $oldValue,
                            'new_value' => is_array($newValue) ? json_encode($newValue) : $newValue,
                            'updated_by' => Auth::guard('staff')->id(),
                        ]);
                        $updatesCreated = true;
                        Log::debug('Pending update created', ['customer_id' => $customer->id, 'field' => $field, 'old_value' => $oldValue, 'new_value' => $newValue]);
                    } catch (\Exception $e) {
                        Log::error('Failed to create pending update', ['customer_id' => $customer->id, 'field' => $field, 'error' => $e->getMessage()]);
                    }
                }
            }

            if (!$updatesCreated) {
                return redirect()->route('staff.customers.index')->with('info', 'No changes detected to submit for approval.');
            }

            Log::info('Personal information update submitted', ['customer_id' => $customer->id]);
            return redirect()->route('staff.customers.index')->with('success', 'Personal information update submitted for approval.');
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to update personal info', ['user_id' => Auth::guard('staff')->id(), 'customer_id' => $customer->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to edit this customer.');
        } catch (\Exception $e) {
            Log::error('Error submitting personal update', ['customer_id' => $customer->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while submitting the update: ' . $e->getMessage())->withInput();
        }
    }

    public function editAddress(Request $request, Customer $customer)
    {
        try {
            $this->authorize('edit-customer', $customer);
            $staff = auth()->guard('staff')->user();
            $accessibleLgaIds = $staff->getAccessibleLgaIdsAttribute;
            $accessibleWardIds = $staff->getAccessibleWardIdsAttribute;
            $accessibleAreaIds = $staff->getAccessibleAreaIdsAttribute;

            $lgaQuery = Lga::where('status', 'approved');
            $wardQuery = Ward::where('status', 'approved');
            $areaQuery = Area::where('status', 'approved');

            if (!empty($accessibleLgaIds)) {
                $lgaQuery->whereIn('id', $accessibleLgaIds);
            }
            if (!empty($accessibleWardIds)) {
                $wardQuery->whereIn('id', $accessibleWardIds);
            }
            if (!empty($accessibleAreaIds)) {
                $areaQuery->whereIn('id', $accessibleAreaIds);
            }

            $lgas = $lgaQuery->get();
            $wards = $wardQuery->get();
            $areas = $areaQuery->get();
            $selectedLgaId = $customer->lga_id;
            $selectedWardId = $customer->ward_id;
            return view('staff.customers.edit_address', compact('customer', 'lgas', 'wards', 'areas', 'selectedLgaId', 'selectedWardId'));
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized access attempt to edit customer address', ['user_id' => Auth::guard('staff')->id(), 'customer_id' => $customer->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to edit this customer.');
        }
    }

    public function updateAddress(Request $request, Customer $customer)
    {
        try {
            $this->authorize('edit-customer', $customer);
            $validated = $request->validate([
                'lga_id' => 'required|exists:lgas,id',
                'ward_id' => 'required|exists:wards,id',
                'area_id' => 'required|exists:areas,id',
                'street_name' => 'nullable|string|max:255',
                'house_number' => 'nullable|string|max:255',
                'landmark' => 'required|string|max:255',
            ]);

            $ward = Ward::where('id', $validated['ward_id'])->where('lga_id', $validated['lga_id'])->first();
            if (!$ward) {
                return back()->withErrors(['ward_id' => 'Selected ward does not belong to the chosen LGA.'])->withInput();
            }

            $area = Area::where('id', $validated['area_id'])->where('ward_id', $validated['ward_id'])->first();
            if (!$area) {
                return back()->withErrors(['area_id' => 'Selected area does not belong to the chosen ward.'])->withInput();
            }

            $updatesCreated = false;
            foreach ($validated as $field => $newValue) {
                if ($field === 'password_confirmation' || $field === 'status') continue;
                $oldValue = $customer->$field ?? null;
                if ($newValue != $oldValue) {
                    try {
                        PendingCustomerUpdate::create([
                            'customer_id' => $customer->id,
                            'field' => $field,
                            'old_value' => is_array($oldValue) ? json_encode($oldValue) : $oldValue,
                            'new_value' => is_array($newValue) ? json_encode($newValue) : $newValue,
                            'updated_by' => Auth::guard('staff')->id(),
                        ]);
                        $updatesCreated = true;
                        Log::debug('Pending update created', ['customer_id' => $customer->id, 'field' => $field, 'old_value' => $oldValue, 'new_value' => $newValue]);
                    } catch (\Exception $e) {
                        Log::error('Failed to create pending update', ['customer_id' => $customer->id, 'field' => $field, 'error' => $e->getMessage()]);
                    }
                }
            }

            if (!$updatesCreated) {
                return redirect()->route('staff.customers.index')->with('info', 'No changes detected to submit for approval.');
            }

            Log::info('Address update submitted', ['customer_id' => $customer->id]);
            return redirect()->route('staff.customers.index')->with('success', 'Address update submitted for approval.');
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to update address', ['user_id' => Auth::guard('staff')->id(), 'customer_id' => $customer->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to edit this customer.');
        } catch (\Exception $e) {
            Log::error('Error submitting address update', ['customer_id' => $customer->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while submitting the update: ' . $e->getMessage())->withInput();
        }
    }

    public function editBilling(Request $request, Customer $customer)
    {
        try {
            $this->authorize('edit-customer', $customer);
            $categories = Category::where('status', 'approved')->get();
            $tariffs = $request->category_id ? Tariff::where('category_id', $request->category_id)->where('status', 'approved')->get() : ($customer->category_id ? Tariff::where('category_id', $customer->category_id)->where('status', 'approved')->get() : collect());
            $selectedCategoryId = $request->category_id ?? $customer->category_id;
            return view('staff.customers.edit_billing', compact('customer', 'categories', 'tariffs', 'selectedCategoryId'));
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized access attempt to edit billing', ['user_id' => Auth::guard('staff')->id(), 'customer_id' => $customer->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to edit this customer.');
        }
    }

    public function updateBilling(Request $request, Customer $customer)
    {
        try {
            $this->authorize('edit-customer', $customer);
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'tariff_id' => 'required|exists:tariffs,id',
                'delivery_code' => 'nullable|string|max:255',
                'billing_condition' => 'required|in:Metered,Non-Metered',
                'water_supply_status' => 'required|in:Functional,Non-Functional',
            ]);

            $tariff = Tariff::where('id', $validated['tariff_id'])->where('category_id', $validated['category_id'])->first();
            if (!$tariff) {
                return back()->withErrors(['tariff_id' => 'Selected tariff does not belong to the chosen category.'])->withInput();
            }

            $updatesCreated = false;
            foreach ($validated as $field => $newValue) {
                if ($field === 'password_confirmation' || $field === 'status') continue;
                $oldValue = $customer->$field ?? null;
                if ($newValue != $oldValue) {
                    try {
                        PendingCustomerUpdate::create([
                            'customer_id' => $customer->id,
                            'field' => $field,
                            'old_value' => is_array($oldValue) ? json_encode($oldValue) : $oldValue,
                            'new_value' => is_array($newValue) ? json_encode($newValue) : $newValue,
                            'updated_by' => Auth::guard('staff')->id(),
                        ]);
                        $updatesCreated = true;
                        Log::debug('Pending update created', ['customer_id' => $customer->id, 'field' => $field, 'old_value' => $oldValue, 'new_value' => $newValue]);
                    } catch (\Exception $e) {
                        Log::error('Failed to create pending update', ['customer_id' => $customer->id, 'field' => $field, 'error' => $e->getMessage()]);
                    }
                }
            }

            if (!$updatesCreated) {
                return redirect()->route('staff.customers.index')->with('info', 'No changes detected to submit for approval.');
            }

            Log::info('Billing update submitted', ['customer_id' => $customer->id]);
            return redirect()->route('staff.customers.index')->with('success', 'Billing update submitted for approval.');
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to update billing', ['user_id' => Auth::guard('staff')->id(), 'customer_id' => $customer->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to edit this customer.');
        } catch (\Exception $e) {
            Log::error('Error submitting billing update', ['customer_id' => $customer->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while submitting the update: ' . $e->getMessage())->withInput();
        }
    }

    public function editLocation(Customer $customer)
    {
        try {
            $this->authorize('edit-customer', $customer);
            return view('staff.customers.edit_location', compact('customer'));
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized access attempt to edit location', ['user_id' => Auth::guard('staff')->id(), 'customer_id' => $customer->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to edit this customer.');
        }
    }

    public function updateLocation(Request $request, Customer $customer)
    {
        try {
            $this->authorize('edit-customer', $customer);
            $validated = $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'altitude' => 'nullable|numeric',
                'pipe_path' => 'nullable|json',
                'polygon_coordinates' => 'nullable|json',
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            if ($validated['polygon_coordinates']) {
                $coords = json_decode($validated['polygon_coordinates'], true);
                $invalid = false;
                if (!is_array($coords)) {
                    $invalid = true;
                } elseif (!empty($coords)) {
                    foreach ($coords as $point) {
                        if (!is_array($point) || count($point) !== 2 || !is_numeric($point[0]) || !is_numeric($point[1])) {
                            $invalid = true;
                            break;
                        }
                    }
                }
                if ($invalid) {
                    return back()->withErrors(['polygon_coordinates' => 'Invalid polygon coordinates format. Must be an array of [lat, lng] pairs.'])->withInput();
                }
            }

            if ($validated['pipe_path']) {
                $pipePath = json_decode($validated['pipe_path'], true);
                $invalid = false;
                if (!is_array($pipePath)) {
                    $invalid = true;
                } elseif (!empty($pipePath)) {
                    foreach ($pipePath as $point) {
                        if (!is_array($point) || count($point) !== 2 || !is_numeric($point[0]) || !is_numeric($point[1])) {
                            $invalid = true;
                            break;
                        }
                    }
                }
                if ($invalid) {
                    return back()->withErrors(['pipe_path' => 'Invalid pipe path format. Must be an array of [lat, lng] pairs.'])->withInput();
                }
            }

            $updatesCreated = false;
            foreach ($validated as $field => $newValue) {
                if ($field === 'password_confirmation' || $field === 'status') continue;
                $oldValue = $customer->$field ?? null;
                if ($field === 'password' && !$newValue) continue;
                if ($newValue != $oldValue) {
                    try {
                        PendingCustomerUpdate::create([
                            'customer_id' => $customer->id,
                            'field' => $field,
                            'old_value' => is_array($oldValue) ? json_encode($oldValue) : $oldValue,
                            'new_value' => is_array($newValue) ? json_encode($newValue) : ($field === 'password' ? Hash::make($newValue) : $newValue),
                            'updated_by' => Auth::guard('staff')->id(),
                        ]);
                        $updatesCreated = true;
                        Log::debug('Pending update created', ['customer_id' => $customer->id, 'field' => $field, 'old_value' => $oldValue, 'new_value' => $newValue]);
                    } catch (\Exception $e) {
                        Log::error('Failed to create pending update', ['customer_id' => $customer->id, 'field' => $field, 'error' => $e->getMessage()]);
                    }
                }
            }

            if (!$updatesCreated) {
                return redirect()->route('staff.customers.index')->with('info', 'No changes detected to submit for approval.');
            }

            Log::info('Location update submitted', ['customer_id' => $customer->id]);
            return redirect()->route('staff.customers.index')->with('success', 'Location update submitted for approval.');
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to update location', ['user_id' => Auth::guard('staff')->id(), 'customer_id' => $customer->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to edit this customer.');
        } catch (\Exception $e) {
            Log::error('Error submitting location update', ['customer_id' => $customer->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while submitting the update: ' . $e->getMessage())->withInput();
        }
    }

   public function filterWards(Request $request)
    {
        try {
            $this->authorize('edit-customer', Customer::class);
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'lga_id' => 'required|exists:lgas,id',
            ]);

            $customer = Customer::findOrFail($request->customer_id);

            // Check if the staff can access this customer
            $staff = auth()->guard('staff')->user();
            $accessibleWardIds = $staff->getAccessibleWardIds();

            if (!empty($accessibleWardIds) && !in_array($customer->ward_id, $accessibleWardIds)) {
                return response()->json(['error' => 'You are not authorized to edit this customer.'], 403);
            }

            $selectedLgaId = $request->lga_id;

            // Check if the selected LGA is accessible to the staff
            $accessibleLgaIds = $staff->getAccessibleLgaIds();
            $lgaQuery = Lga::where('status', 'approved');

            if (!empty($accessibleLgaIds)) {
                $lgaQuery->whereIn('id', $accessibleLgaIds);
            }

            $lgas = $lgaQuery->get();

            // Check if the selectedLgaId is accessible
            if (!empty($accessibleLgaIds) && !in_array($selectedLgaId, $accessibleLgaIds)) {
                return response()->json(['error' => 'You are not authorized to access this LGA.'], 403);
            }

            // Get wards for the selected LGA
            $wardQuery = Ward::where('lga_id', $selectedLgaId)->where('status', 'approved');

            if (!empty($accessibleWardIds)) {
                $wardQuery->whereIn('id', $accessibleWardIds);
            }

            $wards = $wardQuery->get();
            $areas = collect();
            $selectedWardId = null;
            return response()->json([
                'html' => view('staff.customers.partials.edit_address', compact('customer', 'lgas', 'wards', 'areas', 'selectedLgaId', 'selectedWardId'))->render(),
            ]);
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to filter wards', ['user_id' => Auth::guard('staff')->id()]);
            return response()->json(['error' => 'You are not authorized to perform this action.'], 403);
        } catch (\Exception $e) {
            Log::error('Error filtering wards', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while filtering wards: ' . $e->getMessage()], 500);
        }
    }


    public function filterAreas(Request $request)
    {
        try {
            $this->authorize('edit-customer', Customer::class);
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'lga_id' => 'required|exists:lgas,id',
                'ward_id' => 'required|exists:wards,id',
            ]);

            $customer = Customer::findOrFail($request->customer_id);

            // Check if the staff can access this customer
            $staff = auth()->guard('staff')->user();
            $accessibleWardIds = $staff->getAccessibleWardIds();

            if (!empty($accessibleWardIds) && !in_array($customer->ward_id, $accessibleWardIds)) {
                return response()->json(['error' => 'You are not authorized to edit this customer.'], 403);
            }

            $selectedLgaId = $request->lga_id;
            $selectedWardId = $request->ward_id;

            // Check if the selected LGA and Ward are accessible to the staff
            $accessibleLgaIds = $staff->getAccessibleLgaIds();
            $lgaQuery = Lga::where('status', 'approved');

            if (!empty($accessibleLgaIds)) {
                $lgaQuery->whereIn('id', $accessibleLgaIds);
            }

            $lgas = $lgaQuery->get();

            // Check if the selectedLgaId and selectedWardId are accessible
            if (!empty($accessibleLgaIds) && !in_array($selectedLgaId, $accessibleLgaIds)) {
                return response()->json(['error' => 'You are not authorized to access this LGA.'], 403);
            }

            if (!empty($accessibleWardIds) && !in_array($selectedWardId, $accessibleWardIds)) {
                return response()->json(['error' => 'You are not authorized to access this Ward.'], 403);
            }

            // Get wards for the selected LGA
            $wardQuery = Ward::where('lga_id', $selectedLgaId)->where('status', 'approved');

            if (!empty($accessibleWardIds)) {
                $wardQuery->whereIn('id', $accessibleWardIds);
            }

            $wards = $wardQuery->get();

            // Get areas for the selected ward
            $accessibleAreaIds = $staff->getAccessibleAreaIds();
            $areaQuery = Area::where('ward_id', $selectedWardId)->where('status', 'approved');

            if (!empty($accessibleAreaIds)) {
                $areaQuery->whereIn('id', $accessibleAreaIds);
            }

            $areas = $areaQuery->get();
            return response()->json([
                'html' => view('staff.customers.partials.edit_address', compact('customer', 'lgas', 'wards', 'areas', 'selectedLgaId', 'selectedWardId'))->render(),
            ]);
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to filter areas', ['user_id' => Auth::guard('staff')->id()]);
            return response()->json(['error' => 'You are not authorized to perform this action.'], 403);
        } catch (\Exception $e) {
            Log::error('Error filtering areas', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while filtering areas: ' . $e->getMessage()], 500);
        }
    }

    // Add new method to filter areas for customer creation
    public function filterAreasForCustomer(Request $request)
    {
        try {
            $this->authorize('create-customer', Customer::class);
            $request->validate([
                'ward_id' => 'required|exists:wards,id',
            ]);

            $staff = auth()->guard('staff')->user();
            $accessibleAreaIds = $staff->getAccessibleAreaIds();

            $selectedWardId = $request->ward_id;

            // Check if the selected ward is accessible to the staff
            $accessibleWardIds = $staff->getAccessibleWardIds();
            if (!empty($accessibleWardIds) && !in_array($selectedWardId, $accessibleWardIds)) {
                return response()->json(['error' => 'You are not authorized to access this Ward.'], 403);
            }

            // Get areas for the selected ward
            $areaQuery = Area::where('ward_id', $selectedWardId);

            if (!empty($accessibleAreaIds)) {
                $areaQuery->whereIn('id', $accessibleAreaIds);
            }

            $areas = $areaQuery->get();

            // Return HTML options for the dropdown
            $optionsHtml = '<option value="">Select Area</option>';
            foreach ($areas as $area) {
                $optionsHtml .= '<option value="' . $area->id . '">' . $area->name . '</option>';
            }
            $optionsHtml .= '<option value="add_new_area">+ Add New Area</option>';

            return response()->json([
                'options_html' => $optionsHtml
            ]);
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to filter areas in customer creation', ['user_id' => Auth::guard('staff')->id()]);
            return response()->json(['error' => 'You are not authorized to perform this action.'], 403);
        } catch (\Exception $e) {
            Log::error('Error filtering areas in customer creation', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while filtering areas: ' . $e->getMessage()], 500);
        }
    }

    public function pending(Request $request)
    {
        // Set breadcrumbs
        $breadcrumb = app(BreadcrumbService::class);
        $breadcrumb->addHome()->add('Customer Management', route('staff.customers.index'))->add('Pending Changes');

        $pendingUpdates = PendingCustomerUpdate::with(['customer', 'customer.category', 'customer.tariff', 'customer.lga', 'customer.ward', 'customer.area'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('staff.customers.pending_changes', compact('pendingUpdates'));
    }

    public function approvePending(PendingCustomerUpdate $update)
    {
        try {
            $this->authorize('approve-customer', Customer::class);
            $customer = $update->customer;
            $field = $update->field;
            $newValue = $update->new_value;

            if (in_array($field, ['polygon_coordinates', 'pipe_path']) && $newValue) {
                $newValue = json_decode($newValue, true);
            }

            $customer->update([$field => $newValue]);

            // Delete the pending update record after applying the changes
            $update->delete();

            Log::info('Pending update approved and removed', ['customer_id' => $customer->id, 'field' => $field, 'staff_id' => Auth::guard('staff')->id()]);
            return redirect()->route('staff.customers.pending')->with('success', 'Update approved successfully.');
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to approve update', ['user_id' => Auth::guard('staff')->id(), 'update_id' => $update->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to approve updates.');
        } catch (\Exception $e) {
            Log::error('Error approving update', ['update_id' => $update->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while approving the update: ' . $e->getMessage());
        }
    }

    public function rejectPending(PendingCustomerUpdate $update)
    {
        try {
            $this->authorize('reject-customer', Customer::class);
            $customer = $update->customer;
            $field = $update->field;
            $oldValue = $update->old_value;

            // Restore the old value to the customer record
            if (in_array($field, ['polygon_coordinates', 'pipe_path']) && $oldValue) {
                $oldValue = json_decode($oldValue, true);
            }

            $customer->update([$field => $oldValue]);

            // Delete the pending update record after restoring the old value
            $update->delete();

            Log::info('Pending update rejected and old value restored', ['customer_id' => $customer->id, 'field' => $field, 'staff_id' => Auth::guard('staff')->id()]);
            return redirect()->route('staff.customers.pending')->with('success', 'Update rejected and old value restored.');
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to reject pending update', ['user_id' => Auth::guard('staff')->id(), 'update_id' => $update->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to reject updates.');
        } catch (\Exception $e) {
            Log::error('Error rejecting update', ['update_id' => $update->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while rejecting the update: ' . $e->getMessage());
        }
    }

    public function show(Customer $customer)
    {
        try {
            $this->authorize('view-customer', $customer);
            return view('staff.customers.show', compact('customer'));
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized access attempt to view customer', ['user_id' => Auth::guard('staff')->id(), 'customer_id' => $customer->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to view this customer.');
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $this->authorize('delete-customer', $customer);
            $customer->delete();
            Log::info('Customer deleted', ['customer_id' => $customer->id]);
            return redirect()->route('staff.customers.index')->with('success', 'Customer deleted successfully.');
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to delete customer', ['user_id' => Auth::guard('staff')->id(), 'customer_id' => $customer->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to delete this customer.');
        } catch (\Exception $e) {
            Log::error('Error deleting customer', ['customer_id' => $customer->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while deleting the customer: ' . $e->getMessage());
        }
    }

    public function approve(Customer $customer)
    {
        try {
            $this->authorize('approve-customer', Customer::class);
            $customer->update(['status' => 'approved']);
            if (!$customer->billing_id) {
                $customer->billing_id = Customer::generateBillingId($customer);
                $customer->save();
            }

            PendingCustomerUpdate::where('customer_id', $customer->id)
                ->where('status', 'pending')
                ->update(['status' => 'approved']);

            Log::info('Customer approved', ['customer_id' => $customer->id]);
            return redirect()->route('staff.customers.index')->with('success', 'Customer approved successfully.');
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to approve customer', ['user_id' => Auth::guard('staff')->id(), 'customer_id' => $customer->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to approve customers.');
        } catch (\Exception $e) {
            Log::error('Error approving customer', ['customer_id' => $customer->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while approving the customer: ' . $e->getMessage());
        }
    }

    public function reject(Customer $customer)
    {
        try {
            $this->authorize('reject-customer', Customer::class);
            $customer->update(['status' => 'rejected']);

            PendingCustomerUpdate::where('customer_id', $customer->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);

            Log::info('Customer rejected', ['customer_id' => $customer->id]);
            return redirect()->route('staff.customers.index')->with('success', 'Customer rejected successfully.');
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to reject customer', ['user_id' => Auth::guard('staff')->id(), 'customer_id' => $customer->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to reject customers.');
        } catch (\Exception $e) {
            Log::error('Error rejecting customer', ['customer_id' => $customer->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while rejecting the customer: ' . $e->getMessage());
        }
    }

    protected function updateSection(Request $request, Customer $customer, string $part, array $rules, callable $extraValidation = null)
    {
        try {
            $this->authorize('edit-customer', $customer);
            Log::debug('Update request received', ['customer_id' => $customer->id, 'part' => $part, 'input' => $request->all()]);

            try {
                $validated = $request->validate($rules);
                Log::debug('Validation passed', ['customer_id' => $customer->id, 'validated' => $validated]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('Validation failed', ['customer_id' => $customer->id, 'errors' => $e->errors()]);
                return back()->withErrors($e->errors())->withInput();
            }

            if ($extraValidation) {
                $extraResult = $extraValidation($validated);
                if ($extraResult && isset($extraResult['error'])) {
                    Log::warning('Extra validation failed', ['customer_id' => $customer->id, 'errors' => $extraResult['error']]);
                    return back()->withErrors($extraResult['error'])->withInput();
                }
            }

            $updatesCreated = false;
            foreach ($validated as $field => $newValue) {
                if ($field === 'password_confirmation' || $field === 'status') continue;
                $oldValue = $customer->$field ?? null;
                if ($field === 'password' && !$newValue) continue;
                if ($newValue != $oldValue) {
                    try {
                        PendingCustomerUpdate::create([
                            'customer_id' => $customer->id,
                            'field' => $field,
                            'old_value' => is_array($oldValue) ? json_encode($oldValue) : $oldValue,
                            'new_value' => is_array($newValue) ? json_encode($newValue) : ($field === 'password' ? Hash::make($newValue) : $newValue),
                            'updated_by' => Auth::guard('staff')->id(),
                        ]);
                        $updatesCreated = true;
                        Log::debug('Pending update created', ['customer_id' => $customer->id, 'field' => $field, 'old_value' => $oldValue, 'new_value' => $newValue]);
                    } catch (\Exception $e) {
                        Log::error('Failed to create pending update', ['customer_id' => $customer->id, 'field' => $field, 'error' => $e->getMessage()]);
                    }
                }
            }

            if (!$updatesCreated) {
                Log::warning('No updates created, no fields changed', ['customer_id' => $customer->id]);
                return redirect()->route('staff.customers.index')->with('info', 'No changes detected to submit for approval.');
            }

            Log::info('Customer update submitted', ['customer_id' => $customer->id, 'part' => $part]);
            return redirect()->route('staff.customers.index')->with('success', 'Update submitted for approval.');
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized attempt to update customer', ['user_id' => Auth::guard('staff')->id(), 'customer_id' => $customer->id]);
            return redirect()->route('staff.dashboard')->with('error', 'You are not authorized to edit this customer.');
        } catch (\Exception $e) {
            Log::error('Error submitting customer update', ['customer_id' => $customer->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while submitting the update: ' . $e->getMessage())->withInput();
        }
    }

 protected function getValidationRules(string $part)
    {
        $rules = [
            'personal' => [
                'rules' => [
                    'first_name' => 'required|string|max:255',
                    'surname' => 'required|string|max:255',
                    'middle_name' => 'nullable|string|max:255',
                    'email' => 'required|email|unique:customers,email,' . request()->route('customer')->id,
                    'phone_number' => 'required|string|min:10|regex:/^[0-9]+$/|unique:customers,phone_number,' . request()->route('customer')->id,
                    'alternate_phone_number' => 'nullable|string|min:10|regex:/^[0-9]+$/|unique:customers,alternate_phone_number,' . request()->route('customer')->id . ',id',
                ],
            ],
            'address' => [
                'rules' => [
                    'lga_id' => 'required|exists:lgas,id',
                    'ward_id' => 'required|exists:wards,id',
                    'area_id' => 'required|exists:areas,id',
                    'street_name' => 'nullable|string|max:255',
                    'house_number' => 'nullable|string|max:255',
                    'landmark' => 'required|string|max:255',
                ],
                'extraValidation' => function ($validated) {
                    $ward = Ward::where('id', $validated['ward_id'])->where('lga_id', $validated['lga_id'])->first();
                    if (!$ward) {
                        return ['error' => ['ward_id' => 'Selected ward does not belong to the chosen LGA.']];
                    }
                    $area = Area::where('id', $validated['area_id'])->where('ward_id', $validated['ward_id'])->first();
                    if (!$area) {
                        return ['error' => ['area_id' => 'Selected area does not belong to the chosen ward.']];
                    }

                    return null;
                },
            ],
            'billing' => [
                'rules' => [
                    'category_id' => 'required|exists:categories,id',
                    'tariff_id' => 'required|exists:tariffs,id',
                    'delivery_code' => 'nullable|string|max:255',
                    'billing_condition' => 'required|in:Metered,Non-Metered',
                    'water_supply_status' => 'required|in:Functional,Non-Functional',
                ],
                'extraValidation' => function ($validated) {
                    $tariff = Tariff::where('id', $validated['tariff_id'])->where('category_id', $validated['category_id'])->first();
                    if (!$tariff) {
                        return ['error' => ['tariff_id' => 'Selected tariff does not belong to the chosen category.']];
                    }
                    return null;
                },
            ],
            'location' => [
                'rules' => [
                    'latitude' => 'required|numeric|between:-90,90',
                    'longitude' => 'required|numeric|between:-180,180',
                    'altitude' => 'nullable|numeric',
                    'pipe_path' => 'nullable|json',
                    'polygon_coordinates' => 'nullable|json',
                    'password' => 'nullable|string|min:8|confirmed',
                ],
                'extraValidation' => function ($validated) {
                    if ($validated['polygon_coordinates']) {
                        $coords = json_decode($validated['polygon_coordinates'], true);
                        if (!is_array($coords) || (!empty($coords) && !collect($coords)->every(function($point) {
                            return is_array($point) && count($point) === 2 && is_numeric($point[0]) && is_numeric($point[1]);
                        }))) {
                            return ['error' => ['polygon_coordinates' => 'Invalid polygon coordinates format. Must be an array of [lat, lng] pairs.']];
                        }
                    }
                    if ($validated['pipe_path']) {
                        $pipePath = json_decode($validated['pipe_path'], true);
                        if (!is_array($pipePath) || (!empty($pipePath) && !collect($pipePath)->every(function($point) {
                            return is_array($point) && count($point) === 2 && is_numeric($point[0]) && is_numeric($point[1]);
                        }))) {
                            return ['error' => ['pipe_path' => 'Invalid pipe path format. Must be an array of [lat, lng] pairs.']];
                        }
                    }
                    return null;
                },
            ],
        ];

        return $rules[$part] ?? null;
    }
}
