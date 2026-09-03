<?php

namespace App\Http\Controllers;

use App\Models\FeeStructure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeeStructureController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $structures = FeeStructure::where('academic_year', $year)
            ->orderBy('grade')->orderBy('term')
            ->get()
            ->groupBy('grade');

        return view('fee_structures.index', compact('structures', 'year'));
    }

    public function create()
    {
        return view('fee_structures.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade'         => ['required', Rule::in(['1','2','3','4','5','6','7','8','9'])],
            'academic_year' => 'required|string',
            'term'          => 'required|in:Term 1,Term 2,Term 3',
            'tuition_fee'   => 'required|numeric|min:0',
            'activity_fee'  => 'nullable|numeric|min:0',
            'exam_fee'      => 'nullable|numeric|min:0',
            'boarding_fee'  => 'nullable|numeric|min:0',
            'transport_fee' => 'nullable|numeric|min:0',
            'other_fee'     => 'nullable|numeric|min:0',
        ]);

        FeeStructure::updateOrCreate(
            ['grade' => $validated['grade'], 'academic_year' => $validated['academic_year'], 'term' => $validated['term']],
            $validated
        );

        return redirect()->route('fee-structures.index')
            ->with('success', "Fee structure for Grade {$validated['grade']} saved.");
    }

    public function edit(FeeStructure $feeStructure)
    {
        return view('fee_structures.edit', compact('feeStructure'));
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $validated = $request->validate([
            'tuition_fee'   => 'required|numeric|min:0',
            'activity_fee'  => 'nullable|numeric|min:0',
            'exam_fee'      => 'nullable|numeric|min:0',
            'boarding_fee'  => 'nullable|numeric|min:0',
            'transport_fee' => 'nullable|numeric|min:0',
            'other_fee'     => 'nullable|numeric|min:0',
        ]);

        $feeStructure->update($validated);
        return redirect()->route('fee-structures.index')
            ->with('success', 'Fee structure updated.');
    }

    public function bulkCreate(Request $request)
    {
        // Copy fee structure from one year to another for all grades
        $fromYear = $request->from_year;
        $toYear   = $request->to_year;

        $structures = FeeStructure::where('academic_year', $fromYear)->get();
        foreach ($structures as $s) {
            FeeStructure::updateOrCreate(
                ['grade' => $s->grade, 'academic_year' => $toYear, 'term' => $s->term],
                ['tuition_fee' => $s->tuition_fee, 'activity_fee' => $s->activity_fee,
                 'exam_fee' => $s->exam_fee, 'boarding_fee' => $s->boarding_fee,
                 'transport_fee' => $s->transport_fee, 'other_fee' => $s->other_fee]
            );
        }

        return redirect()->route('fee-structures.index', ['year' => $toYear])
            ->with('success', "Fee structures copied from $fromYear to $toYear.");
    }
}