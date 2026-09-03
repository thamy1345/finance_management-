@extends('layouts.admin')
@section('title','Transactions')
@section('page-title','Transactions')

@push('styles')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=JetBrains+Mono:wght@300;400;500&display=swap');

  :root {
    --ink:        #0a0a0f;
    --ink-2:      #14141c;
    --ink-3:      #1e1e2a;
    --surface:    #161620;
    --border:     rgba(255,255,255,0.07);
    --border-hi:  rgba(255,255,255,0.14);
    --text:       #e8e8f0;
    --muted:      #6b6b80;
    --faint:      #2e2e3d;

    --green:      #00e5a0;
    --green-dim:  rgba(0,229,160,0.12);
    --green-glow: rgba(0,229,160,0.25);
    --red:        #ff4d6a;
    --red-dim:    rgba(255,77,106,0.12);
    --amber:      #ffb830;
    --amber-dim:  rgba(255,184,48,0.12);
    --blue:       #4d9fff;
    --blue-dim:   rgba(77,159,255,0.10);

    --radius:     12px;
    --radius-sm:  8px;
    --font:       'Syne', sans-serif;
    --mono:       'JetBrains Mono', monospace;
  }

  .txn-shell *, .txn-shell *::before, .txn-shell *::after { box-sizing: border-box; margin: 0; padding: 0; }
  .txn-shell { font-family: var(--font); color: var(--text); }

  /* ── Page Header ── */
  .txn-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 28px; gap: 16px; flex-wrap: wrap;
  }
  .txn-header-left { display: flex; flex-direction: column; gap: 4px; }
  .txn-header-eyebrow {
    font-family: var(--mono); font-size: 10px; letter-spacing: .18em;
    text-transform: uppercase; color: var(--muted);
  }
  .txn-header-title {
    font-size: 28px; font-weight: 800; line-height: 1;
    background: linear-gradient(135deg, var(--text) 30%, var(--muted) 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
  }
  .txn-header-actions { display: flex; gap: 10px; flex-wrap: wrap; }

  /* ── Buttons ── */
  .btn-ghost, .btn-income, .btn-expense {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: var(--radius-sm);
    font-family: var(--font); font-size: 13px; font-weight: 600;
    cursor: pointer; text-decoration: none; border: 1px solid transparent;
    transition: all .18s ease; white-space: nowrap;
  }
  .btn-ghost   { background: var(--faint); color: var(--text); border-color: var(--border-hi); }
  .btn-ghost:hover { background: #363648; border-color: rgba(255,255,255,.22); }
  .btn-income  { background: var(--green-dim); color: var(--green); border-color: rgba(0,229,160,.25); }
  .btn-income:hover  { background: rgba(0,229,160,.2); box-shadow: 0 0 14px var(--green-glow); }
  .btn-expense { background: var(--red-dim); color: var(--red); border-color: rgba(255,77,106,.25); }
  .btn-expense:hover { background: rgba(255,77,106,.2); box-shadow: 0 0 14px rgba(255,77,106,.3); }
  .btn-sm   { padding: 6px 11px; font-size: 12px; border-radius: 6px; }
  .btn-icon { padding: 7px; }
  .btn-filter {
    background: var(--blue); color: #fff; border-color: var(--blue);
    padding: 9px 20px; border-radius: var(--radius-sm);
    font-family: var(--font); font-size: 13px; font-weight: 700;
    cursor: pointer; display: inline-flex; align-items: center; gap: 7px;
    transition: all .18s ease; white-space: nowrap; border: none;
  }
  .btn-filter:hover { background: #6dafff; box-shadow: 0 0 16px rgba(77,159,255,.4); }
  .btn-clear {
    background: transparent; color: var(--muted); border: 1px solid var(--border);
    padding: 9px 16px; border-radius: var(--radius-sm);
    font-family: var(--font); font-size: 13px; font-weight: 500;
    cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
    transition: all .18s ease;
  }
  .btn-clear:hover { color: var(--text); border-color: var(--border-hi); }

  /* ── Filter Bar ── */
  .filter-shell {
    background: var(--ink-2); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 18px; margin-bottom: 24px;
    display: grid; grid-template-columns: 2fr repeat(4,1fr) 1fr;
    gap: 10px; align-items: end; position: relative; overflow: hidden;
  }
  .filter-shell::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(77,159,255,.5), transparent);
  }
  .filter-actions { display: flex; gap: 8px; align-items: center; }
  .filter-group   { display: flex; flex-direction: column; gap: 6px; }
  .filter-label {
    font-family: var(--mono); font-size: 10px; letter-spacing: .12em;
    text-transform: uppercase; color: var(--muted);
  }
  .filter-input {
    width: 100%; background: var(--ink-3); color: var(--text);
    border: 1px solid var(--border); border-radius: var(--radius-sm);
    padding: 8px 12px; font-family: var(--font); font-size: 13px;
    transition: border-color .15s, box-shadow .15s; outline: none;
    -webkit-appearance: none; appearance: none;
  }
  .filter-input:focus {
    border-color: rgba(77,159,255,.5);
    box-shadow: 0 0 0 3px rgba(77,159,255,.1);
  }
  .filter-input::placeholder { color: var(--muted); }
  select.filter-input {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b6b80' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center; padding-right: 30px; cursor: pointer;
  }
  optgroup { color: var(--muted); font-size: 11px; }
  option   { background: var(--ink-3); color: var(--text); }

  /* ── Metric Tiles ── */
  .metrics-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; margin-bottom: 24px; }
  .metric-card {
    background: var(--ink-2); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 22px 24px;
    position: relative; overflow: hidden; transition: transform .2s, border-color .2s;
  }
  .metric-card:hover { transform: translateY(-2px); border-color: var(--border-hi); }
  .metric-card::after {
    content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2px;
  }
  .metric-card.green::after   { background: linear-gradient(90deg, transparent, var(--green), transparent); }
  .metric-card.red::after     { background: linear-gradient(90deg, transparent, var(--red), transparent); }
  .metric-card.neutral::after { background: linear-gradient(90deg, transparent, var(--blue), transparent); }
  .metric-glow {
    position: absolute; top: -40px; right: -40px; width: 100px; height: 100px;
    border-radius: 50%; opacity: .15; pointer-events: none;
  }
  .metric-card.green   .metric-glow { background: var(--green); }
  .metric-card.red     .metric-glow { background: var(--red); }
  .metric-card.neutral .metric-glow { background: var(--blue); }
  .metric-top { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
  .metric-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; font-size: 17px; flex-shrink: 0;
  }
  .metric-card.green   .metric-icon { background: var(--green-dim); color: var(--green); }
  .metric-card.red     .metric-icon { background: var(--red-dim);   color: var(--red); }
  .metric-card.neutral .metric-icon { background: var(--blue-dim);  color: var(--blue); }
  .metric-label {
    font-size: 11px; font-weight: 600; letter-spacing: .06em;
    text-transform: uppercase; color: var(--muted);
  }
  .metric-value { font-size: 26px; font-weight: 800; line-height: 1; letter-spacing: -.02em; font-variant-numeric: tabular-nums; }
  .metric-card.green   .metric-value { color: var(--green); }
  .metric-card.red     .metric-value { color: var(--red); }
  .metric-card.neutral .metric-value { color: var(--text); }
  .metric-sub { margin-top: 6px; font-family: var(--mono); font-size: 11px; display: inline-flex; align-items: center; gap: 4px; }
  .metric-sub.up   { color: var(--green); }
  .metric-sub.down { color: var(--red); }

  /* ── Table Card ── */
  .table-card { background: var(--ink-2); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; }
  .table-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 24px; border-bottom: 1px solid var(--border); gap: 12px; flex-wrap: wrap;
  }
  .table-card-title {
    font-size: 13px; font-weight: 700; letter-spacing: .04em;
    text-transform: uppercase; color: var(--muted); display: flex; align-items: center; gap: 8px;
  }
  .table-count {
    background: var(--faint); color: var(--text); border-radius: 20px;
    padding: 2px 10px; font-family: var(--mono); font-size: 11px; font-weight: 400;
  }

  /* ── Table ── */
  .txn-table { width: 100%; border-collapse: collapse; }
  .txn-table thead th {
    padding: 12px 16px; font-family: var(--mono); font-size: 10px; letter-spacing: .14em;
    text-transform: uppercase; color: var(--muted); font-weight: 500;
    text-align: left; border-bottom: 1px solid var(--border); white-space: nowrap;
  }
  .txn-table thead th:first-child { padding-left: 24px; }
  .txn-table thead th:last-child  { padding-right: 24px; text-align: right; }
  .txn-table tbody tr { border-bottom: 1px solid rgba(255,255,255,.04); transition: background .12s; }
  .txn-table tbody tr:last-child  { border-bottom: none; }
  .txn-table tbody tr:hover { background: rgba(255,255,255,.03); }
  .txn-table td { padding: 14px 16px; vertical-align: middle; font-size: 13px; }
  .txn-table td:first-child { padding-left: 24px; }
  .txn-table td:last-child  { padding-right: 24px; }

  .td-date   { font-family: var(--mono); font-size: 12px; color: var(--muted); white-space: nowrap; }
  .td-desc   { font-weight: 600; font-size: 13px; max-width: 200px; }
  .td-desc small { display: block; font-size: 11px; color: var(--muted); font-weight: 400; margin-top: 2px; font-family: var(--mono); }
  .td-amount { font-weight: 700; font-size: 14px; font-variant-numeric: tabular-nums; white-space: nowrap; }
  .td-amount.income  { color: var(--green); }
  .td-amount.expense { color: var(--red); }
  .td-method { font-size: 11px; color: var(--muted); font-family: var(--mono); white-space: nowrap; }
  .td-ref    { font-family: var(--mono); font-size: 11px; color: var(--muted); }
  .td-actions { text-align: right; }
  .action-group { display: inline-flex; gap: 6px; align-items: center; }

  /* ── Badges ── */
  .badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;
    letter-spacing: .03em; white-space: nowrap;
  }
  .badge-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
  .badge-income    { background: var(--green-dim); color: var(--green); }
  .badge-expense   { background: var(--red-dim);   color: var(--red); }
  .badge-neutral   { background: var(--faint);     color: var(--muted); border: 1px solid var(--border); }
  .badge-success   { background: var(--green-dim); color: var(--green); }
  .badge-pending   { background: var(--amber-dim); color: var(--amber); }
  .badge-cancelled { background: var(--faint);     color: var(--muted); }

  /* ── Flash message ── */
  .flash-success {
    display: flex; align-items: center; gap: 10px;
    background: var(--green-dim); border: 1px solid rgba(0,229,160,.3);
    color: var(--green); border-radius: var(--radius-sm);
    padding: 12px 18px; margin-bottom: 20px;
    font-size: 13px; font-weight: 600;
  }

  /* ── Empty State ── */
  .empty-state {
    padding: 64px 24px; text-align: center; color: var(--muted);
    display: flex; flex-direction: column; align-items: center; gap: 12px;
  }
  .empty-icon {
    width: 56px; height: 56px; border-radius: 16px; background: var(--faint);
    display: flex; align-items: center; justify-content: center; font-size: 24px; color: var(--muted); margin-bottom: 4px;
  }
  .empty-title { font-size: 15px; font-weight: 700; color: var(--text); }
  .empty-sub   { font-size: 13px; max-width: 280px; line-height: 1.6; }

  /* ── Pagination ── */
  .pagination-shell { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; }
  .pagination-shell nav { display: flex; align-items: center; gap: 4px; }
  .pagination-shell .pagination { display: flex; gap: 4px; list-style: none; }
  .pagination-shell .page-item .page-link,
  .pagination-shell .page-item span {
    display: flex; align-items: center; justify-content: center;
    min-width: 32px; height: 32px; padding: 0 10px;
    background: var(--faint); color: var(--muted); border: 1px solid var(--border);
    border-radius: 6px; font-family: var(--mono); font-size: 12px;
    text-decoration: none; transition: all .15s;
  }
  .pagination-shell .page-item.active .page-link,
  .pagination-shell .page-item.active span { background: var(--blue); color: #fff; border-color: var(--blue); }
  .pagination-shell .page-item .page-link:hover { background: var(--ink-3); color: var(--text); border-color: var(--border-hi); }

  /* ── Animations ── */
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .animate-in { animation: fadeInUp .35s ease both; }
  .delay-1 { animation-delay: .06s; }
  .delay-2 { animation-delay: .12s; }
  .delay-3 { animation-delay: .18s; }

  .action-form { display: inline; }

  @media (max-width: 1200px) {
    .filter-shell { grid-template-columns: 1fr 1fr 1fr; }
    .filter-actions { grid-column: span 3; }
  }
  @media (max-width: 900px) {
    .metrics-row { grid-template-columns: 1fr; }
    .filter-shell { grid-template-columns: 1fr 1fr; }
    .filter-actions { grid-column: span 2; }
    .txn-table td, .txn-table th { font-size: 12px; padding: 10px; }
  }
  @media (max-width: 640px) {
    .filter-shell { grid-template-columns: 1fr; }
    .filter-actions { grid-column: span 1; }
    .txn-header { flex-direction: column; align-items: flex-start; }
  }
</style>
@endpush

@section('content')
<div class="txn-shell">

  {{-- Flash ── --}}
  @if(session('success'))
  <div class="flash-success animate-in">
    <i class="ti ti-circle-check" style="font-size:18px;flex-shrink:0"></i>
    {{ session('success') }}
  </div>
  @endif

  {{-- ══ HEADER ══ --}}
  <div class="txn-header animate-in">
    <div class="txn-header-left">
      <span class="txn-header-eyebrow">
        <i class="ti ti-arrows-exchange"></i>&nbsp; Financial Ledger
      </span>
      <h1 class="txn-header-title">
        {{ request('type') === 'expense' ? 'Expenses'
           : (request('type') === 'income'  ? 'Income'
           : 'All Transactions') }}
      </h1>
    </div>
    <div class="txn-header-actions">
      <a href="{{ route('transactions.create') }}?type=income"  class="btn-income">
        <i class="ti ti-trending-up"></i> Add Income
      </a>
      <a href="{{ route('transactions.create') }}?type=expense" class="btn-expense">
        <i class="ti ti-trending-down"></i> Add Expense
      </a>
    </div>
  </div>

  {{-- ══ FILTER BAR ══ --}}
  <form method="GET" class="filter-shell animate-in delay-1">

    <div class="filter-group" style="grid-column:span 1">
      <label class="filter-label">Search</label>
      <input type="text" name="search" class="filter-input"
             placeholder="Description or reference…"
             value="{{ request('search') }}">
    </div>

    <div class="filter-group">
      <label class="filter-label">Type</label>
      <select name="type" class="filter-input">
        <option value="">All Types</option>
        <option value="income"  {{ request('type')==='income'  ?'selected':'' }}>Income</option>
        <option value="expense" {{ request('type')==='expense' ?'selected':'' }}>Expense</option>
      </select>
    </div>

    <div class="filter-group">
      <label class="filter-label">Category</label>
      <select name="category" class="filter-input">
        <option value="">All Categories</option>
        <optgroup label="─ Income ─">
          @foreach($incomeCategories as $c)
            <option value="{{ $c }}" {{ request('category')===$c?'selected':'' }}>{{ $c }}</option>
          @endforeach
        </optgroup>
        <optgroup label="─ Expense ─">
          @foreach($expenseCategories as $c)
            <option value="{{ $c }}" {{ request('category')===$c?'selected':'' }}>{{ $c }}</option>
          @endforeach
        </optgroup>
      </select>
    </div>

    <div class="filter-group">
      <label class="filter-label">Term</label>
      <select name="term" class="filter-input">
        <option value="">All Terms</option>
        @foreach(['Term 1','Term 2','Term 3'] as $t)
          <option value="{{ $t }}" {{ request('term')===$t?'selected':'' }}>{{ $t }}</option>
        @endforeach
      </select>
    </div>

    {{-- ✅ FIX: sends "2025/2026" to match what's stored in academic_year column --}}
    <div class="filter-group">
      <label class="filter-label">Academic Year</label>
      <select name="year" class="filter-input">
        @foreach(range(date('Y'), date('Y') - 3) as $y)
          @php $label = $y . '/' . ($y + 1); @endphp
          <option value="{{ $label }}" {{ $year === $label ? 'selected' : '' }}>
            {{ $label }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="filter-group">
      <label class="filter-label">Status</label>
      <select name="status" class="filter-input">
        <option value="">All Status</option>
        <option value="completed" {{ request('status')==='completed'?'selected':'' }}>Completed</option>
        <option value="pending"   {{ request('status')==='pending'  ?'selected':'' }}>Pending</option>
        <option value="cancelled" {{ request('status')==='cancelled'?'selected':'' }}>Cancelled</option>
      </select>
    </div>

    <div class="filter-group">
      <label class="filter-label">&nbsp;</label>
      <div class="filter-actions">
        <button type="submit" class="btn-filter">
          <i class="ti ti-search"></i> Filter
        </button>
        <a href="{{ route('transactions.index') }}" class="btn-clear">
          <i class="ti ti-x"></i> Clear
        </a>
      </div>
    </div>

  </form>

  {{-- ══ METRICS ══ --}}
  <div class="metrics-row animate-in delay-2">

    <div class="metric-card green">
      <div class="metric-glow"></div>
      <div class="metric-top">
        <div class="metric-icon"><i class="ti ti-trending-up"></i></div>
        <div class="metric-label">Total Income</div>
      </div>
      <div class="metric-value">KES {{ number_format($income) }}</div>
      <div class="metric-sub up"><i class="ti ti-arrow-up" style="font-size:11px"></i> Revenue</div>
    </div>

    <div class="metric-card red">
      <div class="metric-glow"></div>
      <div class="metric-top">
        <div class="metric-icon"><i class="ti ti-trending-down"></i></div>
        <div class="metric-label">Total Expenses</div>
      </div>
      <div class="metric-value">KES {{ number_format($expenses) }}</div>
      <div class="metric-sub down"><i class="ti ti-arrow-down" style="font-size:11px"></i> Outflow</div>
    </div>

    <div class="metric-card neutral">
      <div class="metric-glow"></div>
      <div class="metric-top">
        <div class="metric-icon"><i class="ti ti-wallet"></i></div>
        <div class="metric-label">Net Balance</div>
      </div>
      <div class="metric-value" style="color:{{ $balance >= 0 ? 'var(--green)' : 'var(--red)' }}">
        {{ $balance < 0 ? '−' : '' }}KES {{ number_format(abs($balance)) }}
      </div>
      <div class="metric-sub {{ $balance >= 0 ? 'up' : 'down' }}">
        <i class="ti ti-{{ $balance >= 0 ? 'circle-check' : 'alert-circle' }}" style="font-size:11px"></i>
        {{ $balance >= 0 ? 'Surplus' : 'Deficit' }}
      </div>
    </div>

  </div>

  {{-- ══ TABLE ══ --}}
  <div class="table-card animate-in delay-3">

    <div class="table-card-header">
      <div class="table-card-title">
        <i class="ti ti-list-details"></i>
        Ledger Entries
        <span class="table-count">{{ $transactions->total() }} records</span>
      </div>
      <div style="font-family:var(--mono);font-size:11px;color:var(--muted)">
        {{ $year }}
      </div>
    </div>

    <div style="overflow-x:auto">
      <table class="txn-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Description</th>
            <th>Category</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Status</th>
            <th>Reference</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($transactions as $t)
          <tr>

            <td class="td-date">
              {{ $t->transaction_date->format('d M') }}<br>
              <span style="font-size:10px;opacity:.55">{{ $t->transaction_date->format('Y') }}</span>
            </td>

            <td class="td-desc">
              {{ $t->description }}
              @if($t->notes)
                <small>{{ Str::limit($t->notes, 40) }}</small>
              @endif
            </td>

            <td>
              <span class="badge badge-neutral">{{ $t->category }}</span>
            </td>

            <td>
              @if($t->type === 'income')
                <span class="badge badge-income">
                  <span class="badge-dot" style="background:var(--green)"></span>Income
                </span>
              @else
                <span class="badge badge-expense">
                  <span class="badge-dot" style="background:var(--red)"></span>Expense
                </span>
              @endif
            </td>

            <td class="td-amount {{ $t->type === 'income' ? 'income' : 'expense' }}">
              {{ $t->type === 'income' ? '+' : '−' }}&nbsp;KES {{ number_format($t->amount) }}
            </td>

            <td class="td-method">
              {{ ucwords(str_replace('_', ' ', $t->payment_method)) }}
            </td>

            <td>
              @if($t->status === 'completed')
                <span class="badge badge-success">
                  <span class="badge-dot" style="background:var(--green)"></span>Completed
                </span>
              @elseif($t->status === 'pending')
                <span class="badge badge-pending">
                  <span class="badge-dot" style="background:var(--amber)"></span>Pending
                </span>
              @else
                <span class="badge badge-cancelled">
                  <span class="badge-dot" style="background:var(--muted)"></span>Cancelled
                </span>
              @endif
            </td>

            <td class="td-ref">{{ $t->reference_number ?? '—' }}</td>

            <td class="td-actions">
              <div class="action-group">
                <a href="{{ route('transactions.edit', $t) }}"
                   class="btn-ghost btn-sm btn-icon" title="Edit">
                  <i class="ti ti-edit"></i>
                </a>
                <form class="action-form" method="POST"
                      action="{{ route('transactions.destroy', $t) }}"
                      onsubmit="return confirm('Delete this transaction?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn-expense btn-sm btn-icon" title="Delete">
                    <i class="ti ti-trash"></i>
                  </button>
                </form>
              </div>
            </td>

          </tr>
          @empty
          <tr>
            <td colspan="9">
              <div class="empty-state">
                <div class="empty-icon"><i class="ti ti-arrows-exchange"></i></div>
                <div class="empty-title">No transactions found</div>
                <div class="empty-sub">No records for {{ $year }}. Try a different year or add a new entry.</div>
                <div style="display:flex;gap:10px;margin-top:8px">
                  <a href="{{ route('transactions.create') }}?type=income"  class="btn-income">
                    <i class="ti ti-plus"></i> Add Income
                  </a>
                  <a href="{{ route('transactions.create') }}?type=expense" class="btn-expense">
                    <i class="ti ti-plus"></i> Add Expense
                  </a>
                </div>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($transactions->hasPages())
    <div class="pagination-shell">
      {{ $transactions->links() }}
    </div>
    @endif

  </div>

</div>
@endsection