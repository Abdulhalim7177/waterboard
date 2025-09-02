<?php

namespace App\Exports\Staff;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        return Customer::with(['lga', 'ward', 'area', 'category', 'tariff'])
            ->when($this->filters['status'] ?? null, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($this->filters['lga'] ?? null, function ($query, $lga_id) {
                return $query->where('lga_id', $lga_id);
            })
            ->when($this->filters['ward'] ?? null, function ($query, $ward_id) {
                return $query->where('ward_id', $ward_id);
            })
            ->when($this->filters['area'] ?? null, function ($query, $area_id) {
                return $query->where('area_id', $area_id);
            })
            ->when($this->filters['category'] ?? null, function ($query, $category_id) {
                return $query->where('category_id', $category_id);
            })
            ->when($this->filters['tariff'] ?? null, function ($query, $tariff_id) {
                return $query->where('tariff_id', $tariff_id);
            })
            ->when($this->filters['search'] ?? null, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('surname', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('billing_id', 'like', "%{$search}%");
                });
            })
            ->get();
    }

    public function headings(): array
    {
        return [
            'Billing ID',
            'First Name',
            'Surname',
            'Middle Name',
            'Email',
            'Phone Number',
            'Alternate Phone Number',
            'LGA',
            'Ward',
            'Area',
            'Street Name',
            'House Number',
            'Landmark',
            'Category',
            'Tariff',
            'Delivery Code',
            'Billing Condition',
            'Water Supply Status',
            'Latitude',
            'Longitude',
            'Altitude',
            'Pipe Path',
            'Polygon Coordinates',
            'Status',
            'Created At',
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->billing_id ?? 'N/A',
            $customer->first_name ?? '',
            $customer->surname ?? '',
            $customer->middle_name ?? '',
            $customer->email ?? '',
            $customer->phone_number ?? '',
            $customer->alternate_phone_number ?? '',
            $customer->lga->name ?? 'N/A',
            $customer->ward->name ?? 'N/A',
            $customer->area->name ?? 'N/A',
            $customer->street_name ?? '',
            $customer->house_number ?? '',
            $customer->landmark ?? '',
            $customer->category->name ?? 'N/A',
            $customer->tariff->name ?? 'N/A',
            $customer->delivery_code ?? '',
            $customer->billing_condition ?? '',
            $customer->water_supply_status ?? '',
            $customer->latitude ?? '',
            $customer->longitude ?? '',
            $customer->altitude ?? '',
            $customer->pipe_path ?? '',
            $customer->polygon_coordinates ?? '',
            $customer->status ?? '',
            $customer->created_at ? $customer->created_at->format('Y-m-d H:i:s') : '',
        ];
    }
}