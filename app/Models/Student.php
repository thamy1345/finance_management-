<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_number', 'first_name', 'last_name', 'grade', 'stream',
        'parent_name', 'parent_phone', 'parent_email',
        'date_of_birth', 'gender', 'status', 'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // ── Accessors ─────────────────────────────────────────────────────────────

    /** $student->name — joins first + last */
    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /** $student->full_name — alias */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getGradeLabelAttribute(): string
    {
        return "Grade {$this->grade}";
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function feePayments()
    {
        return $this->hasMany(FeePayment::class);
    }

    // ── Fee helpers ───────────────────────────────────────────────────────────

    /**
     * Total paid for a given year and optional term.
     * Both totalPaid() and getPaidAmount() work — controllers use both.
     */
    public function totalPaid(string $year = null, string $term = null): float
    {
        $year  = $year ?? date('Y');
        $query = $this->feePayments()->where('academic_year', $year);
        if ($term) {
            $query->where('term', $term);
        }
        return (float) $query->sum('amount_paid');
    }

    public function getPaidAmount(string $year, string $term = null): float
    {
        return $this->totalPaid($year, $term);
    }

    /**
     * Fetch FeeStructure for this student's grade/year/term.
     * Named getFeeStructure() NOT feeStructure() to prevent Eloquent __get() collision.
     */
    public function getFeeStructure(string $year, string $term): ?FeeStructure
    {
        return FeeStructure::where('grade', $this->grade)
            ->where('academic_year', $year)
            ->where('term', $term)
            ->first();
    }

    /**
     * Outstanding balance for a specific year/term.
     * Used by ReportController and DashboardController.
     */
    public function getOutstandingBalance(string $year, string $term): float
    {
        $structure = $this->getFeeStructure($year, $term);
        if (! $structure) return 0.0;
        return max(0.0, $structure->total_fee - $this->totalPaid($year, $term));
    }

    /**
     * Total balance due across all terms for a year (used on student index list).
     */
    public function balanceDue(string $year = null): float
    {
        $year = $year ?? date('Y');
        $structures = FeeStructure::where('grade', $this->grade)
            ->where('academic_year', $year)
            ->get();
        $expected = $structures->sum('total_fee');
        $paid     = $this->totalPaid($year);
        return max(0.0, (float) ($expected - $paid));
    }

    /**
     * Fee status for the current year: paid | partial | unpaid
     */
    public function feeStatus(string $year = null): string
    {
        $year    = $year ?? date('Y');
        $balance = $this->balanceDue($year);
        $paid    = $this->totalPaid($year);
        if ($balance <= 0)  return 'paid';
        if ($paid > 0)      return 'partial';
        return 'unpaid';
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByGrade($query, $grade)
    {
        return $query->where('grade', $grade);
    }
}