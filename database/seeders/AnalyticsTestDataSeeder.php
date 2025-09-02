<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\Complaint;
use Carbon\Carbon;

class AnalyticsTestDataSeeder extends Seeder
{
    public function run()
    {
        // Seed Bills
        for ($i = 0; $i < 12; $i++) {
            Bill::create([
                'customer_id' => 1, // Adjust based on your customer IDs
                'amount' => rand(1000, 5000),
                'status' => $i % 2 == 0 ? 'pending' : 'overdue',
                'approval_status' => 'approved',
                'created_at' => Carbon::now()->subMonths($i)->startOfMonth(),
                'updated_at' => Carbon::now()->subMonths($i)->startOfMonth(),
            ]);
        }

        // Seed Payments
        for ($i = 0; $i < 12; $i++) {
            Payment::create([
                'customer_id' => 1,
                'amount' => rand(500, 3000),
                'payment_status' => 'successful',
                'created_at' => Carbon::now()->subMonths($i)->startOfMonth(),
                'updated_at' => Carbon::now()->subMonths($i)->startOfMonth(),
            ]);
        }

        // Seed Complaints
        $statuses = ['pending', 'in_progress', 'resolved'];
        for ($i = 0; $i < 12; $i++) {
            Complaint::create([
                'customer_id' => 1,
                'status' => $statuses[$i % 3],
                'created_at' => Carbon::now()->subMonths($i)->startOfMonth(),
                'updated_at' => Carbon::now()->subMonths($i)->startOfMonth(),
            ]);
        }
    }
}