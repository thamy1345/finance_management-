<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->filled('grade'))  $query->where('grade', $request->grade);
        if ($request->filled('status')) $query->where('status', $request->status);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name',        'like', "%$s%")
                  ->orWhere('last_name',        'like', "%$s%")
                  ->orWhere('admission_number', 'like', "%$s%")
                  ->orWhere('parent_name',      'like', "%$s%");
            });
        }

        $students = $query->orderBy('grade')->orderBy('last_name')
                          ->paginate(20)->withQueryString();

        $year = $request->get('year', date('Y'));
        $term = $request->get('term', 'Term 1');

        return view('students.index', compact('students', 'year', 'term'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'            => 'required|string|max:100',
            'last_name'             => 'required|string|max:100',
            'admission_number'      => 'required|string|max:50|unique:students,admission_number',
            'date_of_birth'         => 'nullable|date|before:today',
            'gender'                => ['required', Rule::in(['Male', 'Female', 'male', 'female'])],
            'status'                => ['required', Rule::in(['active', 'inactive', 'graduated'])],
            'grade'                 => ['required', Rule::in(['1','2','3','4','5','6','7','8','9'])],
            'stream'                => 'nullable|string|max:20',
            'academic_year'         => 'required|string|max:10',
            'enrollment_date'       => 'nullable|date',
            'guardian_name'         => 'required|string|max:150',
            'guardian_phone'        => 'required|string|max:25',
            'guardian_email'        => 'nullable|email|max:150',
            'guardian_relationship' => 'nullable|string|max:50',
            'address'               => 'nullable|string|max:255',
            'amount_paid'           => 'nullable|numeric|min:0',
            'payment_method'        => 'nullable|in:cash,mpesa,bank_transfer,cheque',
            'payment_term'          => 'nullable|in:Term 1,Term 2,Term 3',
            'payment_date'          => 'nullable|date',
            'receipt_number'        => 'nullable|string|max:100',
        ]);

        $noteParts = [];
        if ($request->filled('guardian_relationship')) $noteParts[] = 'Relationship: ' . $request->guardian_relationship;
        if ($request->filled('address'))               $noteParts[] = 'Address: '      . $request->address;

        $student = Student::create([
            'admission_number' => $request->admission_number,
            'first_name'       => $request->first_name,
            'last_name'        => $request->last_name,
            'date_of_birth'    => $request->date_of_birth,
            'gender'           => strtolower($request->gender),
            'status'           => $request->status,
            'grade'            => $request->grade,
            'stream'           => $request->stream,
            'parent_name'      => $request->guardian_name,
            'parent_phone'     => $request->guardian_phone,
            'parent_email'     => $request->guardian_email,
            'notes'            => implode(' | ', $noteParts) ?: null,
        ]);

        if ($request->filled('amount_paid') && (float) $request->amount_paid > 0) {
            $methodMap = [
                'Cash'          => 'cash',
                'M-Pesa'        => 'mpesa',
                'Bank Transfer' => 'bank_transfer',
                'Cheque'        => 'cheque',
            ];
            $method = $methodMap[$request->payment_method] ?? (strtolower($request->payment_method) ?? 'cash');

            FeePayment::create([
                'student_id'     => $student->id,
                'academic_year'  => $request->academic_year ?? date('Y'),
                'term'           => $request->payment_term  ?? 'Term 1',
                'amount_paid'    => $request->amount_paid,
                'payment_method' => $method,
                'payment_date'   => $request->payment_date ?? today()->toDateString(),
                'mpesa_code'     => $method === 'mpesa' ? strtoupper($request->receipt_number ?? '') : null,
                'bank_reference' => in_array($method, ['bank_transfer','cheque']) ? $request->receipt_number : null,
                'notes'          => 'Recorded at enrolment',
                'recorded_by'    => auth()->id(),
            ]);
        }

        $msg = "Student {$student->first_name} {$student->last_name} enrolled successfully.";
        if ($request->filled('amount_paid') && (float) $request->amount_paid > 0) {
            $msg .= ' Payment of KES ' . number_format($request->amount_paid) . ' recorded.';
        }

        return redirect()->route('students.show', $student)->with('success', $msg);
    }

    public function show(Student $student, Request $request)
    {
        $year = $request->get('year', date('Y'));
        $term = $request->get('term', 'Term 1');

        $payments = $student->feePayments()->orderByDesc('payment_date')->get();

        $paymentsByTermRaw = [];
        foreach ($payments as $payment) {
            $key = ($payment->academic_year ?? date('Y')) . '|' . ($payment->term ?? 'Term 1');
            if (!isset($paymentsByTermRaw[$key])) {
                $paymentsByTermRaw[$key] = ['payments' => collect(), 'structure' => null];
            }
            $paymentsByTermRaw[$key]['payments']->push($payment);
        }

        foreach ($paymentsByTermRaw as $key => &$data) {
            [$y, $t] = explode('|', $key);
            $data['structure'] = FeeStructure::where('grade', $student->grade)
                ->where('academic_year', $y)->where('term', $t)->first();
        }
        unset($data);

        $paymentsByTerm      = collect($paymentsByTermRaw);
        $currentFeeStructure = FeeStructure::where('grade', $student->grade)
            ->where('academic_year', $year)->where('term', $term)->first();

        $currentTermPaid = $student->feePayments()
            ->where('academic_year', $year)->where('term', $term)->sum('amount_paid');

        $totalExpected = $currentFeeStructure ? $currentFeeStructure->total_fee : 0;
        $balance       = $totalExpected - $currentTermPaid;

        return view('students.show', compact(
            'student', 'payments', 'paymentsByTerm',
            'currentFeeStructure', 'currentTermPaid',
            'totalExpected', 'balance', 'year', 'term'
        ));
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'admission_number'      => ['required', 'string', 'max:50', Rule::unique('students')->ignore($student->id)],
            'first_name'            => 'required|string|max:100',
            'last_name'             => 'required|string|max:100',
            'grade'                 => ['required', Rule::in(['1','2','3','4','5','6','7','8','9'])],
            'stream'                => 'nullable|string|max:20',
            'guardian_name'         => 'required|string|max:150',
            'guardian_phone'        => 'required|string|max:25',
            'guardian_email'        => 'nullable|email|max:150',
            'guardian_relationship' => 'nullable|string|max:50',
            'address'               => 'nullable|string|max:255',
            'date_of_birth'         => 'nullable|date',
            'gender'                => ['required', Rule::in(['Male', 'Female', 'male', 'female'])], // ← fixed
            'status'                => ['required', Rule::in(['active', 'inactive', 'transferred', 'graduated'])],
            'notes'                 => 'nullable|string',
        ]);

        $student->update([
            'admission_number' => $request->admission_number,
            'first_name'       => $request->first_name,
            'last_name'        => $request->last_name,
            'grade'            => $request->grade,
            'stream'           => $request->stream,
            'parent_name'      => $request->guardian_name,
            'parent_phone'     => $request->guardian_phone,
            'parent_email'     => $request->guardian_email,
            'date_of_birth'    => $request->date_of_birth,
            'gender'           => strtolower($request->gender),
            'status'           => $request->status,
            'notes'            => $request->notes,
        ]);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student record updated.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')
            ->with('success', 'Student removed from records.');
    }

    public function ledger(Student $student, Request $request)
    {
        $year = $request->get('year', date('Y'));

        $payments = $student->feePayments()
            ->where('academic_year', $year)
            ->orderBy('payment_date')
            ->get();

        $structures = FeeStructure::where('grade', $student->grade)
            ->where('academic_year', $year)
            ->get()->keyBy('term');

        $ledger = [];
        foreach (['Term 1', 'Term 2', 'Term 3'] as $t) {
            $structure  = $structures[$t] ?? null;
            $paid       = $payments->where('term', $t)->sum('amount_paid');
            $ledger[$t] = [
                'expected' => $structure ? $structure->total_fee : 0,
                'paid'     => $paid,
                'balance'  => ($structure ? $structure->total_fee : 0) - $paid,
                'payments' => $payments->where('term', $t),
            ];
        }

        return view('students.ledger', compact('student', 'ledger', 'year'));
    }
}