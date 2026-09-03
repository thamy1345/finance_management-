@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap');

    /* ── Light tokens ── */
    :root {
        --font-sans: 'Inter', system-ui, sans-serif;
        --font-mono: 'JetBrains Mono', monospace;

        --page-bg:        #f4f5f7;
        --surface:        #ffffff;
        --surface-raised: #f8f9fa;
        --border:         rgba(0,0,0,0.08);
        --border-md:      rgba(0,0,0,0.14);

        --text-primary:   #0f1117;
        --text-secondary: #6b7280;

        --blue-fill:   #e6f1fb; --blue-text:   #185fa5; --blue-border:   rgba(24,95,165,0.25);
        --green-fill:  #eaf3de; --green-text:  #3b6d11; --green-border:  rgba(59,109,17,0.25);
        --red-fill:    #fcebeb; --red-text:    #a32d2d; --red-border:    rgba(163,45,45,0.25);
        --amber-fill:  #faeeda; --amber-text:  #854f0b; --amber-border:  rgba(133,79,11,0.25);
        --purple-fill: #eeedfe; --purple-text: #534ab7; --purple-border: rgba(83,74,183,0.25);

        --chart-grid:  rgba(0,0,0,0.06);
        --chart-tick:  #6b7280;
        --donut-border: #ffffff;

        --radius-sm: 8px;
        --radius-md: 10px;
        --radius-lg: 14px;
        --radius-xl: 18px;

        --toggle-track: #d1d5db;
        --toggle-thumb: #ffffff;

        color-scheme: light;
        transition: background 0.25s, color 0.25s;
    }

    /* ── Dark tokens ── */
    [data-theme="dark"] {
        --page-bg:        #0d0f12;
        --surface:        #161a1f;
        --surface-raised: #1e2329;
        --border:         rgba(255,255,255,0.07);
        --border-md:      rgba(255,255,255,0.13);

        --text-primary:   #f0f2f5;
        --text-secondary: #8b919a;

        --blue-fill:   rgba(24,95,165,0.18);  --blue-text:   #6aaee8; --blue-border:   rgba(106,174,232,0.25);
        --green-fill:  rgba(59,109,17,0.18);  --green-text:  #86c94a; --green-border:  rgba(134,201,74,0.25);
        --red-fill:    rgba(163,45,45,0.18);  --red-text:    #f07070; --red-border:    rgba(240,112,112,0.25);
        --amber-fill:  rgba(133,79,11,0.18);  --amber-text:  #f0a94a; --amber-border:  rgba(240,169,74,0.25);
        --purple-fill: rgba(83,74,183,0.18);  --purple-text: #a89cf5; --purple-border: rgba(168,156,245,0.25);

        --chart-grid:  rgba(255,255,255,0.05);
        --chart-tick:  #5a6270;
        --donut-border: #161a1f;

        --toggle-track: #3b5bdb;
        --toggle-thumb: #ffffff;

        color-scheme: dark;
    }

    /* ── Base ── */
    body {
        background: var(--page-bg) !important;
        font-family: var(--font-sans);
        color: var(--text-primary);
        font-size: 14px;
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        transition: background 0.25s, color 0.25s;
    }

    /* ── Theme Toggle ── */
    .theme-toggle {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        cursor: pointer;
        user-select: none;
    }
    .theme-toggle-track {
        width: 40px; height: 22px;
        background: var(--toggle-track);
        border-radius: 99px;
        position: relative;
        transition: background 0.25s;
        flex-shrink: 0;
    }
    .theme-toggle-thumb {
        position: absolute;
        top: 3px; left: 3px;
        width: 16px; height: 16px;
        background: var(--toggle-thumb);
        border-radius: 50%;
        transition: transform 0.25s cubic-bezier(0.4,0,0.2,1);
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    [data-theme="dark"] .theme-toggle-thumb {
        transform: translateX(18px);
    }
    .theme-toggle-icon {
        font-size: 15px;
        color: var(--text-secondary);
        line-height: 1;
    }

    /* ── Page Header ── */
    .db-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 28px 0 24px;
    }
    .db-header h1 {
        font-size: 22px;
        font-weight: 500;
        color: var(--text-primary);
        margin: 0 0 3px;
        letter-spacing: -0.01em;
    }
    .db-header p {
        font-size: 13px;
        color: var(--text-secondary);
        margin: 0;
    }
    .db-header-right {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: var(--text-primary);
        color: var(--page-bg);
        font-family: var(--font-sans);
        font-size: 13px;
        font-weight: 500;
        padding: 9px 18px;
        border-radius: var(--radius-md);
        border: none;
        text-decoration: none;
        transition: opacity 0.15s;
        letter-spacing: 0.01em;
    }
    .btn-export:hover { opacity: 0.8; color: var(--page-bg); }
    .btn-export i { font-size: 14px; }

    /* ── Stat Cards ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0,1fr));
        gap: 12px;
        margin-bottom: 16px;
    }
    .stat-card {
        background: var(--surface);
        border: 0.5px solid var(--border);
        border-radius: var(--radius-xl);
        padding: 20px 22px;
        position: relative;
        transition: border-color 0.2s, background 0.25s;
    }
    .stat-card:hover { border-color: var(--border-md); }
    .stat-icon {
        width: 40px; height: 40px;
        border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        font-size: 17px;
        margin-bottom: 16px;
        transition: background 0.25s;
    }
    .stat-icon.blue   { background: var(--blue-fill);   color: var(--blue-text); }
    .stat-icon.green  { background: var(--green-fill);  color: var(--green-text); }
    .stat-icon.red    { background: var(--red-fill);    color: var(--red-text); }
    .stat-icon.amber  { background: var(--amber-fill);  color: var(--amber-text); }
    .stat-value {
        font-family: var(--font-mono);
        font-size: 19px;
        font-weight: 500;
        color: var(--text-primary);
        letter-spacing: -0.02em;
        margin: 0 0 4px;
        line-height: 1.2;
    }
    .stat-label {
        font-size: 11px;
        font-weight: 500;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.07em;
    }
    .stat-chip {
        position: absolute; top: 18px; right: 18px;
        font-size: 11px; font-weight: 500;
        font-family: var(--font-mono);
        padding: 3px 9px; border-radius: 99px;
    }
    .stat-chip.up {
        background: var(--green-fill);
        color: var(--green-text);
        border: 0.5px solid var(--green-border);
    }

    /* ── Panels ── */
    .panel {
        background: var(--surface);
        border: 0.5px solid var(--border);
        border-radius: var(--radius-xl);
        overflow: hidden;
        transition: background 0.25s, border-color 0.25s;
    }
    .panel-head {
        display: flex; align-items: center; justify-content: space-between;
        padding: 15px 22px;
        border-bottom: 0.5px solid var(--border);
    }
    .panel-head-title {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; font-weight: 500; color: var(--text-primary);
    }
    .panel-head-title i { font-size: 15px; color: var(--text-secondary); }
    .panel-head-action {
        font-size: 13px; font-weight: 500;
        color: var(--blue-text); text-decoration: none;
    }
    .panel-head-action:hover { text-decoration: underline; }
    .panel-body { padding: 20px 22px; }

    /* ── Layout Rows ── */
    .row-2-1 {
        display: grid;
        grid-template-columns: minmax(0,1.9fr) minmax(0,1fr);
        gap: 12px; margin-bottom: 16px;
    }
    .row-1-2 {
        display: grid;
        grid-template-columns: minmax(0,1fr) minmax(0,1.9fr);
        gap: 12px; margin-bottom: 16px;
    }
    .row-equal {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px; margin-bottom: 16px;
    }

    /* ── Chart ── */
    .chart-wrap { height: 220px; position: relative; }
    .chart-legend { display: flex; gap: 16px; margin-bottom: 14px; }
    .chart-legend-item {
        display: flex; align-items: center; gap: 6px;
        font-size: 12px; color: var(--text-secondary);
    }
    .chart-legend-dot { width: 8px; height: 8px; border-radius: 3px; flex-shrink: 0; }
    .year-select {
        font-size: 12px;
        padding: 5px 10px;
        border: 0.5px solid var(--border-md);
        border-radius: var(--radius-sm);
        background: var(--surface-raised);
        color: var(--text-secondary);
        font-family: var(--font-mono);
        cursor: pointer;
    }
    .year-select:focus { outline: none; }

    /* ── Donut ── */
    .donut-wrap {
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        padding: 24px 22px; gap: 20px;
        height: calc(100% - 51px); box-sizing: border-box;
    }
    .fee-legend { width: 100%; display: flex; flex-direction: column; gap: 10px; }
    .fee-legend-row { display: flex; align-items: center; justify-content: space-between; }
    .fee-legend-left { display: flex; align-items: center; gap: 9px; font-size: 13px; color: var(--text-primary); }
    .fee-legend-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .fee-legend-val { font-family: var(--font-mono); font-size: 13px; font-weight: 500; }

    /* ── Quick Actions ── */
    .qa-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .quick-action {
        display: flex; align-items: center; gap: 12px;
        padding: 13px 14px;
        background: var(--surface-raised);
        border: 0.5px solid var(--border);
        border-radius: var(--radius-lg);
        text-decoration: none;
        color: var(--text-primary);
        font-size: 13px; font-weight: 500;
        transition: border-color 0.15s, background 0.15s;
    }
    .quick-action:hover { border-color: var(--border-md); color: var(--text-primary); }
    [data-theme="dark"] .quick-action:hover { background: rgba(255,255,255,0.04); }
    [data-theme="light"] .quick-action:hover { background: #eef0f4; }
    .qa-icon {
        width: 34px; height: 34px;
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; flex-shrink: 0;
    }
    .qa-icon.blue   { background: var(--blue-fill);   color: var(--blue-text); }
    .qa-icon.green  { background: var(--green-fill);  color: var(--green-text); }
    .qa-icon.amber  { background: var(--amber-fill);  color: var(--amber-text); }
    .qa-icon.purple { background: var(--purple-fill); color: var(--purple-text); }

    /* ── Tables ── */
    .tbl-wrap { overflow-x: auto; }
    .tbl { width: 100%; border-collapse: collapse; font-size: 13px; }
    .tbl th {
        padding: 11px 22px;
        text-align: left;
        font-size: 11px; font-weight: 500;
        color: var(--text-secondary);
        text-transform: uppercase; letter-spacing: 0.07em;
        border-bottom: 0.5px solid var(--border);
        background: var(--surface-raised);
        white-space: nowrap;
    }
    .tbl td {
        padding: 13px 22px;
        border-bottom: 0.5px solid var(--border);
        vertical-align: middle;
        color: var(--text-primary);
    }
    .tbl tbody tr:last-child td { border-bottom: none; }
    .tbl tbody tr:hover td { background: var(--surface-raised); }

    .avatar {
        width: 30px; height: 30px;
        border-radius: var(--radius-sm);
        background: var(--surface-raised);
        border: 0.5px solid var(--border-md);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 500;
        font-family: var(--font-mono);
        color: var(--text-secondary);
        flex-shrink: 0; margin-right: 10px;
    }
    .pill {
        display: inline-block;
        padding: 3px 10px; border-radius: 99px;
        font-size: 11px; font-weight: 500; white-space: nowrap;
    }
    .pill-neutral {
        background: var(--surface-raised);
        color: var(--text-secondary);
        border: 0.5px solid var(--border-md);
    }
    .pill-blue {
        background: var(--blue-fill);
        color: var(--blue-text);
        border: 0.5px solid var(--blue-border);
    }
    .tbl-amount-green { font-family: var(--font-mono); font-size: 13px; font-weight: 500; color: var(--green-text); }
    .tbl-amount-red   { font-family: var(--font-mono); font-size: 13px; font-weight: 500; color: var(--red-text); }
    .tbl-meta         { font-family: var(--font-mono); font-size: 12px; color: var(--text-secondary); }

    .btn-view {
        font-size: 12px; font-weight: 500;
        padding: 5px 13px;
        border-radius: var(--radius-sm);
        background: var(--surface-raised);
        border: 0.5px solid var(--border-md);
        color: var(--text-primary);
        text-decoration: none;
        transition: border-color 0.15s;
    }
    .btn-view:hover { border-color: var(--border-md); color: var(--text-primary); }

    /* ── Activity Feed ── */
    .activity-feed { padding: 0 22px; }
    .activity-item {
        display: flex; align-items: center; gap: 14px;
        padding: 13px 0;
        border-bottom: 0.5px solid var(--border);
    }
    .activity-item:last-child { border-bottom: none; }
    .act-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .act-desc { font-size: 13px; font-weight: 500; color: var(--text-primary); margin: 0 0 2px; }
    .act-meta { font-family: var(--font-mono); font-size: 11px; color: var(--text-secondary); }
    .act-amount { font-family: var(--font-mono); font-size: 13px; font-weight: 500; margin-left: auto; flex-shrink: 0; white-space: nowrap; }

    /* ── Responsive ── */
    @media (max-width: 1199px) {
        .stat-grid { grid-template-columns: repeat(2,1fr); }
        .row-2-1, .row-1-2 { grid-template-columns: 1fr; }
        .row-equal { grid-template-columns: 1fr; }
    }
    @media (max-width: 639px) {
        .stat-grid { grid-template-columns: 1fr; }
        .qa-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

{{-- ── Header ── --}}
<div class="db-header">
    <div>
        <h1>Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }} 👋</h1>
        <p>Here's what's happening in your school today.</p>
    </div>
    <div class="db-header-right">
        {{-- Theme Toggle --}}
        <label class="theme-toggle" title="Toggle theme" aria-label="Toggle dark mode">
            <i class="bi bi-sun theme-toggle-icon" id="toggleIconLight"></i>
            <div class="theme-toggle-track">
                <div class="theme-toggle-thumb"></div>
            </div>
            <i class="bi bi-moon theme-toggle-icon" id="toggleIconDark"></i>
        </label>
        <a href="{{ route('reports.index') }}" class="btn-export">
            <i class="bi bi-download"></i> Export report
        </a>
    </div>
</div>

{{-- ── Stats ── --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
        <div class="stat-value">{{ $totalStudents }}</div>
        <div class="stat-label">Total students</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-cash-stack"></i></div>
        <div class="stat-value">KSh {{ number_format($totalFeesCollected) }}</div>
        <div class="stat-label">Fees collected</div>
        <span class="stat-chip up">↑ This term</span>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="bi bi-exclamation-circle"></i></div>
        <div class="stat-value">KSh {{ number_format($totalOutstanding) }}</div>
        <div class="stat-label">Outstanding fees</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><i class="bi bi-wallet2"></i></div>
        <div class="stat-value">KSh {{ number_format($netBalance) }}</div>
        <div class="stat-label">Net balance</div>
    </div>
</div>

{{-- ── Charts Row ── --}}
<div class="row-2-1">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title">
                <i class="bi bi-bar-chart"></i> Monthly collections
            </div>
            <select class="year-select" id="chartYear">
                <option>{{ date('Y') }}</option>
                <option>{{ date('Y') - 1 }}</option>
            </select>
        </div>
        <div class="panel-body">
            <div class="chart-legend">
                <div class="chart-legend-item">
                    <div class="chart-legend-dot" style="background:#63a326"></div> Income
                </div>
                <div class="chart-legend-item">
                    <div class="chart-legend-dot" style="background:#e24b4a"></div> Expenses
                </div>
            </div>
            <div class="chart-wrap">
                <canvas id="collectionsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title">
                <i class="bi bi-pie-chart"></i> Fee status
            </div>
        </div>
        <div class="donut-wrap">
            <canvas id="statusChart" style="max-width:140px; max-height:140px;"></canvas>
            <div class="fee-legend">
                <div class="fee-legend-row">
                    <div class="fee-legend-left">
                        <div class="fee-legend-dot" style="background:#63a326"></div> Paid
                    </div>
                    <span class="fee-legend-val" style="color:var(--green-text)">{{ $paidPct ?? 0 }}%</span>
                </div>
                <div class="fee-legend-row">
                    <div class="fee-legend-left">
                        <div class="fee-legend-dot" style="background:#ef9f27"></div> Partial
                    </div>
                    <span class="fee-legend-val" style="color:var(--amber-text)">{{ $partialPct ?? 0 }}%</span>
                </div>
                <div class="fee-legend-row">
                    <div class="fee-legend-left">
                        <div class="fee-legend-dot" style="background:#e24b4a"></div> Unpaid
                    </div>
                    <span class="fee-legend-val" style="color:var(--red-text)">{{ $unpaidPct ?? 0 }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Quick Actions + Recent Payments ── --}}
<div class="row-1-2">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title">
                <i class="bi bi-lightning-charge"></i> Quick actions
            </div>
        </div>
        <div class="panel-body">
            <div class="qa-grid">
                <a href="{{ route('students.create') }}" class="quick-action">
                    <div class="qa-icon blue"><i class="bi bi-person-plus"></i></div>
                    Add student
                </a>
                <a href="{{ route('fee-payments.create') }}" class="quick-action">
                    <div class="qa-icon green"><i class="bi bi-credit-card"></i></div>
                    Record payment
                </a>
                <a href="{{ route('transactions.create') }}" class="quick-action">
                    <div class="qa-icon amber"><i class="bi bi-receipt"></i></div>
                    Add transaction
                </a>
                <a href="{{ route('reports.index') }}" class="quick-action">
                    <div class="qa-icon purple"><i class="bi bi-file-earmark-bar-graph"></i></div>
                    View reports
                </a>
            </div>
        </div>
    </div>
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title">
                <i class="bi bi-clock-history"></i> Recent payments
            </div>
            <a href="{{ route('fee-payments.index') }}" class="panel-head-action">View all →</a>
        </div>
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments as $p)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center;">
                                <span class="avatar">{{ strtoupper(substr($p->student->name ?? '?', 0, 2)) }}</span>
                                <span style="font-weight:500;">{{ $p->student->name ?? '—' }}</span>
                            </div>
                        </td>
                        <td class="tbl-amount-green">KSh {{ number_format($p->amount_paid) }}</td>
                        <td><span class="pill pill-neutral">{{ $p->payment_method_label }}</span></td>
                        <td class="tbl-meta">{{ $p->payment_date?->format('d M Y') }}</td>
                        <td class="tbl-meta">{{ $p->receipt_number }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:32px; color:var(--text-secondary);">
                            No payments recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── Defaulters + Transactions ── --}}
<div class="row-equal">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title">
                <i class="bi bi-exclamation-triangle" style="color:var(--amber-text)"></i> Top defaulters
            </div>
        </div>
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Grade</th>
                        <th>Outstanding</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topDefaulters as $s)
                    <tr>
                        <td style="font-weight:500;">{{ $s->name }}</td>
                        <td><span class="pill pill-blue">Grade {{ $s->grade }}</span></td>
                        <td class="tbl-amount-red">KSh {{ number_format($s->balance_due) }}</td>
                        <td><a href="{{ route('students.show', $s) }}" class="btn-view">View</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:32px; color:var(--text-secondary);">
                            No defaulters. 🎉
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title">
                <i class="bi bi-arrow-left-right"></i> Recent transactions
            </div>
            <a href="{{ route('transactions.index') }}" class="panel-head-action">View all →</a>
        </div>
        <div class="activity-feed">
            @forelse($recentTransactions as $t)
            <div class="activity-item">
                <div class="act-dot" style="background:{{ $t->type === 'income' ? '#63a326' : '#e24b4a' }}"></div>
                <div style="flex:1; min-width:0;">
                    <div class="act-desc">{{ $t->description }}</div>
                    <div class="act-meta">{{ $t->created_at?->format('d M Y') }} · {{ $t->category }}</div>
                </div>
                <div class="act-amount" style="color:{{ $t->type === 'income' ? 'var(--green-text)' : 'var(--red-text)' }}">
                    {{ $t->type === 'income' ? '+' : '-' }}KSh {{ number_format($t->amount) }}
                </div>
            </div>
            @empty
            <p style="text-align:center; padding:32px 0; color:var(--text-secondary); font-size:13px;">
                No transactions yet.
            </p>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const cashflow = @json($cashflow);
    const ROOT     = document.documentElement;

    /* ── Theme persistence ── */
    const saved = localStorage.getItem('db-theme') || 'light';
    ROOT.setAttribute('data-theme', saved);

    document.querySelector('.theme-toggle').addEventListener('click', function () {
        const next = ROOT.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        ROOT.setAttribute('data-theme', next);
        localStorage.setItem('db-theme', next);
        rebuildCharts();
    });

    /* ── Chart helpers ── */
    function chartColors() {
        const dark = ROOT.getAttribute('data-theme') === 'dark';
        return {
            grid:   dark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.06)',
            tick:   dark ? '#5a6270' : '#6b7280',
            border: dark ? '#161a1f' : '#ffffff',
        };
    }

    let barChart   = null;
    let donutChart = null;

    function buildBarChart() {
        const ctx = document.getElementById('collectionsChart');
        if (!ctx) return;
        const c = chartColors();
        const font = { family: 'JetBrains Mono', size: 10, weight: '500' };
        barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                datasets: [
                    {
                        label: 'Income',
                        data: Object.keys(cashflow).map(k => cashflow[k].income),
                        backgroundColor: '#63a326',
                        borderRadius: 5, maxBarThickness: 12,
                    },
                    {
                        label: 'Expenses',
                        data: Object.keys(cashflow).map(k => cashflow[k].expenses),
                        backgroundColor: '#e24b4a',
                        borderRadius: 5, maxBarThickness: 12,
                    },
                ],
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        grid: { color: c.grid, drawTicks: false },
                        border: { display: false },
                        ticks: { color: c.tick, font, callback: v => v.toLocaleString(), maxTicksLimit: 5 },
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: c.tick, font },
                    },
                },
            },
        });
    }

    function buildDonutChart() {
    const ctx = document.getElementById('statusChart');
    if (!ctx) return;

    const c = chartColors();

    const paid = {{ $paidPct ?? 0 }};
    const partial = {{ $partialPct ?? 0 }};
    const unpaid = {{ $unpaidPct ?? 0 }};

    console.log("Doughnut Data:", { paid, partial, unpaid });

    donutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Paid', 'Partial', 'Unpaid'],
            datasets: [{
                data: [paid, partial, unpaid],
                backgroundColor: ['#63a326', '#ef9f27', '#e24b4a'],
                borderWidth: 3,
                borderColor: c.border,
                hoverOffset: 6,
            }],
        },
        options: {
            cutout: '78%',
            plugins: {
                legend: { display: false }
            }
        },
    });
}

    function rebuildCharts() {
        if (barChart)   { barChart.destroy();   barChart   = null; }
        if (donutChart) { donutChart.destroy();  donutChart = null; }
        buildBarChart();
        buildDonutChart();
    }

    buildBarChart();
    buildDonutChart();
})();
</script>
@endpush