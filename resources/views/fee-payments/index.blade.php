{{-- resources/views/payments/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Fee Payments')
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&family=Instrument+Sans:ital,wght@0,400;0,500;0,600;1,400&display=swap');

:root {
    --void:     #070810;
    --deep:     #0b0d18;
    --surface:  #0f1120;
    --raised:   #141728;
    --rim:      #1e2235;
    --line:     rgba(255,255,255,0.06);
    --muted:    #4a5068;
    --dim:      #6b7390;
    --body:     #c8cad8;
    --bright:   #eceef8;
    --white:    #ffffff;

    --electric: #2dd4a0;
    --electric-glow: rgba(79,142,247,0.18);
    --electric-edge: rgba(79,142,247,0.35);
    --mint:     #2dd4a0;
    --mint-glow: rgba(45,212,160,0.15);
    --amber:    #f5a623;
    --amber-glow: rgba(245,166,35,0.15);
    --rose:     #f56b6b;
    --rose-glow: rgba(245,107,107,0.15);

    --radius-sm: 6px;
    --radius:    10px;
    --radius-lg: 16px;
    --radius-xl: 22px;

    --font-display: 'Syne', sans-serif;
    --font-body:    'Instrument Sans', sans-serif;
    --font-mono:    'JetBrains Mono', monospace;

    --transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: var(--font-body);
    background: var(--void);
    color: var(--body);
}

.page-root {
    background: var(--void);
    min-height: 100vh;
    padding: 32px 24px;
}

.page-root::before {
    content: '';
    position: fixed;
    top: -200px; left: -200px;
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(79,142,247,0.06) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
}

.page-wrap {
    max-width: 1200px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

/* ── Header ── */
.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 32px;
    animation: fadeIn 0.5s ease both;
}

.page-title {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.page-title h1 {
    font-family: var(--font-display);
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--white);
    letter-spacing: -0.04em;
}

.page-title p {
    font-size: 0.8rem;
    color: var(--dim);
}

.page-actions {
    display: flex;
    gap: 12px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 20px;
    border: none;
    border-radius: var(--radius);
    font-family: var(--font-display);
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}

.btn-primary {
    background: var(--electric);
    color: var(--white);
}

.btn-primary:hover {
    background: #07007f;
    transform: translateY(-1px);
    box-shadow: 0 6px 24px rgba(79,142,247,0.35);
}

.btn svg { width: 15px; height: 15px; }

/* ── Filters ── */
.filter-bar {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
    margin-bottom: 24px;
    animation: fadeIn 0.5s 0.1s ease both;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.filter-label {
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.07em;
}

.filter-input {
    background: var(--deep);
    border: 1px solid var(--rim);
    color: var(--bright);
    padding: 10px 13px;
    border-radius: var(--radius);
    font-family: var(--font-body);
    font-size: 0.875rem;
    transition: var(--transition);
    -webkit-appearance: none;
    appearance: none;
}

.filter-input::placeholder { color: var(--muted); }
.filter-input:hover { border-color: var(--muted); }
.filter-input:focus {
    border-color: var(--electric);
    box-shadow: 0 0 0 3px var(--electric-glow);
    background: var(--surface);
    outline: none;
}

select.filter-input {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%234a5068' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 34px;
}

/* ── Stats cards ── */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
    margin-bottom: 24px;
}

.stat-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 16px 20px;
    animation: fadeIn 0.5s 0.2s ease both;
}

.stat-label {
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--muted);
    font-weight: 600;
    margin-bottom: 6px;
}

.stat-value {
    font-family: var(--font-display);
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--electric);
    letter-spacing: -0.02em;
}

/* ── Table ── */
.table-wrap {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    overflow: hidden;
    animation: fadeIn 0.5s 0.3s ease both;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}

thead {
    background: rgba(255,255,255,0.02);
    border-bottom: 1px solid var(--line);
}

th {
    padding: 14px 16px;
    text-align: left;
    font-weight: 600;
    color: var(--muted);
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.07em;
}

td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--line);
    color: var(--body);
}

tbody tr:hover {
    background: rgba(79,142,247,0.05);
}

tbody tr:last-child td {
    border-bottom: none;
}

.student-name {
    font-weight: 600;
    color: var(--bright);
}

.admission-no {
    font-family: var(--font-mono);
    font-size: 0.75rem;
    color: var(--dim);
}

.amount {
    font-family: var(--font-mono);
    font-weight: 600;
    color: var(--mint);
}

.receipt-code {
    font-family: var(--font-mono);
    font-size: 0.75rem;
    padding: 2px 8px;
    background: rgba(45,212,160,0.1);
    border: 1px solid rgba(45,212,160,0.2);
    border-radius: 4px;
    color: var(--mint);
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 2px 10px;
    border-radius: 99px;
    font-size: 0.65rem;
    font-weight: 600;
}

.badge-cash {
    background: rgba(255,193,7,0.15);
    color: #ffc107;
    border: 1px solid rgba(255,193,7,0.3);
}

.badge-mpesa {
    background: rgba(76,175,80,0.15);
    color: #4caf50;
    border: 1px solid rgba(76,175,80,0.3);
}

.badge-bank {
    background: rgba(79,142,247,0.15);
    color: var(--electric);
    border: 1px solid var(--electric-edge);
}

.badge-cheque {
    background: rgba(156,39,176,0.15);
    color: #c2185b;
    border: 1px solid rgba(156,39,176,0.3);
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 12px;
    background: var(--electric);
    color: var(--white);
    border: none;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}

.action-btn:hover {
    background: #5f9bff;
}

.action-btn svg { width: 12px; height: 12px; }

/* ── Empty state ── */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 16px;
    opacity: 0.5;
}

.empty-title {
    font-family: var(--font-display);
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--bright);
    margin-bottom: 8px;
}

.empty-text {
    color: var(--dim);
    margin-bottom: 20px;
}

/* ── Pagination ── */
.pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 24px 16px;
    border-top: 1px solid var(--line);
}

.pagination-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: var(--radius);
    border: 1px solid var(--rim);
    background: var(--raised);
    color: var(--dim);
    text-decoration: none;
    transition: var(--transition);
    font-size: 0.85rem;
    font-weight: 600;
}

.pagination-link:hover {
    border-color: var(--muted);
    color: var(--bright);
    background: var(--surface);
}

.pagination-link.active {
    background: var(--electric);
    border-color: var(--electric);
    color: var(--white);
}

.pagination-link.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* ── Animations ── */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: stretch;
    }

    .page-actions {
        width: 100%;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }

    table {
        font-size: 0.75rem;
    }

    th, td {
        padding: 10px;
    }

    .filter-bar {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="page-root">
<div class="page-wrap">

{{-- Header --}}
<div class="page-header">
    <div class="page-title">
        <h1>Fee Payments</h1>
        <p>Track and manage student payment records</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('fee-payments.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="8" y1="2" x2="8" y2="14"/><line x1="2" y1="8" x2="14" y2="8"/>
            </svg>
            Record Payment
        </a>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="filter-bar">
    <div class="filter-group">
        <label class="filter-label">Academic Year</label>
        <select name="year" class="filter-input" onchange="this.form.submit()">
            @foreach(range(date('Y'), date('Y')-3) as $yr)
                <option value="{{ $yr }}" {{ $year == $yr ? 'selected' : '' }}>{{ $yr }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label class="filter-label">Term</label>
        <select name="term" class="filter-input" onchange="this.form.submit()">
            @foreach(['Term 1', 'Term 2', 'Term 3'] as $t)
                <option value="{{ $t }}" {{ $term == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label class="filter-label">Grade</label>
        <select name="grade" class="filter-input" onchange="this.form.submit()">
            <option value="">All Grades</option>
            @foreach(range(1, 9) as $g)
                <option value="{{ $g }}" {{ request('grade') == $g ? 'selected' : '' }}>Grade {{ $g }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label class="filter-label">Search</label>
        <input type="text" name="search" class="filter-input" placeholder="Name, admission #, receipt..." value="{{ request('search') }}">
    </div>
</form>

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-label">Total Collected</div>
        <div class="stat-value">KES {{ number_format($totalCollected, 0) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Payments Recorded</div>
        <div class="stat-value">{{ $payments->total() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Average Payment</div>
        <div class="stat-value">
            KES {{ $payments->total() > 0 ? number_format($totalCollected / $payments->total(), 0) : 0 }}
        </div>
    </div>
</div>

{{-- Payments Table --}}
@if($payments->count() > 0)
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Grade</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Receipt/Reference</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>
                            <div class="student-name">
                                {{ $payment->student->first_name }} {{ $payment->student->last_name }}
                            </div>
                            <div class="admission-no">{{ $payment->student->admission_number }}</div>
                        </td>
                        <td>Grade {{ $payment->student->grade }}</td>
                        <td class="amount">KES {{ number_format($payment->amount_paid, 2) }}</td>
                        <td>
                            @php
                                $method = $payment->payment_method;
                                $badgeClass = match($method) {
                                    'cash' => 'badge-cash',
                                    'mpesa' => 'badge-mpesa',
                                    'bank_transfer' => 'badge-bank',
                                    'cheque' => 'badge-cheque',
                                    default => 'badge-cash'
                                };
                                $label = match($method) {
                                    'cash' => 'Cash',
                                    'mpesa' => 'M-Pesa',
                                    'bank_transfer' => 'Bank Transfer',
                                    'cheque' => 'Cheque',
                                    default => $method
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                        </td>
                        <td>
                            @if($payment->receipt_number)
                                <span class="receipt-code">{{ $payment->receipt_number }}</span>
                            @else
                                <span style="color: var(--dim);">—</span>
                            @endif
                        </td>
                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('fee-payments.show', $payment->id) }}" class="action-btn">
                                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M8 3v6m0 3v.5"/><circle cx="8" cy="2" r="1"/>
                                </svg>
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($payments->hasPages())
            <div class="pagination-wrap">
                @if($payments->onFirstPage())
                    <span class="pagination-link disabled">← Prev</span>
                @else
                    <a href="{{ $payments->previousPageUrl() }}" class="pagination-link">← Prev</a>
                @endif

                @for($i = 1; $i <= $payments->lastPage(); $i++)
                    @if($i == $payments->currentPage())
                        <span class="pagination-link active">{{ $i }}</span>
                    @else
                        <a href="{{ $payments->url($i) }}" class="pagination-link">{{ $i }}</a>
                    @endif
                @endfor

                @if($payments->hasMorePages())
                    <a href="{{ $payments->nextPageUrl() }}" class="pagination-link">Next →</a>
                @else
                    <span class="pagination-link disabled">Next →</span>
                @endif
            </div>
        @endif
    </div>
@else
    <div class="table-wrap">
        <div class="empty-state">
            <div class="empty-icon">📋</div>
            <div class="empty-title">No Payments Found</div>
            <div class="empty-text">No fee payments recorded for {{ $year }} — {{ $term }}</div>
            <a href="{{ route('fee-payments.create') }}" class="btn btn-primary">
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <line x1="8" y1="2" x2="8" y2="14"/><line x1="2" y1="8" x2="14" y2="8"/>
                </svg>
                Record First Payment
            </a>
        </div>
    </div>
@endif

</div>
</div>

@endsection