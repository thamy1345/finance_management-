{{-- resources/views/payments/receipt.blade.php --}}
@extends('layouts.admin')
@section('title', 'Payment Receipt')
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

    --electric: #4f8ef7;
    --electric-glow: rgba(79,142,247,0.18);
    --electric-edge: rgba(79,142,247,0.35);
    --mint:     #2dd4a0;
    --mint-glow: rgba(45,212,160,0.15);
    --amber:    #f5a623;
    --amber-glow: rgba(245,166,35,0.15);

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

.receipt-root {
    font-family: var(--font-body);
    background: var(--void);
    min-height: 100vh;
    color: var(--body);
    padding: 32px 24px 80px;
}

.receipt-root::before {
    content: '';
    position: fixed;
    top: -200px; left: -200px;
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(79,142,247,0.06) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
}

.receipt-wrap {
    max-width: 600px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

/* ── Header ── */
.receipt-header {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 32px;
    animation: fadeSlideDown 0.5s ease both;
}

@keyframes fadeSlideDown {
    from { opacity: 0; transform: translateY(-12px); }
    to { opacity: 1; transform: translateY(0); }
}

.back-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px; height: 40px;
    border-radius: var(--radius);
    background: var(--raised);
    border: 1px solid var(--rim);
    color: var(--dim);
    text-decoration: none;
    flex-shrink: 0;
    transition: var(--transition);
}

.back-btn:hover {
    background: var(--rim);
    color: var(--bright);
}

.receipt-title h1 {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--white);
    letter-spacing: -0.04em;
}

.receipt-title p {
    font-size: 0.8rem;
    color: var(--dim);
    margin-top: 2px;
}

/* ── Receipt Card ── */
.receipt-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    overflow: hidden;
    animation: cardIn 0.5s 0.1s ease both;
}

@keyframes cardIn {
    from { opacity: 0; transform: translateY(14px); }
    to { opacity: 1; transform: translateY(0); }
}

.receipt-header-card {
    background: linear-gradient(135deg, rgba(79,142,247,0.1) 0%, rgba(45,212,160,0.05) 100%);
    border-bottom: 1px solid var(--line);
    padding: 32px 24px;
    text-align: center;
}

.receipt-number {
    font-family: var(--font-mono);
    font-size: 0.7rem;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 12px;
}

.receipt-code {
    font-family: var(--font-mono);
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--mint);
    letter-spacing: 0.04em;
}

.receipt-body {
    padding: 28px 24px;
}

/* ── Section ── */
.receipt-section {
    margin-bottom: 28px;
    padding-bottom: 28px;
    border-bottom: 1px solid var(--line);
}

.receipt-section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.section-title {
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.09em;
    margin-bottom: 12px;
}

.section-divider {
    height: 1px;
    background: var(--line);
    margin: 16px 0;
}

/* ── Row ── */
.receipt-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.receipt-row:last-child { margin-bottom: 0; }

.row-label {
    font-size: 0.75rem;
    color: var(--dim);
}

.row-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--bright);
    text-align: right;
}

.row-value.amount {
    font-family: var(--font-mono);
    color: var(--mint);
    font-size: 1rem;
}

/* ── Student Card ── */
.student-card {
    background: rgba(79,142,247,0.05);
    border: 1px solid var(--electric-edge);
    border-radius: var(--radius-lg);
    padding: 16px;
    margin-bottom: 4px;
}

.student-name {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--electric);
    margin-bottom: 8px;
}

.student-detail {
    font-size: 0.75rem;
    color: var(--dim);
    margin-bottom: 4px;
}

/* ── Badge ── */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 10px;
    border-radius: 99px;
    font-size: 0.65rem;
    font-weight: 600;
    margin-top: 8px;
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

/* ── Footer ── */
.receipt-footer {
    position: fixed;
    bottom: 0; left: 0; right: 0;
    background: rgba(7,8,16,0.9);
    backdrop-filter: blur(16px) saturate(180%);
    border-top: 1px solid var(--line);
    padding: 12px 24px;
    display: flex;
    justify-content: center;
    gap: 12px;
    z-index: 100;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
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
    background: #5f9bff;
    transform: translateY(-1px);
}

.btn-secondary {
    background: transparent;
    color: var(--dim);
    border: 1px solid var(--rim);
}

.btn-secondary:hover {
    color: var(--bright);
    border-color: var(--muted);
}

.btn-danger {
    background: rgba(245,107,107,0.15);
    color: var(--rose);
    border: 1px solid rgba(245,107,107,0.3);
}

.btn-danger:hover {
    background: rgba(245,107,107,0.25);
}

.btn svg { width: 14px; height: 14px; }

/* ── Overpayment highlight ── */
.overpayment-highlight {
    background: rgba(45, 212, 160, 0.1);
    border: 1px solid rgba(45, 212, 160, 0.3);
    padding: 12px;
    border-radius: var(--radius-lg);
    margin-top: 12px;
}

@media (max-width: 620px) {
    .receipt-title h1 { font-size: 1.25rem; }
    .receipt-footer {
        flex-wrap: wrap;
    }
    .btn { flex: 1; justify-content: center; }
}

@media print {
    .receipt-header, .receipt-footer { display: none; }
    body { background: var(--white); color: #000; }
    .receipt-root { padding: 0; background: var(--white); }
    .receipt-wrap { max-width: 100%; }
    .receipt-card { border: none; background: var(--white); }
}
</style>

<div class="receipt-root">
<div class="receipt-wrap">

{{-- Header --}}
<div class="receipt-header">
    <a href="{{ route('fee-payments.index') }}" class="back-btn" title="Back to payments">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10 3L5 8l5 5"/>
        </svg>
    </a>
    <div class="receipt-title">
        <h1>Payment Receipt</h1>
        <p>Transaction details and confirmation</p>
    </div>
</div>

{{-- Receipt Card --}}
<div class="receipt-card">
    {{-- Receipt Header --}}
    <div class="receipt-header-card">
        <div class="receipt-number">Receipt Number</div>
        <div class="receipt-code">{{ $feePayment->receipt_number }}</div>
    </div>

    {{-- Receipt Body --}}
    <div class="receipt-body">
        
        {{-- Student Section --}}
        <div class="receipt-section">
            <div class="section-title">Student Information</div>
            <div class="student-card">
                <div class="student-name">{{ $feePayment->student->first_name }} {{ $feePayment->student->last_name }}</div>
                <div class="student-detail">Admission Number: <strong>{{ $feePayment->student->admission_number }}</strong></div>
                <div class="student-detail">Grade: <strong>{{ $feePayment->student->grade }}</strong></div>
            </div>
        </div>

        {{-- Payment Section --}}
        <div class="receipt-section">
            <div class="section-title">Payment Details</div>
            <div class="receipt-row">
                <span class="row-label">Amount Paid</span>
                <span class="row-value amount">KES {{ number_format($feePayment->amount_paid, 2) }}</span>
            </div>
            @php
                $year = $feePayment->academic_year;
                $term = $feePayment->term;

                $structure = $feePayment->student->getFeeStructure($year, $term);

                $expected = $structure ? $structure->total_fee : 0;
                $paidSoFar = $feePayment->student->totalPaid($year, $term);

                // Calculate actual balance (can be negative for overpayment)
                $balanceAfter = $expected - $paidSoFar;
                $isOverpayment = $balanceAfter < 0;
                $balanceDisplay = abs($balanceAfter);
            @endphp

            <div class="section-divider"></div>

            <div class="receipt-row">
                <span class="row-label">Expected Fees</span>
                <span class="row-value">
                    KES {{ number_format($expected, 2) }}
                </span>
            </div>

            <div class="receipt-row">
                <span class="row-label">Total Paid (Including This)</span>
                <span class="row-value amount">
                    KES {{ number_format($paidSoFar, 2) }}
                </span>
            </div>

            @if($isOverpayment)
                <div class="receipt-row">
                    <span class="row-label" style="color: #2dd4a0; font-weight: 700;">✓ Overpayment / Surplus</span>
                    <span class="row-value amount" style="color: #2dd4a0;">
                        KES {{ number_format($balanceDisplay, 2) }}
                    </span>
                </div>
                <div class="overpayment-highlight">
                    <div style="font-size: 0.8rem; color: #2dd4a0; font-weight: 600; text-align: center;">
                        Student has paid more than the required fees. Remaining amount can be used for future terms.
                    </div>
                </div>
            @else
                <div class="receipt-row">
                    <span class="row-label">Balance Remaining</span>
                    <span class="row-value amount" style="color: #f5a623;">
                        KES {{ number_format($balanceDisplay, 2) }}
                    </span>
                </div>
            @endif

            <div class="section-divider" style="margin-top: 20px;"></div>

            <div class="receipt-row">
                <span class="row-label">Academic Year</span>
                <span class="row-value">{{ $feePayment->academic_year }}</span>
            </div>
            <div class="receipt-row">
                <span class="row-label">Term</span>
                <span class="row-value">{{ $feePayment->term }}</span>
            </div>
            <div class="receipt-row">
                <span class="row-label">Payment Date</span>
                <span class="row-value">{{ $feePayment->payment_date->format('F d, Y') }}</span>
            </div>
            <div class="receipt-row">
                <span class="row-label">Payment Method</span>
                <span class="row-value">
                    @php
                        $method = $feePayment->payment_method;
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
                </span>
            </div>
        </div>

        {{-- Reference Section --}}
        @if($feePayment->receipt_number || $feePayment->mpesa_code || $feePayment->bank_reference)
        <div class="receipt-section">
            <div class="section-title">Reference Information</div>
            @if($feePayment->mpesa_code)
                <div class="receipt-row">
                    <span class="row-label">M-Pesa Code</span>
                    <span class="row-value" style="font-family: var(--font-mono);">{{ $feePayment->mpesa_code }}</span>
                </div>
            @endif
            @if($feePayment->bank_reference)
                <div class="receipt-row">
                    <span class="row-label">Bank Reference</span>
                    <span class="row-value" style="font-family: var(--font-mono);">{{ $feePayment->bank_reference }}</span>
                </div>
            @endif
        </div>
        @endif

        {{-- Additional Info Section --}}
        <div class="receipt-section">
            <div class="section-title">Transaction Information</div>
            <div class="receipt-row">
                <span class="row-label">Recorded By</span>
                <span class="row-value">{{ $feePayment->recorder->name ?? 'System' }}</span>
            </div>
            <div class="receipt-row">
                <span class="row-label">Recorded Date</span>
                <span class="row-value">{{ $feePayment->created_at->format('F d, Y g:i A') }}</span>
            </div>
            @if($feePayment->notes)
                <div class="section-divider"></div>
                <div class="receipt-row">
                    <span class="row-label">Notes</span>
                    <span class="row-value">{{ $feePayment->notes }}</span>
                </div>
            @endif
        </div>

    </div>
</div>

</div>
</div>

{{-- Footer --}}
<div class="receipt-footer">
    <button onclick="window.print()" class="btn btn-primary">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="3" width="12" height="10" rx="1"/><path d="M2 7h12M4 12h8"/>
        </svg>
        Print Receipt
    </button>
    <a href="{{ route('students.show', $feePayment->student->id) }}" class="btn btn-secondary">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
            <circle cx="8" cy="5" r="3"/><path d="M1 14c0-3.866 3.134-7 7-7s7 3.134 7 7" stroke-linecap="round"/>
        </svg>
        View Student
    </a>
    <form action="{{ route('fee-payments.destroy', $feePayment->id) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this payment record? This cannot be undone.')">
            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 4h12M6 7v5M10 7v5M3 4l1 9c0 .6.4 1 1 1h6c.6 0 1-.4 1-1l1-9"/>
            </svg>
            Delete
        </button>
    </form>
</div>

@endsection