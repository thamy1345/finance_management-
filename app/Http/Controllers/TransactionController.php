<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $currentAcademicYear = date('Y') . '/' . (date('Y') + 1);
        $year = $request->get('year', $currentAcademicYear);

        $query = Transaction::byYear($year);

        if ($request->filled('type'))     $query->where('type', $request->type);
        if ($request->filled('category')) $query->where('category', $request->category);
        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('term'))     $query->where('term', $request->term);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) =>
                $q->where('description', 'like', "%$s%")
                  ->orWhere('reference_number', 'like', "%$s%")
            );
        }

        $transactions = $query->orderByDesc('transaction_date')->paginate(20)->withQueryString();

        $income   = Transaction::income()->byYear($year)->sum('amount');
        $expenses = Transaction::expense()->byYear($year)->sum('amount');
        $balance  = $income - $expenses;

        $incomeCategories  = Transaction::incomeCategories();
        $expenseCategories = Transaction::expenseCategories();

        return view('transactions.index', compact(
            'transactions', 'year', 'income', 'expenses', 'balance',
            'incomeCategories', 'expenseCategories'
        ));
    }

    /**
     * Filtered view showing only income transactions.
     */
    public function income(Request $request)
    {
        $request->merge(['type' => 'income']);
        return $this->index($request);
    }

    /**
     * Filtered view showing only expense transactions.
     */
    public function expenses(Request $request)
    {
        $request->merge(['type' => 'expense']);
        return $this->index($request);
    }

    public function create(Request $request)
    {
        $type              = $request->get('type', 'income');
        $incomeCategories  = Transaction::incomeCategories();
        $expenseCategories = Transaction::expenseCategories();

        return view('transactions.create', compact('type', 'incomeCategories', 'expenseCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'             => 'required|in:income,expense',
            'category'         => 'required|string|max:100',
            'description'      => 'required|string|max:255',
            'amount'           => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
            'payment_method'   => 'required|in:cash,mpesa,bank_transfer,cheque',
            'status'           => 'required|in:completed,pending,cancelled',
            'academic_year'    => 'required|string',
            'term'             => 'nullable|in:Term 1,Term 2,Term 3',
            'notes'            => 'nullable|string',
        ]);

        $validated['recorded_by'] = Auth::id();

        Transaction::create($validated);

        return redirect()->route('transactions.index')
            ->with('success', ucfirst($validated['type']) . ' of KES ' .
                number_format($validated['amount']) . ' recorded.');
    }

    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $incomeCategories  = Transaction::incomeCategories();
        $expenseCategories = Transaction::expenseCategories();

        return view('transactions.edit', compact('transaction', 'incomeCategories', 'expenseCategories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'type'             => 'required|in:income,expense',
            'category'         => 'required|string|max:100',
            'description'      => 'required|string|max:255',
            'amount'           => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
            'payment_method'   => 'required|in:cash,mpesa,bank_transfer,cheque',
            'status'           => 'required|in:completed,pending,cancelled',
            'academic_year'    => 'required|string',
            'term'             => 'nullable|in:Term 1,Term 2,Term 3',
            'notes'            => 'nullable|string',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted.');
    }
}