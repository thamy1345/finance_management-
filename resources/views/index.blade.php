@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .chart-wrap { height: 240px; position: relative; }
    .activity-item { display:flex; gap:12px; align-items:flex-start; padding:10px 0; border-bottom:1px solid var(--border); }
    .activity-item:last-child { border-bottom:none; }
    .activity-dot { width:9px;height:9px;border-radius:50%;margin-top:5px;flex-shrink:0; }
    .quick-action { display:flex;flex-direction:column;align-items:center;gap:8px;padding:18px 12px;
        background:var(--surface);border:1px solid var(--border);border-radius:12px;text-decoration:none;
        color:#374151;transition:all .18s;font-size:13px;font-weight:500;text-align:center; }
    .quick-action:hover { border-color:var(--accent);color:var(--accent);transform:translateY(-2px); }
    .quick-action i { font-size:22px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }} 👋</h1>
        <p>Here's what's happening in your school today.</p>
    </div>
    <a href="{{ route('reports.index') }}" class="btn btn-navy btn-sm gap-1">
        <i class="bi bi-download"></i> Export Report
    </a>
</div>

{{-- ── Stat Row ── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-value">{{ $totalStudents }}</div>
                <div class="stat-label">Total Students</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="stat-value">KSh {{ number_format($totalFeesCollected) }}</div>
                <div class="stat-label">Fees Collected</div>
                <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> This term</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-exclamation-circle"></i></div>
            <div>
                <div class="stat-value">KSh {{ number_format($totalOutstanding) }}</div>
                <div class="stat-label">Outstanding Fees</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon gold"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="stat-value">KSh {{ number_format($netBalance) }}</div>
                <div class="stat-label">Net Balance</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Second Row ── --}}
<div class="row g-3 mb-4">
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-bar-chart me-2 text-muted"></i>Monthly Collections</span>
                <select class="form-select form-select-sm w-auto" id="chartYear">
                    <option>{{ date('Y') }}</option>
                    <option>{{ date('Y') - 1 }}</option>
                </select>
            </div>
            <div class="card-body">
                <div class="chart-wrap">
                    <canvas id="collectionsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-pie-chart me-2 text-muted"></i>Fee Status</div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center gap-4">
                <canvas id="statusChart" style="max-width:180px;"></canvas>
                <div class="d-flex gap-4 flex-wrap justify-content-center" style="font-size:13px;">
                    <span><span style="display:inline-block;width:10px;height:10px;background:#22c55e;border-radius:50%;margin-right:4px;"></span>Paid {{ $paidPct ?? 0 }}%</span>
                    <span><span style="display:inline-block;width:10px;height:10px;background:#f0a500;border-radius:50%;margin-right:4px;"></span>Partial {{ $partialPct ?? 0 }}%</span>
                    <span><span style="display:inline-block;width:10px;height:10px;background:#ef4444;border-radius:50%;margin-right:4px;"></span>Unpaid {{ $unpaidPct ?? 0 }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Third Row ── --}}
<div class="row g-3 mb-4">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-lightning-charge me-2 text-muted"></i>Quick Actions</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('students.create') }}" class="quick-action">
                            <i class="bi bi-person-plus" style="color:#3b82f6;"></i> Add Student
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('fee-payments.create') }}" class="quick-action">
                            <i class="bi bi-credit-card" style="color:#22c55e;"></i> Record Payment
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('transactions.create') }}" class="quick-action">
                            <i class="bi bi-receipt" style="color:#f0a500;"></i> Add Transaction
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('reports.index') }}" class="quick-action">
                            <i class="bi bi-file-earmark-bar-graph" style="color:#8b5cf6;"></i> View Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-clock-history me-2 text-muted"></i>Recent Payments</span>
                <a href="{{ route('fee-payments.index') }}" class="btn btn-sm" style="font-size:13px;color:var(--accent);">View all →</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $p)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar" style="width:28px;height:28px;font-size:11px;">
                                        {{ strtoupper(substr($p->student->name ?? '?', 0, 2)) }}
                                    </span>
                                    <span>{{ $p->student->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="fw-600">KSh {{ number_format($p->amount) }}</td>
                            <td>{{ ucfirst($p->payment_method ?? '—') }}</td>
                            <td style="color:#6b7280;">{{ $p->payment_date?->format('d M Y') }}</td>
                            <td>
                                <span class="badge badge-paid">Paid</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No payments recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ── Bottom Row: Top Defaulters + Recent Transactions ── --}}
<div class="row g-3">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Top Defaulters</span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr><th>Student</th><th>Grade</th><th>Outstanding</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($topDefaulters as $s)
                        <tr>
                            <td>{{ $s->name }}</td>
                            <td><span class="badge" style="background:#f0f4ff;color:#3b4b8c;">Grade {{ $s->grade }}</span></td>
                            <td class="text-danger fw-bold">KSh {{ number_format($s->balance_due) }}</td>
                            <td>
                                <a href="{{ route('students.show', $s) }}" class="btn btn-sm btn-outline-secondary" style="font-size:12px;">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">No defaulters. 🎉</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-arrow-left-right me-2 text-muted"></i>Recent Transactions</span>
                <a href="{{ route('transactions.index') }}" style="font-size:13px;color:var(--accent);">View all →</a>
            </div>
            <div class="card-body p-0 px-3">
                @forelse($recentTransactions as $t)
                <div class="activity-item">
                    <div class="activity-dot" style="background:{{ $t->type === 'income' ? '#22c55e' : '#ef4444' }};"></div>
                    <div class="flex-1">
                        <div style="font-size:14px;font-weight:500;">{{ $t->description }}</div>
                        <div style="font-size:12px;color:#9ca3af;">{{ $t->created_at?->format('d M Y') }} · {{ $t->category }}</div>
                    </div>
                    <div style="font-size:14px;font-weight:700;color:{{ $t->type==='income' ? '#22c55e' : '#ef4444' }};">
                        {{ $t->type === 'income' ? '+' : '-' }}KSh {{ number_format($t->amount) }}
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-4" style="font-size:14px;">No transactions yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function(){
    // Collections chart
    const ctx = document.getElementById('collectionsChart');
    if(ctx){
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                datasets: [{
                    label: 'Collections (KSh)',
                    data: @json($monthlyCollections ?? array_fill(0, 12, 0)),
                    backgroundColor: 'rgba(240,165,0,.8)',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#f0f0f0' }, ticks: { callback: v => 'KSh '+v.toLocaleString() } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Status donut
    const ctx2 = document.getElementById('statusChart');
    if(ctx2){
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Paid','Partial','Unpaid'],
                datasets: [{ data: [{{ $paidPct ?? 60 }},{{ $partialPct ?? 25 }},{{ $unpaidPct ?? 15 }}],
                    backgroundColor:['#22c55e','#f0a500','#ef4444'], borderWidth:0, hoverOffset:6 }]
            },
            options: { cutout:'72%', plugins:{legend:{display:false}} }
        });
    }
})();
</script>
@endpush