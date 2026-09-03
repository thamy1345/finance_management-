@extends('layouts.admin')
@section('title', 'Daily Report')

@section('content')
<div class="page-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
    <a href="{{ route('reports.index') }}" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:#1a1d27;border:1px solid #2a2d3e;color:#6b7280;text-decoration:none;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h1 style="font-size:1.35rem;font-weight:700;color:#e8eaf0;margin:0;">Daily Report</h1>
        <p style="font-size:.8rem;color:#6b7280;margin:0;">{{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</p>
    </div>
</div>

{{-- Date picker --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="d-flex gap-2 align-items-end">
            <div>
                <label class="form-label mb-1" style="font-size:.75rem;color:#9ca3af;text-transform:uppercase;">Date</label>
                <input type="date" name="date" class="form-control" value="{{ $date }}">
            </div>
            <button class="btn btn-primary">View</button>
            <a href="?date={{ today()->toDateString() }}" class="btn btn-outline-secondary">Today</a>
        </form>
    </div>
</div>

{{-- Summary --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center py-3">
            <div style="font-size:.72rem;color:#6b7280;text-transform:uppercase;">Fee Payments</div>
            <div style="font-size:1.4rem;font-weight:700;color:#22c55e;">KSh {{ number_format($totalPayments) }}</div>
            <div style="font-size:.75rem;color:#6b7280;">{{ $payments->count() }} transactions</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center py-3">
            <div style="font-size:.72rem;color:#6b7280;text-transform:uppercase;">Other Income</div>
            <div style="font-size:1.4rem;font-weight:700;color:#3b82f6;">KSh {{ number_format($totalIncome) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center py-3">
            <div style="font-size:.72rem;color:#6b7280;text-transform:uppercase;">Expenses</div>
            <div style="font-size:1.4rem;font-weight:700;color:#ef4444;">KSh {{ number_format($totalExpenses) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center py-3">
            <div style="font-size:.72rem;color:#6b7280;text-transform:uppercase;">Net</div>
            <div style="font-size:1.4rem;font-weight:700;color:{{ $dailyNet >= 0 ? '#22c55e' : '#ef4444' }};">
                KSh {{ number_format(abs($dailyNet)) }}
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Fee Payments --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><span class="card-title">Fee Payments</span></div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Term</th>
                            <th>Method</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $p)
                        <tr>
                            <td>
                                <div style="font-weight:500;">{{ $p->student->name ?? '—' }}</div>
                                <div style="font-size:.75rem;color:#6b7280;">{{ $p->receipt_number }}</div>
                            </td>
                            <td style="color:#9ca3af;">{{ $p->term }}</td>
                            <td><span class="badge" style="background:#1e2235;color:#9ca3af;">{{ strtoupper($p->payment_method) }}</span></td>
                            <td class="text-end fw-bold text-success">KSh {{ number_format($p->amount_paid) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No fee payments today</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Transactions --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><span class="card-title">Other Transactions</span></div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Type</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $t)
                        <tr>
                            <td>
                                <div style="font-weight:500;font-size:.85rem;">{{ $t->description }}</div>
                                <div style="font-size:.72rem;color:#6b7280;">{{ $t->category }}</div>
                            </td>
                            <td>
                                <span class="badge" style="background:{{ $t->type === 'income' ? 'rgba(34,197,94,.15)' : 'rgba(239,68,68,.15)' }};color:{{ $t->type === 'income' ? '#22c55e' : '#ef4444' }};">
                                    {{ ucfirst($t->type) }}
                                </span>
                            </td>
                            <td class="text-end fw-bold" style="color:{{ $t->type === 'income' ? '#22c55e' : '#ef4444' }};">
                                KSh {{ number_format($t->amount) }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No transactions today</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection