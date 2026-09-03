@extends('layouts.admin')
@section('title','Outstanding Fees')
@section('page-title','Outstanding Fees')

@section('content')
<div class="page-header">
    <h1>Outstanding Fees</h1>
    <div class="actions">
        <a href="{{ route('fee-payments.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Record Payment</a>
    </div>
</div>

<form method="GET" class="filter-bar">
    <select name="year" class="form-control">
        @foreach(range(date('Y'),date('Y')-3) as $y)
            <option value="{{ $y }}" {{ $year==$y?'selected':''}}>{{ $y }}</option>
        @endforeach
    </select>
    <select name="term" class="form-control">
        @foreach(['Term 1','Term 2','Term 3'] as $t)
            <option value="{{ $t }}" {{ $term==$t?'selected':''}}>{{ $t }}</option>
        @endforeach
    </select>
    <select name="grade" class="form-control">
        <option value="">All Grades</option>
        @foreach(range(1,9) as $g)
            <option value="{{ $g }}" {{ request('grade')==$g?'selected':''}}>Grade {{ $g }}</option>
        @endforeach
    </select>
    <select name="payment_status" class="form-control">
        <option value="">All Status</option>
        <option value="unpaid"  {{ request('payment_status')==='unpaid'?'selected':''}}>Unpaid</option>
        <option value="partial" {{ request('payment_status')==='partial'?'selected':''}}>Partial</option>
        <option value="cleared" {{ request('payment_status')==='cleared'?'selected':''}}>Cleared</option>
    </select>
    <button type="submit" class="btn btn-primary"><i class="ti ti-search"></i> Filter</button>
    <a href="{{ route('fee-payments.outstanding') }}" class="btn">Clear</a>
</form>

@php
    $totalExpected  = $report->sum('expected');
    $totalPaid      = $report->sum('paid');
    $totalBalance   = $report->sum('balance');
    $unpaidCount    = $report->where('status','unpaid')->count();
    $partialCount   = $report->where('status','partial')->count();
    $clearedCount   = $report->where('status','cleared')->count();
@endphp

<div class="metrics" style="grid-template-columns:repeat(4,1fr);margin-bottom:16px">
    <div class="metric-tile">
        <div class="metric-label"><i class="ti ti-coins"></i> Total Expected</div>
        <div class="metric-value">KES {{ number_format($totalExpected) }}</div>
    </div>
    <div class="metric-tile">
        <div class="metric-label"><i class="ti ti-cash"></i> Collected</div>
        <div class="metric-value text-green">KES {{ number_format($totalPaid) }}</div>
        @if($totalExpected > 0)
        <div class="progress-bar"><div class="progress-fill" style="width:{{ min(100,round(($totalPaid/$totalExpected)*100)) }}%"></div></div>
        <div class="metric-sub up">{{ round(($totalPaid/$totalExpected)*100) }}%</div>
        @endif
    </div>
    <div class="metric-tile">
        <div class="metric-label"><i class="ti ti-alert-circle"></i> Outstanding</div>
        <div class="metric-value text-red">KES {{ number_format($totalBalance) }}</div>
    </div>
    <div class="metric-tile">
        <div class="metric-label">By Status</div>
        <div style="display:flex;gap:6px;flex-wrap:wrap;margin-top:6px">
            <span class="badge badge-danger">{{ $unpaidCount }} Unpaid</span>
            <span class="badge badge-warning">{{ $partialCount }} Partial</span>
            <span class="badge badge-success">{{ $clearedCount }} Cleared</span>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="tbl">
            <thead><tr>
                <th>Student</th><th>Grade</th><th>Parent Phone</th>
                <th>Expected</th><th>Paid</th><th>Balance</th><th>Status</th><th></th>
            </tr></thead>
            <tbody>
            @forelse($report as $row)
            <tr>
                <td>
                    <a href="{{ route('students.show', $row['student']) }}" style="text-decoration:none;color:var(--text);font-weight:500">{{ $row['student']->full_name }}</a><br>
                    <span class="mono text-muted" style="font-size:11px">{{ $row['student']->admission_number }}</span>
                </td>
                <td>Grade {{ $row['student']->grade }}</td>
                <td class="mono" style="font-size:12px">{{ $row['student']->parent_phone }}</td>
                <td>KES {{ number_format($row['expected']) }}</td>
                <td class="text-green fw-600">KES {{ number_format($row['paid']) }}</td>
                <td class="{{ $row['balance'] > 0 ? 'text-red fw-600' : 'text-green' }}">KES {{ number_format(abs($row['balance'])) }}</td>
                <td>
                    <span class="badge {{ $row['status']==='cleared'?'badge-success':($row['status']==='partial'?'badge-warning':'badge-danger') }}">
                        {{ ucfirst($row['status']) }}
                    </span>
                </td>
                <td>
                    <div style="display:flex;gap:4px">
                        <a href="{{ route('students.ledger', $row['student']) }}?year={{ $year }}" class="btn btn-sm" title="View Ledger"><i class="ti ti-notebook"></i></a>
                        @if($row['status'] !== 'cleared')
                        <a href="{{ route('fee-payments.create') }}?student_id={{ $row['student']->id }}&term={{ urlencode($term) }}&year={{ $year }}" class="btn btn-sm btn-primary" title="Record Payment"><i class="ti ti-cash"></i></a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8"><div class="empty-state"><i class="ti ti-circle-check"></i>All fees cleared — nothing outstanding!</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection