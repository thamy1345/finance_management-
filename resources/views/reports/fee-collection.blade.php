@extends('layouts.admin')
@section('title', 'Fee Collection Report')

@section('content')
<div class="page-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
    <a href="{{ route('reports.index') }}" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:#1a1d27;border:1px solid #2a2d3e;color:#6b7280;text-decoration:none;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h1 style="font-size:1.35rem;font-weight:700;color:#e8eaf0;margin:0;">Fee Collection</h1>
        <p style="font-size:.8rem;color:#6b7280;margin:0;">{{ $academicYear }} &mdash; {{ $term }}</p>
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

<div class="card">
    <div class="card-header"><span class="card-title">Collection by Grade</span></div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Grade</th>
                    <th class="text-end">Expected</th>
                    <th class="text-end">Collected</th>
                    <th class="text-end">Balance</th>
                    <th style="width:160px;">Progress</th>
                    <th class="text-end">%</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                <tr>
                    <td><span class="badge" style="background:#eff6ff;color:#1d4ed8;">Grade {{ $row['grade'] }}</span></td>
                    <td class="text-end">KSh {{ number_format($row['totalFee']) }}</td>
                    <td class="text-end text-success fw-bold">KSh {{ number_format($row['totalPaid']) }}</td>
                    <td class="text-end {{ $row['balance'] > 0 ? 'text-danger' : 'text-success' }} fw-bold">
                        KSh {{ number_format($row['balance']) }}
                    </td>
                    <td>
                        <div style="background:#12141c;border-radius:99px;height:8px;overflow:hidden;">
                            <div style="height:100%;border-radius:99px;width:{{ $row['pct'] }}%;background:{{ $row['pct'] >= 80 ? '#22c55e' : ($row['pct'] >= 50 ? '#f59e0b' : '#ef4444') }};"></div>
                        </div>
                    </td>
                    <td class="text-end fw-bold" style="color:{{ $row['pct'] >= 80 ? '#22c55e' : ($row['pct'] >= 50 ? '#f59e0b' : '#ef4444') }};">
                        {{ $row['pct'] }}%
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No data found</td></tr>
                @endforelse
            </tbody>
            @if($data->count())
            <tfoot>
                <tr style="border-top:2px solid #2a2d3e;font-weight:700;">
                    <td>Total</td>
                    <td class="text-end">KSh {{ number_format($data->sum('totalFee')) }}</td>
                    <td class="text-end text-success">KSh {{ number_format($data->sum('totalPaid')) }}</td>
                    <td class="text-end text-danger">KSh {{ number_format($data->sum('balance')) }}</td>
                    <td></td>
                    <td class="text-end">
                        @php $tot = $data->sum('totalFee'); $paid = $data->sum('totalPaid'); @endphp
                        {{ $tot > 0 ? round(($paid/$tot)*100) : 0 }}%
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection