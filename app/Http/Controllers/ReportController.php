<?php

namespace App\Http\Controllers;

use App\Models\FeePayment;
use App\Models\Student;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Helper method to centralize structural configurations.
     * Adjust this logic if your system saves years differently (e.g., '2026' vs '2026/2027').
     */
    private function getActiveAcademicContext()
    {
        return [
            'year' => date('Y'), // Returns '2026'. Change to date('Y').'/'.(date('Y')+1) if your models use the string format
            'term' => 'Term 1'   // Keep updated with your live target term
        ];
    }

    public function index()
    {
        $context = $this->getActiveAcademicContext();
        $academicYear = $context['year'];
        $currentTerm  = $context['term'];

        // 1. Calculate Income: Combine core transactions with student fee payment captures
        $directIncome = Transaction::income()->byYear($academicYear)->sum('amount');
        $feeIncome    = FeePayment::where('academic_year', $academicYear)->sum('amount_paid');
        $totalIncome  = $directIncome + $feeIncome;

        // 2. Calculate Expenses
        $totalExpenses = Transaction::expense()->byYear($academicYear)->sum('amount');

        // 3. Count Defaulters: Query active students whose real balance method returns > 0
        $defaultersCount = Student::where('status', 'active')->get()
            ->filter(fn($s) => $s->getOutstandingBalance($academicYear, $currentTerm) > 0)
            ->count();

        // 4. Track Live Collection Metrics
        $todayCollections = FeePayment::whereDate('payment_date', today())->sum('amount_paid');

        return view('reports.index', compact(
            'totalIncome', 'totalExpenses', 'defaultersCount', 'todayCollections'
        ));
    }

    public function feeCollection(Request $request)
    {
        $context      = $this->getActiveAcademicContext();
        $term         = $request->get('term', $context['term']);
        $academicYear = $request->get('academic_year', $context['year']);

        $grades = Student::select('grade')
            ->distinct()
            ->orderBy('grade')
            ->pluck('grade');

        $data = $grades->map(function ($grade) use ($term, $academicYear) {
            $students  = Student::where('grade', $grade)->get();
            $totalFee  = $students->sum(fn($s) => $s->getFeeStructure($academicYear, $term)?->total_fee ?? 0);
            $totalPaid = FeePayment::whereIn('student_id', $students->pluck('id'))
                ->where('term', $term)
                ->where('academic_year', $academicYear)
                ->sum('amount_paid');
            $balance = $totalFee - $totalPaid;
            $pct     = $totalFee > 0 ? round(($totalPaid / $totalFee) * 100) : 0;

            return compact('grade', 'totalFee', 'totalPaid', 'balance', 'pct');
        });

        return view('reports.fee-collection', compact('data', 'term', 'academicYear'));
    }

    public function defaulters(Request $request)
    {
        $context      = $this->getActiveAcademicContext();
        $term         = $request->get('term', $context['term']);
        $academicYear = $request->get('academic_year', $context['year']);
        $grade        = $request->get('grade');

        $query = Student::where('status', 'active');
        if ($grade) $query->where('grade', $grade);

        $students = $query->get()
            ->filter(fn($s) => $s->getOutstandingBalance($academicYear, $term) > 0)
            ->sortByDesc(fn($s) => $s->getOutstandingBalance($academicYear, $term))
            ->values();

        $totalOutstanding = $students->sum(fn($s) => $s->getOutstandingBalance($academicYear, $term));

        return view('reports.defaulters', compact(
            'students', 'totalOutstanding', 'term', 'academicYear', 'grade'
        ));
    }

    public function incomeExpense(Request $request)
    {
        $context      = $this->getActiveAcademicContext();
        $academicYear = $request->get('academic_year', $context['year']);
        $term         = $request->get('term');

        $incomeQuery  = Transaction::income()->byYear($academicYear);
        $expenseQuery = Transaction::expense()->byYear($academicYear);

        if ($term) {
            $incomeQuery->where('term', $term);
            $expenseQuery->where('term', $term);
        }

        $incomeByCategory = (clone $incomeQuery)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')->orderByDesc('total')->get();

        $expenseByCategory = (clone $expenseQuery)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')->orderByDesc('total')->get();

        // Calculate direct transactions
        $directIncome   = $incomeByCategory->sum('total');
        $totalExpenses  = $expenseByCategory->sum('total');

        // Include fee payment entries into category view if no term conflict exists
        $feeIncome = 0;
        if (!$term || $term === $context['term']) {
            $feeIncome = FeePayment::where('academic_year', $academicYear)->sum('amount_paid');
            if ($feeIncome > 0) {
                $incomeByCategory->push((object)[
                    'category' => 'Student Fees',
                    'total'    => $feeIncome
                ]);
            }
        }

        $totalIncome = $directIncome + $feeIncome;
        $netBalance  = $totalIncome - $totalExpenses;

        return view('reports.income-expense', compact(
            'incomeByCategory', 'expenseByCategory',
            'totalIncome', 'totalExpenses', 'netBalance',
            'academicYear', 'term'
        ));
    }

    public function daily(Request $request)
    {
        $date = $request->get('date', today()->toDateString());

        $payments = FeePayment::with('student')
            ->whereDate('payment_date', $date)
            ->orderByDesc('created_at')
            ->get();

        $transactions = Transaction::whereDate('transaction_date', $date)
            ->orderByDesc('created_at')
            ->get();

        $totalPayments = $payments->sum('amount_paid');
        $totalIncome   = $transactions->where('type', 'income')->sum('amount');
        $totalExpenses = $transactions->where('type', 'expense')->sum('amount');
        
        // Sum fee collections + direct transaction revenue, then subtract costs
        $dailyNet = $totalPayments + $totalIncome - $totalExpenses;

        return view('reports.daily', compact(
            'date', 'payments', 'transactions',
            'totalPayments', 'totalIncome', 'totalExpenses', 'dailyNet'
        ));
    }

    public function paymentMethods(Request $request)
    {
        $context      = $this->getActiveAcademicContext();
        $academicYear = $request->get('academic_year', $context['year']);
        $term         = $request->get('term');

        $query = FeePayment::where('academic_year', $academicYear);
        if ($term) $query->where('term', $term);

        $byMethod = $query->selectRaw('payment_method, SUM(amount_paid) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        $grandTotal = $byMethod->sum('total');

        $txnQuery = Transaction::byYear($academicYear);
        if ($term) $txnQuery->where('term', $term);

        $txnByMethod = $txnQuery->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        return view('reports.payment-methods', compact(
            'byMethod', 'grandTotal', 'txnByMethod', 'academicYear', 'term'
        ));
    }
}