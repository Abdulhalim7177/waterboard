<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Bill;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;
use App\Models\Category;
use App\Models\Tariff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;
use Carbon\Carbon;

class GisController extends Controller
{
    public function index()
    {
        try {
            $lgas = Lga::whereNotNull('latitude')->whereNotNull('longitude')->get(['id', 'name', 'latitude', 'longitude']);
            $wards = Ward::whereNotNull('latitude')->whereNotNull('longitude')->get(['id', 'name', 'latitude', 'longitude', 'lga_id']);
            $areas = Area::whereNotNull('latitude')->whereNotNull('longitude')->get(['id', 'name', 'latitude', 'longitude', 'ward_id']);
            $categories = Category::where('status', 'approved')->get(['id', 'name']);
            $tariffs = Tariff::where('status', 'approved')->get(['id', 'name']);
            $defaultStartDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $defaultEndDate = Carbon::now()->endOfMonth()->format('Y-m-d');

            return view('staff.gis', [
                'geoJson' => ['type' => 'FeatureCollection', 'features' => []],
                'lgas' => $lgas,
                'wards' => $wards,
                'areas' => $areas,
                'categories' => $categories,
                'tariffs' => $tariffs,
                'defaultStartDate' => $defaultStartDate,
                'defaultEndDate' => $defaultEndDate,
                'error' => null
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to load GIS index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return view('staff.gis', [
                'geoJson' => ['type' => 'FeatureCollection', 'features' => []],
                'lgas' => [],
                'wards' => [],
                'areas' => [],
                'categories' => [],
                'tariffs' => [],
                'defaultStartDate' => Carbon::now()->startOfMonth()->format('Y-m-d'),
                'defaultEndDate' => Carbon::now()->endOfMonth()->format('Y-m-d'),
                'error' => 'Failed to load GIS data. Please check the logs.'
            ]);
        }
    }

    public function filter(Request $request)
    {
        try {
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d')))->endOfDay()->format('Y-m-d H:i:s');

            // Build the base query with whereHas to ensure customers have at least one approved bill in the date range
            $query = Customer::query()
                ->with([
                    'category', 'tariff', 'lga', 'ward', 'area',
                    'bills' => function ($q) use ($startDate, $endDate) {
                        $q->where('approval_status', 'approved')
                          ->whereBetween('created_at', [$startDate, $endDate]);
                    }
                ])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->whereHas('bills', function ($q) use ($startDate, $endDate) {
                    $q->where('approval_status', 'approved')
                      ->whereBetween('created_at', [$startDate, $endDate]);
                });

            // Filter by payment status using whereHas/whereDoesntHave (equivalent to original havingRaw on sum(balance))
            if ($request->has('payment_status') && in_array($request->payment_status, ['paid', 'unpaid'])) {
                if ($request->payment_status === 'paid') {
                    // No unpaid bills in the period (sum(balance) == 0)
                    $query->whereDoesntHave('bills', function ($q) use ($startDate, $endDate) {
                        $q->where('approval_status', 'approved')
                          ->whereBetween('created_at', [$startDate, $endDate])
                          ->where('balance', '>', 0);
                    });
                } else {
                    // At least one unpaid bill in the period (sum(balance) > 0)
                    $query->whereHas('bills', function ($q) use ($startDate, $endDate) {
                        $q->where('approval_status', 'approved')
                          ->whereBetween('created_at', [$startDate, $endDate])
                          ->where('balance', '>', 0);
                    });
                }
            }

            if ($request->has('category_id') && $request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->has('tariff_id') && $request->tariff_id) {
                $query->where('tariff_id', $request->tariff_id);
            }

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereRaw("CONCAT(first_name, ' ', surname) LIKE ?", ["%$search%"])
                      ->orWhere('billing_id', 'LIKE', "%$search%");
                });
            }

            $customers = $query->get();

            $totalCustomers = $customers->count();
            // Use eager-loaded bills for calculations (optimized, no extra DB queries)
            $paidCustomers = $customers->filter(function ($customer) {
                return $customer->bills->sum('balance') == 0;
            })->count();
            $unpaidCustomers = $customers->filter(function ($customer) {
                return $customer->bills->sum('balance') > 0;
            })->count();
            $totalBilled = $customers->reduce(function ($carry, $customer) {
                return $carry + $customer->bills->sum('amount');
            }, 0);
            $totalUnpaid = $customers->reduce(function ($carry, $customer) {
                return $carry + $customer->bills->sum('balance');
            }, 0);
            $categoryBreakdown = $customers->groupBy('category_id')->map(function ($group) {
                return [
                    'name' => $group->first()->category->name ?? 'N/A',
                    'count' => $group->count()
                ];
            })->values();
            $tariffBreakdown = $customers->groupBy('tariff_id')->map(function ($group) {
                return [
                    'name' => $group->first()->tariff->name ?? 'N/A',
                    'count' => $group->count()
                ];
            })->values();

            $geoJson = [
                'type' => 'FeatureCollection',
                'features' => $customers->map(function ($customer) {
                    // Use eager-loaded bills for sums
                    $unpaidBills = $customer->bills->sum('balance');
                    $totalBilledCustomer = $customer->bills->sum('amount');
                    $totalUnpaidCustomer = $customer->bills->sum('balance');
                    $pipePath = $customer->pipe_path ? json_decode($customer->pipe_path, true) : [];
                    Log::info('Processing customer for GIS filter', [
                        'customer_id' => $customer->id,
                        'name' => $customer->first_name . ' ' . $customer->surname,
                        'latitude' => $customer->latitude,
                        'longitude' => $customer->longitude,
                        'unpaid_bills' => $unpaidBills,
                        'total_billed' => $totalBilledCustomer,
                        'total_unpaid' => $totalUnpaidCustomer,
                        'pipe_path' => $pipePath,
                    ]);
                    return [
                        'type' => 'Feature',
                        'geometry' => [
                            'type' => 'Point',
                            'coordinates' => [$customer->longitude, $customer->latitude]
                        ],
                        'properties' => [
                            'customer_id' => $customer->id,
                            'name' => $customer->first_name . ' ' . $customer->surname,
                            'billing_id' => $customer->billing_id ?? 'N/A',
                            'payment_status' => $unpaidBills > 0 ? 'unpaid' : 'paid',
                            'category' => $customer->category->name ?? 'N/A',
                            'tariff' => $customer->tariff->name ?? 'N/A',
                            'lga' => $customer->lga->name ?? 'N/A',
                            'ward' => $customer->ward->name ?? 'N/A',
                            'area' => $customer->area->name ?? 'N/A',
                            'polygon_coordinates' => $customer->polygon_coordinates ? json_decode($customer->polygon_coordinates, true) : [],
                            'pipe_path' => $pipePath,
                            'total_billed' => $totalBilledCustomer,
                            'total_unpaid' => $totalUnpaidCustomer
                        ]
                    ];
                })->toArray(),
                'summary' => [
                    'total_customers' => $totalCustomers,
                    'paid_customers' => $paidCustomers,
                    'unpaid_customers' => $unpaidCustomers,
                    'total_billed' => $totalBilled,
                    'total_unpaid' => $totalUnpaid,
                    'category_breakdown' => $categoryBreakdown,
                    'tariff_breakdown' => $tariffBreakdown,
                    'start_date' => $startDate,
                    'end_date' => Carbon::parse($endDate)->format('Y-m-d')
                ]
            ];

            if ($totalCustomers === 0) {
                Log::info('No customers found with bills in the selected date range', [
                    'filters' => $request->all(),
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]);
                return response()->json([
                    'type' => 'FeatureCollection',
                    'features' => [],
                    'summary' => [
                        'total_customers' => 0,
                        'paid_customers' => 0,
                        'unpaid_customers' => 0,
                        'total_billed' => 0,
                        'total_unpaid' => 0,
                        'category_breakdown' => [],
                        'tariff_breakdown' => [],
                        'start_date' => $startDate,
                        'end_date' => Carbon::parse($endDate)->format('Y-m-d')
                    ],
                    'error' => 'No customers found with bills in the selected date range.'
                ]);
            }

            Log::info('GIS filter GeoJSON and summary generated', [
                'customer_count' => $totalCustomers,
                'total_billed' => $totalBilled,
                'total_unpaid' => $totalUnpaid,
                'filters' => $request->all(),
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);

            return response()->json($geoJson);
        } catch (\Exception $e) {
            Log::error('Failed to filter GIS data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'type' => 'FeatureCollection',
                'features' => [],
                'summary' => [
                    'total_customers' => 0,
                    'paid_customers' => 0,
                    'unpaid_customers' => 0,
                    'total_billed' => 0,
                    'total_unpaid' => 0,
                    'category_breakdown' => [],
                    'tariff_breakdown' => [],
                    'start_date' => $startDate,
                    'end_date' => Carbon::parse($endDate)->format('Y-m-d')
                ],
                'error' => 'Failed to filter customer data. Please check the logs.'
            ], 500);
        }
    }

    public function exportCsv(Request $request)
    {
        try {
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d')))->endOfDay()->format('Y-m-d H:i:s');

            $customers = $this->getFilteredCustomers($request);

            if ($customers->isEmpty()) {
                Log::info('No customers found for CSV export', [
                    'filters' => $request->all(),
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]);
                return redirect()->route('staff.gis')->with('error', 'No customers found with bills in the selected date range.');
            }

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="customer_data_' . str_replace('-', '', $startDate) . '_to_' . str_replace('-', '', Carbon::parse($endDate)->format('Y-m-d')) . '_' . now()->format('Ymd_His') . '.csv"',
            ];

            $columns = [
                'Customer ID', 'Name', 'Billing ID', 'Payment Status', 'Category', 'Tariff', 
                'LGA', 'Ward', 'Area', 'Latitude', 'Longitude', 'Polygon Coordinates', 
                'Pipe Path', 'Total Bill Amount', 'Total Unpaid Balance'
            ];

            $callback = function() use ($customers, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($customers as $customer) {
                    // Use eager-loaded bills for sums
                    $unpaidBills = $customer->bills->sum('balance');
                    $totalBilled = $customer->bills->sum('amount');
                    $totalUnpaid = $customer->bills->sum('balance');
                    fputcsv($file, [
                        $customer->id,
                        $customer->first_name . ' ' . $customer->surname,
                        $customer->billing_id ?? 'N/A',
                        $unpaidBills > 0 ? 'unpaid' : 'paid',
                        $customer->category->name ?? 'N/A',
                        $customer->tariff->name ?? 'N/A',
                        $customer->lga->name ?? 'N/A',
                        $customer->ward->name ?? 'N/A',
                        $customer->area->name ?? 'N/A',
                        $customer->latitude,
                        $customer->longitude,
                        $customer->polygon_coordinates ?? '[]',
                        $customer->pipe_path ?? '[]',
                        number_format($totalBilled, 2, '.', ''),
                        number_format($totalUnpaid, 2, '.', '')
                    ]);
                }

                fclose($file);
            };

            Log::info('Exporting customer data as CSV', [
                'customer_count' => $customers->count(),
                'filters' => $request->all(),
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);

            return Response::stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Failed to export CSV data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('staff.gis')->with('error', 'Failed to export CSV data. Please check the logs.');
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d')))->endOfDay()->format('Y-m-d H:i:s');
            Log::info('Exporting customer data as Excel', [
                'filters' => $request->all(),
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
            $customers = $this->getFilteredCustomers($request);
            if ($customers->isEmpty()) {
                return redirect()->route('staff.gis')->with('error', 'No customers found with bills in the selected date range.');
            }
            return Excel::download(new CustomersExport($request->all()), 'customer_data_' . str_replace('-', '', $startDate) . '_to_' . str_replace('-', '', Carbon::parse($endDate)->format('Y-m-d')) . '_' . now()->format('Ymd_His') . '.xlsx');
        } catch (\Exception $e) {
            Log::error('Failed to export Excel data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('staff.gis')->with('error', 'Failed to export Excel data. Please check the logs.');
        }
    }

    private function getFilteredCustomers(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d')))->endOfDay()->format('Y-m-d H:i:s');

        // Same query structure as in filter() for consistency
        $query = Customer::query()
            ->with([
                'category', 'tariff', 'lga', 'ward', 'area',
                'bills' => function ($q) use ($startDate, $endDate) {
                    $q->where('approval_status', 'approved')
                      ->whereBetween('created_at', [$startDate, $endDate]);
                }
            ])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereHas('bills', function ($q) use ($startDate, $endDate) {
                $q->where('approval_status', 'approved')
                  ->whereBetween('created_at', [$startDate, $endDate]);
            });

        if ($request->has('payment_status') && in_array($request->payment_status, ['paid', 'unpaid'])) {
            if ($request->payment_status === 'paid') {
                $query->whereDoesntHave('bills', function ($q) use ($startDate, $endDate) {
                    $q->where('approval_status', 'approved')
                      ->whereBetween('created_at', [$startDate, $endDate])
                      ->where('balance', '>', 0);
                });
            } else {
                $query->whereHas('bills', function ($q) use ($startDate, $endDate) {
                    $q->where('approval_status', 'approved')
                      ->whereBetween('created_at', [$startDate, $endDate])
                      ->where('balance', '>', 0);
                });
            }
        }

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('tariff_id') && $request->tariff_id) {
            $query->where('tariff_id', $request->tariff_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(first_name, ' ', surname) LIKE ?", ["%$search%"])
                  ->orWhere('billing_id', 'LIKE', "%$search%");
            });
        }

        return $query->get();
    }
}