@extends('layouts.admin')
@section('title', 'Payment Methods Report')

@section('content')
<div class="page-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
    <a href="{{ route('reports.index') }}" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:#1a1d27;border:1px solid #2a2d3e;color:#6b7280;text-decoration:none;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h1 style="font-size:1.35rem;font-weight:700;color:#e8eaf0;margin:0;">Payment Methods</h1>
        <p style="font-size:.8rem;color:#6b7280;margin:0;">{{ $academicYear }}{{ $term ? ' · '.$term : '' }}</p>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-4">
                <label class="form-label mb-1" style="font-size:.75rem;color:#9ca3af;text-transform:uppercase;">Academic Year</label>
                <input type="text" name="academic_year" class="form-control" value="{{ $academicYear }}">
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

@php
$methodLabels = ['cash' => 'Cash', 'mpesa' => 'M-Pesa', 'bank_transfer' => 'Bank Transfer', 'cheque' => 'Cheque'];
$methodColors = ['cash' => '#22c55e', 'mpesa' => '#3b82f6', 'bank_transfer' => '#f59e0b', 'cheque' => '#a855f7'];
@endphp

<div class="row g-3">
    {{-- Fee Payments by Method --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><span class="card-title">Fee Payments by Method</span></div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Method</th>
                            <th class="text-end">Transactions</th>
                            <th class="text-end">Amount</th>
                            <th class="text-end">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($byMethod as $row)
                        @php $color = $methodColors[$row->payment_method] ?? '#6b7280'; @endphp
                        <tr>
                            <td>
                                <span style="display:inline-flex;align-items:center;gap:6px;">
                                    <span style="width:8px;height:8px;border-radius:50%;background:{{ $color }};display:inline-block;"></span>
                                    {{ $methodLabels[$row->payment_method] ?? strtoupper($row->payment_method) }}
                                </span>
                            </td>
                            <td class="text-end" style="color:#9ca3af;">{{ $row->count }}</td>
                            <td class="text-end fw-bold" style="color:{{ $color }};">KSh {{ number_format($row->total) }}</td>
                            <td class="text-end" style="color:#6b7280;font-size:.82rem;">
                                {{ $grandTotal > 0 ? round(($row->total / $grandTotal) * 100) : 0 }}%
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No data</td></tr>
                        @endforelse
                    </tbody>
                    @if($byMethod->count())
                    <tfoot>
                        <tr style="border-top:2px solid #2a2d3e;font-weight:700;">
                            <td>Total</td>
                            <td class="text-end">{{ $byMethod->sum('count') }}</td>
                            <td class="text-end">KSh {{ number_format($grandTotal) }}</td>
                            <td class="text-end" style="color:#6b7280;">100%</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- Transactions by Method --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><span class="card-title">Other Transactions by Method</span></div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Method</th>
                            <th class="text-end">Transactions</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($txnByMethod as $row)
                        <tr>
                            <td>{{ $methodLabels[$row->payment_method] ?? strtoupper($row->payment_method ?? '—') }}</td>
                            <td class="text-end" style="color:#9ca3af;">{{ $row->count }}</td>
                            <td class="text-end fw-bold" style="color:#e8eaf0;">KSh {{ number_format($row->total) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection