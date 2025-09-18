<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Customer;
use App\Models\PendingCustomerUpdate;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\Tariff;
use App\Models\Category;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:staff', 'restrict.login']);
        $this->middleware('permission:view-analytics', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        // Handle filters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $statusFilter = $request->input('status_filter');

        // Cache key for optimization
        $cacheKey = 'analytics_' . md5($request->fullUrl());
        $cacheTTL = 60; // Cache for 60 minutes

        // Clear cache if filters changed
        if ($request->hasAny(['start_date', 'end_date', 'status_filter'])) {
            Cache::forget($cacheKey);
        }

        $data = Cache::remember($cacheKey, $cacheTTL, function () use ($startDate, $endDate, $statusFilter) {
            // Determine date range
            $start = $startDate && $endDate ? \Carbon\Carbon::parse($startDate)->startOfDay() : now()->subMonths(11)->startOfMonth();
            $end = $startDate && $endDate ? \Carbon\Carbon::parse($endDate)->endOfDay() : now()->endOfMonth();

            // Basic stats with filters
            $stats = [
                'staff' => [
                    'total' => Staff::when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                    'approved' => Staff::where('status', 'approved')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->count(),
                ],
                'customers' => [
                    'total' => Customer::when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                    'approved' => Customer::where('status', 'approved')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->count(),
                    'pending' => Customer::where('status', 'pending')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->count(),
                    'rejected' => Customer::where('status', 'rejected')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->count(),
                    'monthly_registered' => Customer::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                        ->when($statusFilter && in_array($statusFilter, ['approved', 'pending', 'rejected']), function ($query) use ($statusFilter) {
                            return $query->where('status', $statusFilter);
                        })->count(),
                ],
                'pending_updates' => [
                    'total' => PendingCustomerUpdate::when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                    'pending' => PendingCustomerUpdate::where('status', 'pending')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->count(),
                ],
                'bills' => [
                    'total' => Bill::when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->when($statusFilter && in_array($statusFilter, ['pending', 'overdue']), function ($query) use ($statusFilter) {
                        return $query->where('status', $statusFilter);
                    })->count(),
                    'pending' => Bill::where('status', 'pending')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->count(),
                    'overdue' => Bill::where('status', 'overdue')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->count(),
                    'total_amount' => Bill::where('approval_status', 'approved')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->when($statusFilter && in_array($statusFilter, ['pending', 'overdue']), function ($query) use ($statusFilter) {
                            return $query->where('status', $statusFilter);
                        })->sum('amount'),
                ],
                'payments' => [
                    'total' => Payment::when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                    'successful' => Payment::where('payment_status', 'successful')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->count(),
                    'total_amount' => Payment::where('payment_status', 'successful')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->sum('amount'),
                ],
                'tariffs' => [
                    'total' => Tariff::when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                    'approved' => Tariff::where('status', 'approved')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->count(),
                ],
                'categories' => [
                    'total' => Category::when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                    'approved' => Category::where('status', 'approved')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->count(),
                ],
                'lgas' => [
                    'total' => Lga::when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                    'approved' => Lga::where('status', 'approved')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->count(),
                ],
                'wards' => [
                    'total' => Ward::when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                    'approved' => Ward::where('status', 'approved')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->count(),
                ],
                'areas' => [
                    'total' => Area::when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                    'approved' => Area::where('status', 'approved')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->count(),
                ],
                'complaints' => [
                    'total' => Complaint::when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->when($statusFilter && in_array($statusFilter, ['pending', 'in_progress', 'resolved']), function ($query) use ($statusFilter) {
                        return $query->where('status', $statusFilter);
                    })->count(),
                    'pending' => Complaint::where('status', 'pending')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->count(),
                    'in_progress' => Complaint::where('status', 'in_progress')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->count(),
                    'resolved' => Complaint::where('status', 'resolved')
                        ->when($startDate && $endDate, function ($query) use ($start, $end) {
                            return $query->whereBetween('created_at', [$start, $end]);
                        })->count(),
                ],
            ];

            // Log stats and filters for debugging
            Log::debug('Analytics Stats', [
                'stats' => $stats,
                'filters' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status_filter' => $statusFilter,
                ],
            ]);

            // Trend data for charts (last 12 months or custom range)
            $months = [];
            $billAmounts = [];
            $paymentAmounts = [];
            $complaintCounts = [];

            // Generate monthly data
            $month = $start->copy()->startOfMonth();
            while ($month <= $end) {
                $monthStart = $month->copy()->startOfMonth();
                $monthEnd = $month->copy()->endOfMonth();
                $months[] = $month->format('M Y');

                // Bill trends
                $billQuery = Bill::where('approval_status', 'approved')
                    ->whereBetween('created_at', [$monthStart, $monthEnd]);
                if ($statusFilter && in_array($statusFilter, ['pending', 'overdue'])) {
                    $billQuery->where('status', $statusFilter);
                }
                $billAmount = (float) $billQuery->sum('amount');
                $billAmounts[] = $billAmount;

                // Payment trends
                $paymentQuery = Payment::where('payment_status', 'successful')
                    ->when($startDate && $endDate, function ($query) use ($monthStart, $monthEnd) {
                        return $query->whereBetween('created_at', [$monthStart, $monthEnd]);
                    });
                $paymentAmount = (float) $paymentQuery->sum('amount');
                $paymentAmounts[] = $paymentAmount;

                // Complaint trends
                $complaintQuery = Complaint::whereBetween('created_at', [$monthStart, $monthEnd]);
                if ($statusFilter && in_array($statusFilter, ['pending', 'in_progress', 'resolved'])) {
                    $complaintQuery->where('status', $statusFilter);
                }
                $complaintCount = (int) $complaintQuery->count();
                $complaintCounts[] = $complaintCount;

                // Debug logging
                Log::debug('Analytics Data for ' . $month->format('M Y'), [
                    'bill_amount' => $billAmount,
                    'payment_amount' => $paymentAmount,
                    'complaint_count' => $complaintCount,
                    'query_filters' => [
                        'bill_query' => $billQuery->toSql(),
                        'payment_query' => $paymentQuery->toSql(),
                        'complaint_query' => $complaintQuery->toSql(),
                        'bindings' => [
                            'bill' => $billQuery->getBindings(),
                            'payment' => $paymentQuery->getBindings(),
                            'complaint' => $complaintQuery->getBindings(),
                        ],
                    ],
                ]);

                $month->addMonth();
            }

            // Pie chart data
            $tariffByCategory = Category::withCount(['tariffs' => function ($query) use ($startDate, $endDate, $statusFilter) {
                if ($statusFilter && in_array($statusFilter, ['approved', 'pending'])) {
                    $query->where('status', $statusFilter);
                }
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }])->get()->pluck('tariffs_count', 'name')->toArray();

            $customersByCategory = Category::withCount(['customers' => function ($query) use ($startDate, $endDate, $statusFilter) {
                if ($statusFilter && in_array($statusFilter, ['approved', 'pending', 'rejected'])) {
                    $query->where('status', $statusFilter);
                }
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }])->get()->pluck('customers_count', 'name')->toArray();

            $customersByTariff = Tariff::withCount(['customers' => function ($query) use ($startDate, $endDate, $statusFilter) {
                if ($statusFilter && in_array($statusFilter, ['approved', 'pending', 'rejected'])) {
                    $query->where('status', $statusFilter);
                }
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }])->get()->pluck('customers_count', 'name')->toArray();

            $customersByLga = Lga::withCount(['customers' => function ($query) use ($startDate, $endDate, $statusFilter) {
                if ($statusFilter && in_array($statusFilter, ['approved', 'pending', 'rejected'])) {
                    $query->where('status', $statusFilter);
                }
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }])->get()->pluck('customers_count', 'name')->toArray();

            return compact(
                'stats', 'months', 'billAmounts', 'paymentAmounts', 'complaintCounts',
                'tariffByCategory', 'customersByCategory', 'customersByTariff', 'customersByLga'
            );
        });

        return view('staff.analytics.index', $data);
    }

    /**
     * Export analytics data as CSV
     */
    public function exportCsv(Request $request)
    {
        try {
            // Get the current analytics data
            $data = $this->getAnalyticsData($request);
            
            // Create a streamed response for CSV export
            $filename = 'analytics_report_' . now()->format('Y-m-d_H-i-s') . '.csv';
            
            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                
                // Add headers
                fputcsv($file, ['Analytics Report - Generated on ' . now()->format('Y-m-d H:i:s')]);
                fputcsv($file, []);
                
                // Summary Statistics
                fputcsv($file, ['Summary Statistics']);
                fputcsv($file, ['Metric', 'Value']);
                fputcsv($file, ['Total Staff', $data['stats']['staff']['total']]);
                fputcsv($file, ['Approved Staff', $data['stats']['staff']['approved']]);
                fputcsv($file, ['Total Customers', $data['stats']['customers']['total']]);
                fputcsv($file, ['Approved Customers', $data['stats']['customers']['approved']]);
                fputcsv($file, ['Pending Customers', $data['stats']['customers']['pending']]);
                fputcsv($file, ['Total Bills', $data['stats']['bills']['total']]);
                fputcsv($file, ['Total Payments', $data['stats']['payments']['total']]);
                fputcsv($file, ['Total Amount Billed', '₦' . number_format($data['stats']['bills']['total_amount'], 2)]);
                fputcsv($file, ['Total Amount Paid', '₦' . number_format($data['stats']['payments']['total_amount'], 2)]);
                fputcsv($file, []);
                
                // Monthly Trends Data
                fputcsv($file, ['Monthly Trends']);
                fputcsv($file, array_merge(['Month'], $data['months']));
                fputcsv($file, array_merge(['Bills (₦)'], $data['billAmounts']));
                fputcsv($file, array_merge(['Payments (₦)'], $data['paymentAmounts']));
                fputcsv($file, array_merge(['Complaints'], $data['complaintCounts']));
                fputcsv($file, []);
                
                // Close the file
                fclose($file);
            };
            
            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to export analytics CSV', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Failed to export CSV. Please try again.');
        }
    }

    /**
     * Export analytics data as Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            // Get the current analytics data
            $data = $this->getAnalyticsData($request);
            
            // Create a streamed response for Excel export
            $filename = 'analytics_report_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            
            $callback = function() use ($data) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                
                // Add headers
                $sheet->setCellValue('A1', 'Analytics Report - Generated on ' . now()->format('Y-m-d H:i:s'));
                $sheet->mergeCells('A1:D1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                
                // Summary Statistics
                $row = 3;
                $sheet->setCellValue('A' . $row, 'Summary Statistics');
                $sheet->mergeCells('A' . $row . ':D' . $row);
                $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
                
                $row++;
                $sheet->setCellValue('A' . ++$row, 'Metric');
                $sheet->setCellValue('B' . $row, 'Value');
                $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
                
                $sheet->setCellValue('A' . ++$row, 'Total Staff');
                $sheet->setCellValue('B' . $row, $data['stats']['staff']['total']);
                
                $sheet->setCellValue('A' . ++$row, 'Approved Staff');
                $sheet->setCellValue('B' . $row, $data['stats']['staff']['approved']);
                
                $sheet->setCellValue('A' . ++$row, 'Total Customers');
                $sheet->setCellValue('B' . $row, $data['stats']['customers']['total']);
                
                $sheet->setCellValue('A' . ++$row, 'Approved Customers');
                $sheet->setCellValue('B' . $row, $data['stats']['customers']['approved']);
                
                $sheet->setCellValue('A' . ++$row, 'Pending Customers');
                $sheet->setCellValue('B' . $row, $data['stats']['customers']['pending']);
                
                $sheet->setCellValue('A' . ++$row, 'Total Bills');
                $sheet->setCellValue('B' . $row, $data['stats']['bills']['total']);
                
                $sheet->setCellValue('A' . ++$row, 'Total Payments');
                $sheet->setCellValue('B' . $row, $data['stats']['payments']['total']);
                
                $sheet->setCellValue('A' . ++$row, 'Total Amount Billed');
                $sheet->setCellValue('B' . $row, '₦' . number_format($data['stats']['bills']['total_amount'], 2));
                
                $sheet->setCellValue('A' . ++$row, 'Total Amount Paid');
                $sheet->setCellValue('B' . $row, '₦' . number_format($data['stats']['payments']['total_amount'], 2));
                
                // Monthly Trends Data
                $row += 2;
                $sheet->setCellValue('A' . $row, 'Monthly Trends');
                $sheet->mergeCells('A' . $row . ':D' . $row);
                $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
                
                $row++;
                $col = 'A';
                $sheet->setCellValue($col++ . $row, 'Month');
                $sheet->setCellValue($col++ . $row, 'Bills (₦)');
                $sheet->setCellValue($col++ . $row, 'Payments (₦)');
                $sheet->setCellValue($col . $row, 'Complaints');
                $sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setBold(true);
                
                for ($i = 0; $i < count($data['months']); $i++) {
                    $row++;
                    $col = 'A';
                    $sheet->setCellValue($col++ . $row, $data['months'][$i]);
                    $sheet->setCellValue($col++ . $row, $data['billAmounts'][$i]);
                    $sheet->setCellValue($col++ . $row, $data['paymentAmounts'][$i]);
                    $sheet->setCellValue($col . $row, $data['complaintCounts'][$i]);
                }
                
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            };
            
            return response()->stream($callback, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to export analytics Excel', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Failed to export Excel. Please try again.');
        }
    }

    /**
     * Generate detailed analytics report
     */
    public function generateReport(Request $request)
    {
        try {
            // Get the current analytics data
            $data = $this->getAnalyticsData($request);
            
            // Return the report view with data
            return view('staff.analytics.report', compact('data'));
        } catch (\Exception $e) {
            Log::error('Failed to generate analytics report', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Failed to generate report. Please try again.');
        }
    }

    /**
     * View detailed analytics data
     */
    public function viewDetails(Request $request)
    {
        try {
            // Get the current analytics data
            $data = $this->getAnalyticsData($request);
            
            // Return the details view with data
            return view('staff.analytics.details', compact('data'));
        } catch (\Exception $e) {
            Log::error('Failed to view analytics details', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Failed to view details. Please try again.');
        }
    }

    /**
     * Helper method to get analytics data
     */
    private function getAnalyticsData(Request $request)
    {
        // Handle filters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $statusFilter = $request->input('status_filter');

        // Determine date range
        $start = $startDate && $endDate ? \Carbon\Carbon::parse($startDate)->startOfDay() : now()->subMonths(11)->startOfMonth();
        $end = $startDate && $endDate ? \Carbon\Carbon::parse($endDate)->endOfDay() : now()->endOfMonth();

        // Basic stats with filters
        $stats = [
            'staff' => [
                'total' => Staff::when($startDate && $endDate, function ($query) use ($start, $end) {
                    return $query->whereBetween('created_at', [$start, $end]);
                })->count(),
                'approved' => Staff::where('status', 'approved')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
            ],
            'customers' => [
                'total' => Customer::when($startDate && $endDate, function ($query) use ($start, $end) {
                    return $query->whereBetween('created_at', [$start, $end]);
                })->count(),
                'approved' => Customer::where('status', 'approved')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                'pending' => Customer::where('status', 'pending')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                'rejected' => Customer::where('status', 'rejected')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                'monthly_registered' => Customer::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                    ->when($statusFilter && in_array($statusFilter, ['approved', 'pending', 'rejected']), function ($query) use ($statusFilter) {
                        return $query->where('status', $statusFilter);
                    })->count(),
            ],
            'pending_updates' => [
                'total' => PendingCustomerUpdate::when($startDate && $endDate, function ($query) use ($start, $end) {
                    return $query->whereBetween('created_at', [$start, $end]);
                })->count(),
                'pending' => PendingCustomerUpdate::where('status', 'pending')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
            ],
            'bills' => [
                'total' => Bill::when($startDate && $endDate, function ($query) use ($start, $end) {
                    return $query->whereBetween('created_at', [$start, $end]);
                })->when($statusFilter && in_array($statusFilter, ['pending', 'overdue']), function ($query) use ($statusFilter) {
                    return $query->where('status', $statusFilter);
                })->count(),
                'pending' => Bill::where('status', 'pending')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                'overdue' => Bill::where('status', 'overdue')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                'total_amount' => Bill::where('approval_status', 'approved')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->when($statusFilter && in_array($statusFilter, ['pending', 'overdue']), function ($query) use ($statusFilter) {
                        return $query->where('status', $statusFilter);
                    })->sum('amount'),
            ],
            'payments' => [
                'total' => Payment::when($startDate && $endDate, function ($query) use ($start, $end) {
                    return $query->whereBetween('created_at', [$start, $end]);
                })->count(),
                'successful' => Payment::where('payment_status', 'successful')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                'total_amount' => Payment::where('payment_status', 'successful')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->sum('amount'),
            ],
            'tariffs' => [
                'total' => Tariff::when($startDate && $endDate, function ($query) use ($start, $end) {
                    return $query->whereBetween('created_at', [$start, $end]);
                })->count(),
                'approved' => Tariff::where('status', 'approved')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
            ],
            'categories' => [
                'total' => Category::when($startDate && $endDate, function ($query) use ($start, $end) {
                    return $query->whereBetween('created_at', [$start, $end]);
                })->count(),
                'approved' => Category::where('status', 'approved')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
            ],
            'lgas' => [
                'total' => Lga::when($startDate && $endDate, function ($query) use ($start, $end) {
                    return $query->whereBetween('created_at', [$start, $end]);
                })->count(),
                'approved' => Lga::where('status', 'approved')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
            ],
            'wards' => [
                'total' => Ward::when($startDate && $endDate, function ($query) use ($start, $end) {
                    return $query->whereBetween('created_at', [$start, $end]);
                })->count(),
                'approved' => Ward::where('status', 'approved')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
            ],
            'areas' => [
                'total' => Area::when($startDate && $endDate, function ($query) use ($start, $end) {
                    return $query->whereBetween('created_at', [$start, $end]);
                })->count(),
                'approved' => Area::where('status', 'approved')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
            ],
            'complaints' => [
                'total' => Complaint::when($startDate && $endDate, function ($query) use ($start, $end) {
                    return $query->whereBetween('created_at', [$start, $end]);
                })->when($statusFilter && in_array($statusFilter, ['pending', 'in_progress', 'resolved']), function ($query) use ($statusFilter) {
                    return $query->where('status', $statusFilter);
                })->count(),
                'pending' => Complaint::where('status', 'pending')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                'in_progress' => Complaint::where('status', 'in_progress')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
                'resolved' => Complaint::where('status', 'resolved')
                    ->when($startDate && $endDate, function ($query) use ($start, $end) {
                        return $query->whereBetween('created_at', [$start, $end]);
                    })->count(),
            ],
        ];

        // Trend data for charts (last 12 months or custom range)
        $months = [];
        $billAmounts = [];
        $paymentAmounts = [];
        $complaintCounts = [];

        // Generate monthly data
        $month = $start->copy()->startOfMonth();
        while ($month <= $end) {
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            $months[] = $month->format('M Y');

            // Bill trends
            $billQuery = Bill::where('approval_status', 'approved')
                ->whereBetween('created_at', [$monthStart, $monthEnd]);
            if ($statusFilter && in_array($statusFilter, ['pending', 'overdue'])) {
                $billQuery->where('status', $statusFilter);
            }
            $billAmount = (float) $billQuery->sum('amount');
            $billAmounts[] = $billAmount;

            // Payment trends
            $paymentQuery = Payment::where('payment_status', 'successful')
                ->when($startDate && $endDate, function ($query) use ($monthStart, $monthEnd) {
                    return $query->whereBetween('created_at', [$monthStart, $monthEnd]);
                });
            $paymentAmount = (float) $paymentQuery->sum('amount');
            $paymentAmounts[] = $paymentAmount;

            // Complaint trends
            $complaintQuery = Complaint::whereBetween('created_at', [$monthStart, $monthEnd]);
            if ($statusFilter && in_array($statusFilter, ['pending', 'in_progress', 'resolved'])) {
                $complaintQuery->where('status', $statusFilter);
            }
            $complaintCount = (int) $complaintQuery->count();
            $complaintCounts[] = $complaintCount;

            $month->addMonth();
        }

        // Pie chart data
        $tariffByCategory = Category::withCount(['tariffs' => function ($query) use ($startDate, $endDate, $statusFilter) {
            if ($statusFilter && in_array($statusFilter, ['approved', 'pending'])) {
                $query->where('status', $statusFilter);
            }
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }])->get()->pluck('tariffs_count', 'name')->toArray();

        $customersByCategory = Category::withCount(['customers' => function ($query) use ($startDate, $endDate, $statusFilter) {
            if ($statusFilter && in_array($statusFilter, ['approved', 'pending', 'rejected'])) {
                $query->where('status', $statusFilter);
            }
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }])->get()->pluck('customers_count', 'name')->toArray();

        $customersByTariff = Tariff::withCount(['customers' => function ($query) use ($startDate, $endDate, $statusFilter) {
            if ($statusFilter && in_array($statusFilter, ['approved', 'pending', 'rejected'])) {
                $query->where('status', $statusFilter);
            }
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }])->get()->pluck('customers_count', 'name')->toArray();

        $customersByLga = Lga::withCount(['customers' => function ($query) use ($startDate, $endDate, $statusFilter) {
            if ($statusFilter && in_array($statusFilter, ['approved', 'pending', 'rejected'])) {
                $query->where('status', $statusFilter);
            }
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }])->get()->pluck('customers_count', 'name')->toArray();

        return compact(
            'stats', 'months', 'billAmounts', 'paymentAmounts', 'complaintCounts',
            'tariffByCategory', 'customersByCategory', 'customersByTariff', 'customersByLga'
        );
    }
}