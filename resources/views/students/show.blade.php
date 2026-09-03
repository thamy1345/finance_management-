@extends('layouts.admin')
@section('title', $student->first_name . ' ' . $student->last_name)

@push('styles')
<style>
    .info-row { display: flex; padding: 9px 0; border-bottom: 1px solid #f1f5f9; font-size: 13.5px; }
    .info-row:last-child { border-bottom: 0; }
    .info-label { width: 160px; flex-shrink: 0; color: #64748b; font-weight: 500; }
    .info-value { color: #1e293b; font-weight: 500; }
    .ledger-amount { font-variant-numeric: tabular-nums; }
    .term-block { border-left: 3px solid #4f46e5; padding-left: 14px; margin-bottom: 24px; }
    .balance-pill {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-start gap-3">
    <div>
        <h1>{{ $student->first_name }} {{ $student->last_name }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></li>
                <li class="breadcrumb-item active">{{ $student->first_name }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2 flex-wrap no-print">
        <a href="{{ route('fee-payments.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
            <i class="bi bi-cash-coin me-1"></i> Record Payment
        </a>
        <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-secondary">
            <i class="bi bi-pencil-fill me-1"></i> Edit
        </a>
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="bi bi-printer-fill me-1"></i> Print
        </button>
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- LEFT: Profile + Guardian --}}
    <div class="col-lg-4">

        {{-- Profile card --}}
        <div class="card mb-4">
            <div class="card-body text-center py-4">
                <div class="avatar mx-auto mb-3"
                     style="width:72px;height:72px;font-size:26px;background:#4f46e5;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;">
                    {{ strtoupper(substr($student->first_name,0,1).substr($student->last_name,0,1)) }}
                </div>
                <h5 class="mb-1 fw-bold">{{ $student->first_name }} {{ $student->last_name }}</h5>
                <div class="d-flex justify-content-center gap-2 mt-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary border" style="border-color:#e0e7ff!important;">
                        Grade {{ $student->grade }}{{ $student->stream ? ' – '.$student->stream : '' }}
                    </span>
                    <span class="badge {{ $student->status === 'active' ? 'bg-success' : 'bg-secondary' }} bg-opacity-10
                          {{ $student->status === 'active' ? 'text-success' : 'text-secondary' }}">
                        {{ ucfirst($student->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="info-row"><span class="info-label">Admission No</span><span class="info-value font-monospace">{{ $student->admission_number }}</span></div>
                <div class="info-row"><span class="info-label">Gender</span><span class="info-value">{{ ucfirst($student->gender ?? '—') }}</span></div>
                @if($student->date_of_birth)
                <div class="info-row"><span class="info-label">Date of Birth</span><span class="info-value">{{ \Carbon\Carbon::parse($student->date_of_birth)->format('d M Y') }}</span></div>
                @endif
                <div class="info-row"><span class="info-label">Enrolled</span><span class="info-value">{{ $student->created_at->format('d M Y') }}</span></div>
                @if($student->notes)
                <div class="info-row"><span class="info-label">Notes</span><span class="info-value small text-muted">{{ $student->notes }}</span></div>
                @endif
            </div>
        </div>

        {{-- Guardian card --}}
        <div class="card mb-4">
            <div class="card-header"><span class="card-title"><i class="bi bi-person-lines-fill me-1 text-primary"></i>Guardian / Parent</span></div>
            <div class="card-body">
                <div class="info-row"><span class="info-label">Name</span><span class="info-value">{{ $student->parent_name ?? '—' }}</span></div>
                <div class="info-row"><span class="info-label">Phone</span>
                    <span class="info-value">
                        @if($student->parent_phone)
                            <a href="tel:{{ $student->parent_phone }}" class="text-decoration-none">{{ $student->parent_phone }}</a>
                        @else —
                        @endif
                    </span>
                </div>
                <div class="info-row"><span class="info-label">Email</span>
                    <span class="info-value">
                        @if($student->parent_email)
                            <a href="mailto:{{ $student->parent_email }}" class="text-decoration-none small">{{ $student->parent_email }}</a>
                        @else —
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- Fee summary card --}}
        <div class="card">
            <div class="card-header"><span class="card-title"><i class="bi bi-wallet2 me-1 text-success"></i>Fee Summary</span></div>
            <div class="card-body">
                @php
                    $totalPaid      = $payments->sum('amount_paid');
                    $currentFee     = $currentFeeStructure->total_fee   ?? 0;
                    $currentTermPaid= $currentTermPaid ?? 0;
                    $balance        = $currentFee - $currentTermPaid;
                @endphp
                <div class="info-row">
                    <span class="info-label">Term Fee</span>
                    <span class="info-value fw-semibold">{{ number_format($currentFee, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Term Paid</span>
                    <span class="info-value text-success fw-semibold">{{ number_format($currentTermPaid, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Balance</span>
                    <span class="info-value {{ $balance > 0 ? 'text-danger' : 'text-success' }} fw-bold">
                        {{ number_format(abs($balance), 2) }}
                        {{ $balance > 0 ? 'OWING' : ($balance < 0 ? 'CREDIT' : '') }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">All-time Paid</span>
                    <span class="info-value text-primary fw-semibold">{{ number_format($totalPaid, 2) }}</span>
                </div>
                <div class="mt-3">
                    @if($balance <= 0)
                        <span class="balance-pill bg-success bg-opacity-10 text-success w-100 justify-content-center">
                            <i class="bi bi-check-circle-fill"></i> Fully Paid
                        </span>
                    @elseif($currentTermPaid > 0)
                        <span class="balance-pill bg-warning bg-opacity-10 text-warning w-100 justify-content-center">
                            <i class="bi bi-hourglass-split"></i> Partial Payment
                        </span>
                    @else
                        <span class="balance-pill bg-danger bg-opacity-10 text-danger w-100 justify-content-center">
                            <i class="bi bi-exclamation-triangle-fill"></i> No Payment Yet
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: Fee Ledger --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="card-title"><i class="bi bi-journal-text me-1 text-primary"></i>Fee Payment Ledger</span>
                <a href="{{ route('fee-payments.create', ['student_id' => $student->id]) }}" class="btn btn-sm btn-primary no-print">
                    <i class="bi bi-plus-lg me-1"></i> Add Payment
                </a>
            </div>

            @if($paymentsByTerm->isEmpty())
                <div class="card-body text-center py-5">
                    <i class="bi bi-receipt text-muted" style="font-size:40px;"></i>
                    <div class="text-muted mt-2">No payments recorded yet.</div>
                    <a href="{{ route('fee-payments.create', ['student_id' => $student->id]) }}" class="btn btn-primary btn-sm mt-3">
                        Record First Payment
                    </a>
                </div>
            @else
                <div class="card-body">
                    @foreach($paymentsByTerm as $termKey => $termData)
                    @php
                        $termPayments  = $termData['payments'];
                        $termStructure = $termData['structure'] ?? null;
                        $termFee       = $termStructure->total_fee ?? 0;
                        $termPaid      = $termPayments->sum('amount_paid');
                        $termBalance   = $termFee - $termPaid;
                        [$year, $term] = explode('|', $termKey);
                    @endphp
                    <div class="term-block">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $term }} — {{ $year }}</h6>
                                @if($termStructure)
                                <small class="text-muted">Fee: {{ number_format($termFee, 2) }} | {{ $termStructure->name ?? '' }}</small>
                                @endif
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <span class="badge bg-success bg-opacity-10 text-success fw-semibold">
                                    Paid: {{ number_format($termPaid, 2) }}
                                </span>
                                @if($termBalance > 0)
                                <span class="badge bg-danger bg-opacity-10 text-danger fw-semibold">
                                    Bal: {{ number_format($termBalance, 2) }}
                                @elseif($termBalance < 0)
                                <span class="badge bg-info bg-opacity-10 text-info fw-semibold">
                                    Credit: {{ number_format(abs($termBalance), 2) }}
                                @else
                                <span class="badge bg-success bg-opacity-10 text-success fw-semibold">
                                    ✓ Cleared
                                @endif
                                </span>
                            </div>
                        </div>

                        @if($termFee > 0)
                        <div class="progress mb-3" style="height:5px;">
                            <div class="progress-bar bg-success"
                                 style="width: {{ min(100, $termFee > 0 ? ($termPaid/$termFee*100) : 0) }}%"></div>
                        </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-sm table-card">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Receipt No</th>
                                        <th>Mode</th>
                                        <th>Reference</th>
                                        <th class="text-end">Amount</th>
                                        <th class="no-print"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($termPayments as $i => $payment)
                                    <tr>
                                        <td class="text-muted">{{ $i + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                                        <td><span class="font-monospace text-muted small">{{ $payment->receipt_number ?? '—' }}</span></td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ ucfirst(str_replace('_', ' ', $payment->payment_mode ?? 'cash')) }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">{{ $payment->reference ?? '—' }}</td>
                                        <td class="text-end fw-semibold text-success ledger-amount">
                                            {{ number_format($payment->amount_paid, 2) }}
                                        </td>
                                        <td class="no-print">
                                            <div class="d-flex gap-1 justify-content-end">
                                                <a href="{{ route('fee-payments.show', $payment) }}"
                                                   class="btn-icon" title="View Receipt" style="font-size:13px;">
                                                    <i class="bi bi-receipt"></i>
                                                </a>
                                                <a href="{{ route('fee-payments.edit', $payment) }}"
                                                   class="btn-icon" title="Edit" style="font-size:13px;">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                                <form action="{{ route('fee-payments.destroy', $payment) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Delete this payment? This cannot be undone.')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn-icon border-0"
                                                            style="background:none;color:#e11d48;font-size:13px;" title="Delete">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    {{-- Term subtotal --}}
                                    <tr class="table-light fw-semibold">
                                        <td colspan="5" class="text-end text-muted small">Term Total</td>
                                        <td class="text-end text-success">{{ number_format($termPaid, 2) }}</td>
                                        <td class="no-print"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach

                    {{-- Grand total --}}
                    <div class="d-flex justify-content-end mt-2">
                        <table class="table table-sm" style="max-width:320px;">
                            <tr class="fw-bold">
                                <td class="text-end text-muted">All-time Total Paid</td>
                                <td class="text-end text-primary" style="width:120px;">
                                    {{ number_format($payments->sum('amount_paid'), 2) }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection