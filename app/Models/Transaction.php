<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'category', 'description', 'amount',
        'transaction_date', 'reference_number', 'payment_method',
        'status', 'academic_year', 'term', 'notes', 'recorded_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount'           => 'float',
    ];

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    /**
     * Filter by academic year.
     *
     * academic_year is stored as "2025/2026".
     * The index filter dropdown sends the same format.
     * If somehow a plain year like "2025" is passed,
     * we match it via LIKE "2025/%" so records still appear.
     */
    public function scopeByYear($query, $year)
    {
        if (str_contains((string) $year, '/')) {
            return $query->where('academic_year', $year);
        }

        return $query->where('academic_year', 'like', $year . '/%');
    }

    public function scopeByTerm($query, $term)
    {
        return $query->where('term', $term);
    }

    // ── Static helpers ────────────────────────────────────────────────────────

    public static function incomeCategories(): array
    {
        return [
            'Tuition Fees', 'Exam Fees', 'Activity Fees',
            'Transport Fees', 'Boarding Fees', 'Grants',
            'Donations', 'Other Income',
        ];
    }

    public static function expenseCategories(): array
    {
        return [
            'Salaries', 'Utilities', 'Stationery & Supplies',
            'Equipment & Furniture', 'Repairs & Maintenance',
            'Food & Catering', 'Transport', 'Events & Activities',
            'Bank Charges', 'Other Expenses',
        ];
    }
}