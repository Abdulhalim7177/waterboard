<?php
namespace App\Models;
use App\Traits\Auditable;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    use Auditable;

    protected $fillable = [
        'customer_id', 'tariff_id', 'billing_id', 'amount', 'due_date',
        'year_month', 'billing_date', 'status', 'balance', 'approval_status'
    ];

    protected $casts = [
        'due_date' => 'date',
        'billing_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tariff()
    {
        return $this->belongsTo(Tariff::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function updateBalanceAndStatus()
    {
        try {
            $totalPayments = $this->payments()
                ->where('payment_status', 'SUCCESSFUL')
                ->sum('amount');
            $this->balance = max(0, $this->amount - $totalPayments);
            $this->status = $this->balance <= 0 ? 'paid' : ($this->due_date && $this->due_date->isPast() ? 'overdue' : 'pending');
            $this->save();
        } catch (\Exception $e) {
            Log::error('Failed to update bill balance and status', [
                'bill_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function toPdf()
    {
        if ($this->approval_status !== 'approved') {
            throw new \Exception('Cannot generate PDF for unapproved bill');
        }

        return Pdf::view('pdf.bill', ['bill' => $this])
            ->format('A4')
            ->withBrowsershot(function ($browsershot) {
                $browsershot->setOption('dpi', 96)
                            ->setOption('defaultFont', 'DejaVu Sans');
            });
    }
}