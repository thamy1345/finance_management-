<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'receipt_number', 'academic_year', 'term',
        'amount_paid', 'payment_method', 'mpesa_code',
        'bank_reference', 'payment_date', 'notes', 'recorded_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount_paid'  => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
    

    // Auto-generate receipt number before creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->receipt_number)) {
                $year = date('Y');
                $last = static::whereYear('created_at', $year)->max('id') ?? 0;
                $payment->receipt_number = 'RCP-' . $year . '-' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'cash'          => 'Cash',
            'mpesa'         => 'M-Pesa',
            'bank_transfer' => 'Bank Transfer',
            'cheque'        => 'Cheque',
            default         => ucfirst($this->payment_method),
        };
    }
}