<?php 

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class CustomersExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $startDate = $this->filters['start_date'] ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::parse($this->filters['end_date'] ?? Carbon::now()->endOfMonth()->format('Y-m-d'))->endOfDay()->format('Y-m-d H:i:s');

        $query = Customer::with(['bills', 'category', 'tariff', 'lga', 'ward', 'area'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        $query->join('bills', function ($join) use ($startDate, $endDate) {
            $join->on('customers.id', '=', 'bills.customer_id')
                 ->where('bills.approval_status', 'approved')
                 ->whereIn('bills.status', ['pending', 'overdue'])
                 ->whereBetween('bills.created_at', [$startDate, $endDate]);
        })
        ->select('customers.*')
        ->groupBy('customers.id');

        if (isset($this->filters['payment_status']) && in_array($this->filters['payment_status'], ['paid', 'unpaid'])) {
            if ($this->filters['payment_status'] === 'paid') {
                $query->havingRaw('SUM(bills.balance) = 0');
            } else {
                $query->havingRaw('SUM(bills.balance) > 0');
            }
        }

        if (isset($this->filters['category_id']) && $this->filters['category_id']) {
            $query->where('category_id', $this->filters['category_id']);
        }

        if (isset($this->filters['tariff_id']) && $this->filters['tariff_id']) {
            $query->where('tariff_id', $this->filters['tariff_id']);
        }

        if (isset($this->filters['search']) && $this->filters['search']) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(first_name, ' ', surname) LIKE ?", ["%$search%"])
                  ->orWhere('billing_id', 'LIKE', "%$search%");
            });
        }

        $customers = $query->get();

        return $customers->map(function ($customer) use ($startDate, $endDate) {
            $unpaidBills = $customer->bills()
                ->whereIn('status', ['pending', 'overdue'])
                ->where('approval_status', 'approved')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('balance');
            $totalBilled = $customer->bills()
                ->whereIn('status', ['pending', 'overdue'])
                ->where('approval_status', 'approved')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');
            $totalUnpaid = $customer->bills()
                ->whereIn('status', ['pending', 'overdue'])
                ->where('approval_status', 'approved')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('balance');
            return [
                'customer_id' => $customer->id,
                'name' => $customer->first_name . ' ' . $customer->surname,
                'billing_id' => $customer->billing_id ?? 'N/A',
                'payment_status' => $unpaidBills > 0 ? 'unpaid' : 'paid',
                'category' => $customer->category->name ?? 'N/A',
                'tariff' => $customer->tariff->name ?? 'N/A',
                'lga' => $customer->lga->name ?? 'N/A',
                'ward' => $customer->ward->name ?? 'N/A',
                'area' => $customer->area->name ?? 'N/A',
                'latitude' => $customer->latitude,
                'longitude' => $customer->longitude,
                'polygon_coordinates' => $customer->polygon_coordinates ?? '[]',
                'total_billed' => number_format($totalBilled, 2, '.', ''),
                'total_unpaid' => number_format($totalUnpaid, 2, '.', '')
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Customer ID', 'Name', 'Billing ID', 'Payment Status', 'Category', 'Tariff', 
            'LGA', 'Ward', 'Area', 'Latitude', 'Longitude', 'Polygon Coordinates',
            'Total Bill Amount (NGN)', 'Total Unpaid Balance (NGN)'
        ];
    }
}