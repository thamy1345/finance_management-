<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $term = $request->get('term', 'Term 1');

        // =========================
        // STUDENTS
        // =========================
        $studentsByGrade = Student::active()
            ->select('grade', DB::raw('count(*) as total'))
            ->groupBy('grade')
            ->orderBy('grade')
            ->pluck('total', 'grade');

        $totalStudents = Student::active()->count();

        // =========================
        // FEES
        // =========================
        $totalFeesCollected = FeePayment::where('academic_year', $year)
            ->where('term', $term)
            ->sum('amount_paid');

        $expectedFees = 0;
        $structures   = FeeStructure::where('academic_year', $year)
            ->where('term', $term)
            ->get();

        foreach ($structures as $s) {
            $count         = Student::active()->byGrade($s->grade)->count();
            $expectedFees += $s->total_fee * $count;
        }

        $totalOutstanding = $expectedFees - $totalFeesCollected;

        // =========================
        // TRANSACTIONS
        // =========================
        $income     = Transaction::income()->byYear($year)->sum('amount');
        $expenses   = Transaction::expense()->byYear($year)->sum('amount');
        $netBalance = ($totalFeesCollected + $income) - $expenses;

        // =========================
        // MONTHLY CASHFLOW
        // =========================
        $monthlyFees = FeePayment::where('academic_year', $year)
            ->select(DB::raw("DATE_FORMAT(payment_date, '%m') as month"), DB::raw('SUM(amount_paid) as total'))
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyIncome = Transaction::income()
            ->byYear($year)
            ->select(DB::raw("DATE_FORMAT(transaction_date, '%m') as month"), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyExpenses = Transaction::expense()
            ->byYear($year)
            ->select(DB::raw("DATE_FORMAT(transaction_date, '%m') as month"), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->pluck('total', 'month');

        $cashflow = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthKey     = str_pad($m, 2, '0', STR_PAD_LEFT);
            $cashflow[$m] = [
                'income'   => ($monthlyFees[$monthKey] ?? 0) + ($monthlyIncome[$monthKey] ?? 0),
                'expenses' => $monthlyExpenses[$monthKey] ?? 0,
            ];
        }

        // =========================
        // GRADE COLLECTION
        // =========================
        $gradeCollection = [];
        foreach (range(1, 9) as $grade) {
            $structure    = FeeStructure::where('grade', $grade)
                ->where('academic_year', $year)
                ->where('term', $term)
                ->first();

            $studentCount     = Student::active()->byGrade($grade)->count();
            $expectedForGrade = $structure ? $structure->total_fee * $studentCount : 0;

            $collectedForGrade = FeePayment::whereHas('student', fn($q) =>
                $q->where('grade', $grade)
            )
                ->where('academic_year', $year)
                ->where('term', $term)
                ->sum('amount_paid');

            $gradeCollection[$grade] = [
                'students'  => $studentCount,
                'expected'  => $expectedForGrade,
                'collected' => $collectedForGrade,
                'balance'   => $expectedForGrade - $collectedForGrade,
                'pct'       => $expectedForGrade > 0
                    ? round(($collectedForGrade / $expectedForGrade) * 100)
                    : 0,
            ];
        }

        // =========================
        // RECENT DATA
        // =========================
        $recentPayments     = FeePayment::with('student')->latest()->take(8)->get();
        $recentTransactions = Transaction::latest()->take(8)->get();

        $expenseBreakdown = Transaction::expense()
            ->byYear($year)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        // =========================
        // TOP DEFAULTERS
        // =========================
        $topDefaulters = Student::active()
            ->withSum(['feePayments as paid' => function ($q) use ($year, $term) {
                $q->where('academic_year', $year)->where('term', $term);
            }], 'amount_paid')
            ->get()
            ->map(function ($student) use ($year, $term) {
                $structure        = $student->getFeeStructure($year, $term);
                $expected         = $structure ? $structure->total_fee : 0;
                $student->balance_due = max($expected - ($student->paid ?? 0), 0);
                return $student;
            })
            ->sortByDesc('balance_due')
            ->take(10)
            ->values();

        // =========================
        // FEE STATUS DISTRIBUTION (DOUGHNUT)
        // =========================
        $paid    = 0;
        $partial = 0;
        $unpaid  = 0;

        $students = Student::active()->get();

        foreach ($students as $student) {
            $structure  = $student->getFeeStructure($year, $term);
            $expected   = $structure ? $structure->total_fee : 0;

            // Skip students with no fee structure for this term
            if ($expected <= 0) continue;

            $paidAmount = FeePayment::where('student_id', $student->id)
                ->where('academic_year', $year)
                ->where('term', $term)
                ->sum('amount_paid');

            if ($paidAmount <= 0) {
                $unpaid++;
            } elseif ($paidAmount < $expected) {
                $partial++;
            } else {
                $paid++;
            }
        }

        // Percentage conversion — guard against zero-student edge case
        $totalStatus = max($paid + $partial + $unpaid, 1);
        $paidPct     = round(($paid    / $totalStatus) * 100);
        $partialPct  = round(($partial / $totalStatus) * 100);
        $unpaidPct   = round(($unpaid  / $totalStatus) * 100);

        // =========================
        // RETURN VIEW
        // =========================
        return view('dashboard.index', compact(
            'year', 'term',
            'studentsByGrade', 'totalStudents',
            'totalFeesCollected', 'totalOutstanding',
            'income', 'expenses', 'netBalance',
            'cashflow', 'gradeCollection',
            'recentPayments', 'recentTransactions',
            'expenseBreakdown', 'topDefaulters',
            'paid', 'partial', 'unpaid',
            'paidPct', 'partialPct', 'unpaidPct',
        ));
    }
}