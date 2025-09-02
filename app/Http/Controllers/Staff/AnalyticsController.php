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
}