<?php
namespace App\Console\Commands;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\Tariff;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateBills extends Command
{
    protected $signature = 'bills:generate';
    protected $description = 'Generate monthly bills for approved customers';

    public function handle()
    {
        $yearMonth = now()->format('Ym');
        $lastBillDate = Bill::where('year_month', $yearMonth)->max('created_at');

        if ($lastBillDate) {
            $this->info('Bills already generated for this month');
            Log::info('Bill generation skipped: Bills already generated for this month', ['year_month' => $yearMonth]);
            return 0;
        }

        DB::beginTransaction();
        try {
            $customers = Customer::where('status', 'approved')->with('tariff')->get();
            foreach ($customers as $customer) {
                $tariff = $customer->tariff;
                $amount = $tariff->billing_type === 'Metered' && $customer->meter_reading
                    ? $tariff->rate * $customer->meter_reading + ($tariff->fixed_charge ?? 0)
                    : ($tariff->fixed_charge ?? $tariff->rate);

                Bill::create([
                    'customer_id' => $customer->id,
                    'tariff_id' => $tariff->id,
                    'billing_id' => $customer->billing_id,
                    'amount' => $amount,
                    'due_date' => now()->endOfMonth(),
                    'year_month' => $yearMonth,
                    'billing_date' => now(),
                    'status' => 'pending',
                    'balance' => $amount,
                    'approval_status' => 'pending',
                ]);
            }

            DB::commit();
            $this->info('Bills generated successfully for ' . $yearMonth);
            Log::info('Bills generated successfully', ['year_month' => $yearMonth]);
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bill generation failed', ['error' => $e->getMessage()]);
            $this->error('Bill generation failed: ' . $e->getMessage());
            return 1;
        }
    }
}