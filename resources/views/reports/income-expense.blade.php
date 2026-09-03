@extends('layouts.admin')
@section('title', 'Income vs Expense')

@section('content')
<div class="page-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
    <a href="{{ route('reports.index') }}" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:#1a1d27;border:1px solid #2a2d3e;color:#6b7280;text-decoration:none;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h1 style="font-size:1.35rem;font-weight:700;color:#e8eaf0;margin:0;">Income vs Expense</h1>
        <p style="font-size:.8rem;color:#6b7280;margin:0;">{{ $academicYear }}{{ $term ? ' · '.$term : '' }}</p>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-4">
                <label class="form-label mb-1" style="font-size:.75rem;color:#9ca3af;text-transform:uppercase;">Academic Year</label>
                <input type="text" name="academic_year" class="form-control" value="{{ $academicYear }}" placeholder="e.g. 2026/2027">
            </div>
            <div class="col-sm-3">
                <label class="form-label mb-1" style="font-size:.75rem;color:#9ca3af;text-transform:uppercase;">Term</label>
                <select name="term" class="form-select">
                    <option value="">All Terms</option>
                    @foreach(['Term 1','Term 2','Term 3'] as $t)
                        <option value="{{ $t }}" {{ $term === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center py-3">
            <div style="font-size:.72rem;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;">Total Income</div>
            <div style="font-size:1.6rem;font-weight:700;color:#22c55e;">KSh {{ number_format($totalIncome) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center py-3">
            <div style="font-size:.72rem;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;">Total Expenses</div>
            <div style="font-size:1.6rem;font-weight:700;color:#ef4444;">KSh {{ number_format($totalExpenses) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center py-3">
            <div style="font-size:.72rem;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;">Net Balance</div>
            <div style="font-size:1.6rem;font-weight:700;color:{{ $netBalance >= 0 ? '#22c55e' : '#ef4444' }};">
                {{ $netBalance < 0 ? '-' : '' }}KSh {{ number_format(abs($netBalance)) }}
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Income by Category --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header" style="display:flex;align-items:center;gap:8px;">
                <i class="bi bi-arrow-down-circle text-success"></i>
                <span class="card-title">Income by Category</span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th class="text-end">Amount</th>
                            <th class="text-end">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($incomeByCategory as $row)
                        <tr>
                            <td>{{ $row->category ?? 'Uncategorised' }}</td>
                            <td class="text-end text-success fw-bold">KSh {{ number_format($row->total) }}</td>
                            <td class="text-end" style="color:#6b7280;font-size:.82rem;">
                                {{ $totalIncome > 0 ? round(($row->total / $totalIncome) * 100) : 0 }}%
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No income records</td></tr>
                        @endforelse
                    </tbody>
                    @if($incomeByCategory->count())
                    <tfoot>
                        <tr style="border-top:2px solid #2a2d3e;">
                            <td class="fw-bold">Total</td>
                            <td class="text-end fw-bold text-success">KSh {{ number_format($totalIncome) }}</td>
                            <td class="text-end" style="color:#6b7280;">100%</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- Expense by Category --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header" style="display:flex;align-items:center;gap:8px;">
                <i class="bi bi-arrow-up-circle text-danger"></i>
                <span class="card-title">Expenses by Category</span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th class="text-end">Amount</th>
                            <th class="text-end">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenseByCategory as $row)
                        <tr>
                            <td>{{ $row->category ?? 'Uncategorised' }}</td>
                            <td class="text-end text-danger fw-bold">KSh {{ number_format($row->total) }}</td>
                            <td class="text-end" style="color:#6b7280;font-size:.82rem;">
                                {{ $totalExpenses > 0 ? round(($row->total / $totalExpenses) * 100) : 0 }}%
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No expense records</td></tr>
                        @endforelse
                    </tbody>
                    @if($expenseByCategory->count())
                    <tfoot>
                        <tr style="border-top:2px solid #2a2d3e;">
                            <td class="fw-bold">Total</td>
                            <td class="text-end fw-bold text-danger">KSh {{ number_format($totalExpenses) }}</td>
                            <td class="text-end" style="color:#6b7280;">100%</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection