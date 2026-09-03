<?php

namespace App\Http\Controllers;

use App\Models\FeePayment;
use App\Models\Student;
use App\Models\FeeStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeePaymentController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $term = $request->get('term', 'Term 1');

        $query = FeePayment::with('student')
            ->where('academic_year', $year)
            ->where('term', $term);

        if ($request->filled('grade')) {
            $query->whereHas('student', fn($q) =>
                $q->where('grade', $request->grade)
            );
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('receipt_number', 'like', "%$s%")
                    ->orWhereHas('student', fn($sq) =>
                        $sq->where('first_name', 'like', "%$s%")
                           ->orWhere('last_name', 'like', "%$s%")
                           ->orWhere('admission_number', 'like', "%$s%")
                    );
            });
        }

        $payments = $query->orderByDesc('payment_date')
            ->paginate(20)
            ->withQueryString();

        $totalCollected = FeePayment::where('academic_year', $year)
            ->where('term', $term)
            ->sum('amount_paid');

        return view('fee-payments.index', compact('payments', 'year', 'term', 'totalCollected'));
    }

    public function create(Request $request)
    {
        $student = null;

        if ($request->filled('student_id')) {
            $student = Student::findOrFail($request->student_id);
        }

        $students = Student::active()
            ->orderBy('grade')
            ->orderBy('last_name')
            ->get();

        return view('fee-payments.create', compact('students', 'student'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'     => 'required|exists:students,id',
            'academic_year'  => 'required|string',
            'term'           => 'required|in:Term 1,Term 2,Term 3',
            'amount_paid'    => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,mpesa,bank_transfer,cheque',
            'mpesa_code'     => 'nullable|string|max:50',
            'bank_reference' => 'nullable|string|max:100',
            'payment_date'   => 'required|date',
            'notes'          => 'nullable|string',
        ]);

        $validated['recorded_by'] = Auth::id();

        $payment = FeePayment::create($validated);

        return redirect()
            ->route('students.show', $validated['student_id'])
            ->with('success', "Payment of KES " . number_format($validated['amount_paid']) .
                " recorded. Receipt: {$payment->receipt_number}");
    }

    public function show(FeePayment $feePayment)
    {
        $feePayment->load('student', 'recorder');

        return view('fee-payments.receipt', compact('feePayment'));
    }

    public function edit(FeePayment $feePayment)
    {
        $students = Student::active()
            ->orderBy('grade')
            ->orderBy('last_name')
            ->get();

        return view('fee-payments.edit', compact('feePayment', 'students'));
    }

    public function update(Request $request, FeePayment $feePayment)
    {
        $validated = $request->validate([
            'student_id'     => 'required|exists:students,id',
            'academic_year'  => 'required|string',
            'term'           => 'required|in:Term 1,Term 2,Term 3',
            'amount_paid'    => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,mpesa,bank_transfer,cheque',
            'mpesa_code'     => 'nullable|string|max:50',
            'bank_reference' => 'nullable|string|max:100',
            'payment_date'   => 'required|date',
            'notes'          => 'nullable|string',
        ]);

        $feePayment->update($validated);

        return redirect()
            ->route('fee-payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(FeePayment $feePayment)
    {
        $studentId = $feePayment->student_id;

        $feePayment->delete();

        return redirect()
            ->route('students.show', $studentId)
            ->with('success', 'Payment record deleted.');
    }

    public function outstanding(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $term = $request->get('term', 'Term 1');

        $students = Student::active()
            ->with(['feePayments' => fn($q) =>
                $q->where('academic_year', $year)
                  ->where('term', $term)
            ])
            ->orderBy('grade')
            ->orderBy('last_name')
            ->get();

        $report = $students->map(function ($student) use ($year, $term) {
            $structure = $student->getFeeStructure($year, $term);
            $expected  = $structure ? $structure->total_fee : 0;
            $paid      = $student->getPaidAmount($year, $term);
            $balance   = $expected - $paid;

            return [
                'student'  => $student,
                'expected' => $expected,
                'paid'     => $paid,
                'balance'  => $balance,
                'status'   => $balance <= 0 ? 'cleared' : ($paid > 0 ? 'partial' : 'unpaid'),
            ];
        });

        $gradeFilter = $request->get('grade');
        if ($gradeFilter) {
            $report = $report->filter(fn($r) => $r['student']->grade == $gradeFilter);
        }

        $statusFilter = $request->get('payment_status');
        if ($statusFilter) {
            $report = $report->filter(fn($r) => $r['status'] === $statusFilter);
        }

        return view('fee-payments.outstanding', compact('report', 'year', 'term'));
    }
}