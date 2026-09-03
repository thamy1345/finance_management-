
@extends('layouts.admin')
@section('title', 'Fee Structures')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════════════════════════
   DESIGN TOKENS  — Dark Institutional Luxury
═══════════════════════════════════════════════════════════════ */
:root {
    --ink:         #0c1225;
    --ink-2:       #111b35;
    --ink-3:       #1a2644;
    --ink-4:       #243058;
    --veil:        rgba(255,255,255,.04);
    --veil-2:      rgba(255,255,255,.07);
    --line:        rgba(255,255,255,.08);
    --line-2:      rgba(255,255,255,.13);
    --text:        #2dd4bf;
    --text-2:      #2dd4bf;
    --text-3:      #2dd4bf;
    --gold:        #2dd4bf;
    --gold-lt:     #2dd4bf;
    --gold-dk:     #2dd4bf;
    --gold-glow:   rgba(201,168,76,.18);
    --gold-veil:   rgba(201,168,76,.08);
    --teal:        #2dd4bf;
    --teal-lt:     rgba(45,212,191,.12);
    --red:         #f87171;
    --red-lt:      rgba(248,113,113,.12);
    --green:       #34d399;
    --green-lt:    rgba(52,211,153,.12);
    --r:           14px;
    --r-sm:        9px;
    --r-xs:        6px;
    --sh:          0 4px 24px rgba(0,0,0,.35);
    --sh-lg:       0 8px 48px rgba(0,0,0,.5);
    --font-serif:  'Playfair Display', Georgia, serif;
    --font-sans:   'Plus Jakarta Sans', sans-serif;
    --font-mono:   'JetBrains Mono', monospace;
    --dur:         .22s;
}

/* ═══════════════════════════════════════════════════════════════
   BASE OVERRIDES
═══════════════════════════════════════════════════════════════ */
body { background: var(--ink); font-family: var(--font-sans); color: var(--text); }

/* ═══════════════════════════════════════════════════════════════
   PAGE WRAPPER
═══════════════════════════════════════════════════════════════ */
.fs-page {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 0 5rem;
}
.fs-page > * { animation: fs-rise .45s cubic-bezier(.16,1,.3,1) both; }
.fs-page > *:nth-child(1) { animation-delay: .00s; }
.fs-page > *:nth-child(2) { animation-delay: .05s; }
.fs-page > *:nth-child(3) { animation-delay: .10s; }
.fs-page > *:nth-child(4) { animation-delay: .15s; }
.fs-page > *:nth-child(n+5) { animation-delay: .20s; }

@keyframes fs-rise {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ═══════════════════════════════════════════════════════════════
   PAGE HEADER
═══════════════════════════════════════════════════════════════ */
.fs-header {
    display: flex; flex-wrap: wrap;
    align-items: flex-start; justify-content: space-between;
    gap: 1.25rem; margin-bottom: 2rem;
    padding-bottom: 1.75rem;
    border-bottom: 1px solid var(--line);
    position: relative;
}
.fs-header::after {
    content: '';
    position: absolute; bottom: -1px; left: 0;
    width: 80px; height: 2px;
    background: linear-gradient(90deg, var(--gold), transparent);
}

.fs-title-block {}
.fs-eyebrow {
    display: flex; align-items: center; gap: .5rem;
    font-size: .68rem; font-weight: 600; letter-spacing: .16em;
    text-transform: uppercase; color: var(--gold);
    margin-bottom: .5rem;
}
.fs-eyebrow-line {
    width: 24px; height: 1.5px;
    background: linear-gradient(90deg, var(--gold), transparent);
}
.fs-h1 {
    font-family: var(--font-serif);
    font-size: 2rem; font-weight: 700;
    color: var(--text); letter-spacing: -.01em; line-height: 1.15;
    margin: 0 0 .5rem;
}
.fs-breadcrumb {
    display: flex; align-items: center; gap: .35rem;
    font-size: .75rem; color: var(--text-3); list-style: none;
    padding: 0; margin: 0;
}
.fs-breadcrumb a { color: var(--text-3); text-decoration: none; transition: color var(--dur); }
.fs-breadcrumb a:hover { color: var(--gold); }
.fs-breadcrumb-sep { opacity: .4; font-size: .65rem; }
.fs-breadcrumb .active { color: var(--text-2); }

/* Primary CTA */
.fs-btn-primary {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .65rem 1.35rem;
    font-family: var(--font-sans); font-size: .82rem; font-weight: 600;
    color: var(--ink); background: linear-gradient(135deg, var(--gold-lt) 0%, var(--gold) 100%);
    border: none; border-radius: var(--r-sm); cursor: pointer; text-decoration: none;
    box-shadow: 0 2px 12px var(--gold-glow), inset 0 1px 0 rgba(255,255,255,.2);
    transition: all var(--dur); white-space: nowrap; letter-spacing: .01em;
}
.fs-btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 20px var(--gold-glow), inset 0 1px 0 rgba(255,255,255,.25);
    color: var(--ink);
}
.fs-btn-primary:active { transform: translateY(0); }
.fs-btn-primary svg, .fs-btn-primary i { font-size: .9rem; }

/* ═══════════════════════════════════════════════════════════════
   FILTER STRIP + BULK COPY  — glassmorphism panels
═══════════════════════════════════════════════════════════════ */
.fs-panel {
    background: var(--veil);
    border: 1px solid var(--line);
    border-radius: var(--r);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    margin-bottom: 1.25rem;
    overflow: hidden;
    transition: border-color var(--dur), box-shadow var(--dur);
}
.fs-panel:hover {
    border-color: var(--line-2);
    box-shadow: var(--sh);
}

.fs-panel-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: .9rem 1.4rem;
    border-bottom: 1px solid var(--line);
    background: var(--veil);
}
.fs-panel-title {
    display: flex; align-items: center; gap: .5rem;
    font-size: .72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .1em; color: var(--text-2);
}
.fs-panel-title i { color: var(--gold); font-size: .9rem; }

.fs-panel-body { padding: 1.25rem 1.4rem; }

/* Form controls */
.fs-label {
    display: block;
    font-size: .7rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .09em; color: var(--text-3); margin-bottom: .45rem;
}
.fs-select, .fs-input {
    background: var(--ink-3); color: var(--text);
    border: 1.5px solid var(--line-2); border-radius: var(--r-xs);
    font-family: var(--font-sans); font-size: .84rem; font-weight: 500;
    padding: .55rem .85rem;
    transition: border-color var(--dur), box-shadow var(--dur);
    outline: none; appearance: none; -webkit-appearance: none;
    cursor: pointer;
}
.fs-select:focus, .fs-input:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px var(--gold-veil);
}
.fs-select option { background: var(--ink-3); color: var(--text); }

.fs-select-wrap { position: relative; display: inline-flex; align-items: center; }
.fs-select-wrap::after {
    content: '';
    position: absolute; right: .8rem; top: 50%; transform: translateY(-60%);
    pointer-events: none;
    border-left: 4px solid transparent; border-right: 4px solid transparent;
    border-top: 5px solid var(--text-3);
}
.fs-select-wrap .fs-select { padding-right: 2rem; }

/* Filter bar inline */
.fs-filter-bar {
    display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap;
}

/* Bulk copy form */
.fs-copy-grid {
    display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap;
}
.fs-copy-grid .fs-field { display: flex; flex-direction: column; }

/* Arrow between selects */
.fs-copy-arrow {
    display: flex; align-items: center; padding-bottom: .55rem;
    color: var(--gold); font-size: .85rem;
}

.fs-btn-outline {
    display: inline-flex; align-items: center; gap: .45rem;
    padding: .55rem 1.1rem;
    font-family: var(--font-sans); font-size: .8rem; font-weight: 600;
    color: var(--gold); background: var(--gold-veil);
    border: 1.5px solid var(--gold-dk); border-radius: var(--r-xs);
    cursor: pointer; text-decoration: none; letter-spacing: .01em;
    transition: all var(--dur); white-space: nowrap;
}
.fs-btn-outline:hover {
    background: rgba(201,168,76,.15); border-color: var(--gold);
    color: var(--gold-lt); transform: translateY(-1px);
}
.fs-copy-note {
    font-size: .72rem; color: var(--text-3); margin-top: .75rem;
    display: flex; align-items: center; gap: .35rem;
}
.fs-copy-note strong { color: var(--text-2); font-weight: 600; }

/* ═══════════════════════════════════════════════════════════════
   YEAR BADGE
═══════════════════════════════════════════════════════════════ */
.fs-year-pill {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .22rem .75rem;
    background: var(--gold-veil); border: 1px solid var(--gold-dk);
    border-radius: 99px;
    font-family: var(--font-mono); font-size: .7rem; font-weight: 500;
    color: var(--gold); letter-spacing: .06em;
}
.fs-year-pill::before {
    content: ''; width: 5px; height: 5px; border-radius: 50%;
    background: var(--gold); opacity: .7;
    animation: fs-pulse 2s ease-in-out infinite;
}
@keyframes fs-pulse {
    0%,100% { opacity: .7; transform: scale(1); }
    50%      { opacity: 1; transform: scale(1.35); }
}

/* ═══════════════════════════════════════════════════════════════
   EMPTY STATE
═══════════════════════════════════════════════════════════════ */
.fs-empty {
    background: var(--veil); border: 1px solid var(--line);
    border-radius: var(--r);
    padding: 5rem 2rem;
    text-align: center;
}
.fs-empty-icon {
    width: 64px; height: 64px; margin: 0 auto 1.25rem;
    background: var(--veil-2); border: 1px solid var(--line-2);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; color: var(--text-3);
}
.fs-empty h3 {
    font-family: var(--font-serif); font-size: 1.2rem; font-weight: 600;
    color: var(--text-2); margin: 0 0 .4rem;
}
.fs-empty p { font-size: .84rem; color: var(--text-3); margin: 0 0 1.5rem; }

/* ═══════════════════════════════════════════════════════════════
   GRADE SECTION CARDS
═══════════════════════════════════════════════════════════════ */
.fs-grade-card {
    background: var(--veil);
    border: 1px solid var(--line);
    border-radius: var(--r);
    margin-bottom: 1.1rem;
    overflow: hidden;
    transition: border-color var(--dur), box-shadow var(--dur);
}
.fs-grade-card:hover {
    border-color: var(--line-2);
    box-shadow: var(--sh);
}

.fs-grade-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: .95rem 1.4rem;
    background: linear-gradient(90deg, rgba(201,168,76,.06) 0%, transparent 60%);
    border-bottom: 1px solid var(--line);
    gap: 1rem;
}
.fs-grade-title {
    display: flex; align-items: center; gap: .7rem;
}
.fs-grade-icon {
    width: 32px; height: 32px;
    background: var(--gold-veil); border: 1px solid var(--gold-dk);
    border-radius: var(--r-xs);
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; color: var(--gold); flex-shrink: 0;
}
.fs-grade-label {
    font-family: var(--font-serif); font-size: 1rem; font-weight: 600;
    color: var(--text); letter-spacing: -.005em;
}
.fs-grade-meta {
    display: flex; align-items: center; gap: .5rem;
}
.fs-term-count {
    font-family: var(--font-mono); font-size: .66rem; font-weight: 500;
    color: var(--text-3); background: var(--veil-2);
    border: 1px solid var(--line); border-radius: 99px;
    padding: .15rem .6rem;
}

.fs-btn-ghost {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .42rem .9rem;
    font-family: var(--font-sans); font-size: .76rem; font-weight: 600;
    color: var(--text-2); background: var(--veil);
    border: 1px solid var(--line-2); border-radius: var(--r-xs);
    cursor: pointer; text-decoration: none;
    transition: all var(--dur); white-space: nowrap; letter-spacing: .01em;
}
.fs-btn-ghost:hover {
    color: var(--text); background: var(--veil-2);
    border-color: rgba(255,255,255,.2);
}
.fs-btn-ghost i { font-size: .8rem; }

/* ═══════════════════════════════════════════════════════════════
   DATA TABLE
═══════════════════════════════════════════════════════════════ */
.fs-table-wrap { overflow-x: auto; }

table.fs-table {
    width: 100%; border-collapse: collapse;
    font-size: .82rem;
}

/* Head */
.fs-table thead tr {
    background: var(--veil-2);
    border-bottom: 1px solid var(--line-2);
}
.fs-table thead th {
    padding: .7rem 1rem;
    font-size: .65rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .1em; color: var(--text-3);
    white-space: nowrap;
}
.fs-table thead th:first-child { padding-left: 1.4rem; }
.fs-table thead th:last-child  { padding-right: 1.4rem; }
.fs-table th.r { text-align: right; }

/* Body rows */
.fs-table tbody tr {
    border-bottom: 1px solid var(--line);
    transition: background var(--dur);
}
.fs-table tbody tr:last-child { border-bottom: none; }
.fs-table tbody tr:hover { background: var(--veil-2); }

.fs-table tbody td {
    padding: .78rem 1rem; color: var(--text-2); vertical-align: middle;
    font-variant-numeric: tabular-nums;
}
.fs-table tbody td:first-child { padding-left: 1.4rem; }
.fs-table tbody td:last-child  { padding-right: 1.4rem; }
.fs-table td.r {
    text-align: right; font-family: var(--font-mono); font-size: .78rem;
}
.fs-table td.r-bold {
    text-align: right; font-family: var(--font-mono); font-size: .82rem;
    font-weight: 600; color: var(--text);
}

/* Term badge */
.fs-term-badge {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .25rem .75rem;
    background: rgba(45,212,191,.08); border: 1px solid rgba(45,212,191,.2);
    border-radius: 99px;
    font-size: .72rem; font-weight: 600; letter-spacing: .05em;
    color: var(--teal); white-space: nowrap;
}
.fs-term-badge::before {
    content: ''; width: 4.5px; height: 4.5px; border-radius: 50%;
    background: var(--teal); opacity: .8;
}

/* Totals row */
.fs-totals-row {
    background: linear-gradient(90deg, rgba(201,168,76,.07) 0%, transparent 100%) !important;
    border-top: 1px solid var(--gold-dk) !important;
    border-bottom: 1px solid var(--line) !important;
}
.fs-totals-row td {
    color: var(--text-2) !important; font-weight: 600;
    padding-top: .88rem !important; padding-bottom: .88rem !important;
}
.fs-totals-row td.r { font-family: var(--font-mono); font-size: .78rem; }
.fs-totals-row td.r-gold {
    text-align: right; font-family: var(--font-mono); font-size: .84rem;
    font-weight: 700; color: var(--gold) !important;
    text-shadow: 0 0 20px var(--gold-glow);
}
.fs-totals-label {
    font-size: .68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .1em; color: var(--gold) !important;
    display: flex; align-items: center; gap: .4rem;
}
.fs-totals-label::before {
    content: ''; width: 10px; height: 1.5px;
    background: var(--gold); opacity: .6;
}

/* Actions */
.fs-actions { display: flex; align-items: center; justify-content: flex-end; gap: .4rem; }
.fs-icon-btn {
    width: 30px; height: 30px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: var(--r-xs); border: 1px solid var(--line-2);
    background: var(--veil); color: var(--text-3);
    cursor: pointer; text-decoration: none; font-size: .75rem;
    transition: all var(--dur);
}
.fs-icon-btn:hover { background: var(--veil-2); border-color: rgba(255,255,255,.2); color: var(--text); }
.fs-icon-btn--danger:hover { background: var(--red-lt); border-color: rgba(248,113,113,.3); color: var(--red); }

/* ═══════════════════════════════════════════════════════════════
   SECTION SPACER LABEL
═══════════════════════════════════════════════════════════════ */
.fs-section-label {
    display: flex; align-items: center; gap: .75rem;
    font-size: .68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .14em; color: var(--text-3);
    margin-bottom: .9rem; margin-top: .5rem;
}
.fs-section-label::before {
    content: ''; flex: 1; height: 1px; background: var(--line);
}
.fs-section-label::after {
    content: ''; flex: 1; height: 1px; background: var(--line);
}

/* ═══════════════════════════════════════════════════════════════
   CONFIRM MODAL  (replaces browser confirm)
═══════════════════════════════════════════════════════════════ */
.fs-modal-backdrop {
    display: none; position: fixed; inset: 0; z-index: 9999;
    background: rgba(8,12,28,.75);
    backdrop-filter: blur(6px);
    align-items: center; justify-content: center;
    animation: fs-fade .2s ease both;
}
.fs-modal-backdrop.open { display: flex; }
@keyframes fs-fade { from { opacity: 0; } to { opacity: 1; } }

.fs-modal {
    background: var(--ink-2); border: 1px solid var(--line-2);
    border-radius: var(--r); box-shadow: var(--sh-lg);
    padding: 2rem 2rem 1.75rem; max-width: 400px; width: 92%;
    animation: fs-rise .3s cubic-bezier(.16,1,.3,1) both;
}
.fs-modal-icon {
    width: 44px; height: 44px; border-radius: 50%;
    background: var(--red-lt); border: 1px solid rgba(248,113,113,.25);
    display: flex; align-items: center; justify-content: center;
    color: var(--red); font-size: 1.1rem; margin-bottom: 1rem;
}
.fs-modal h3 {
    font-family: var(--font-serif); font-size: 1.1rem; font-weight: 600;
    color: var(--text); margin: 0 0 .4rem;
}
.fs-modal p { font-size: .83rem; color: var(--text-3); margin: 0 0 1.5rem; line-height: 1.6; }
.fs-modal-actions { display: flex; justify-content: flex-end; gap: .6rem; }
.fs-modal-cancel {
    padding: .5rem 1rem; font-family: var(--font-sans);
    font-size: .82rem; font-weight: 600; cursor: pointer;
    background: var(--veil); border: 1px solid var(--line-2);
    border-radius: var(--r-xs); color: var(--text-2);
    transition: all var(--dur);
}
.fs-modal-cancel:hover { background: var(--veil-2); color: var(--text); }
.fs-modal-confirm {
    padding: .5rem 1rem; font-family: var(--font-sans);
    font-size: .82rem; font-weight: 600; cursor: pointer;
    background: var(--red); border: none; border-radius: var(--r-xs);
    color: #fff; transition: opacity var(--dur);
}
.fs-modal-confirm:hover { opacity: .88; }

/* ═══════════════════════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════════════════════ */
@media (max-width: 768px) {
    .fs-h1 { font-size: 1.55rem; }
    .fs-copy-grid { flex-direction: column; align-items: stretch; }
    .fs-copy-arrow { display: none; }
    .fs-table thead th, .fs-table tbody td {
        padding-left: .65rem; padding-right: .65rem;
    }
}
</style>
@endpush

@section('content')
<div class="fs-page">
    @if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

    {{-- ── PAGE HEADER ────────────────────────────────────────────── --}}
    <header class="fs-header">
        <div class="fs-title-block">
            <div class="fs-eyebrow">
                <span class="fs-eyebrow-line"></span>
                Academic Finance
            </div>
            <h1 class="fs-h1">Fee Structures</h1>
            <ol class="fs-breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="fs-breadcrumb-sep">›</li>
                <li class="active">Fee Structures</li>
            </ol>
        </div>
        <a href="{{ route('fee-structures.create') }}" class="fs-btn-primary">
            <i class="bi bi-plus-lg"></i>
            Add Fee Structure
        </a>
    </header>

    {{-- ── YEAR FILTER ────────────────────────────────────────────── --}}
    <div class="fs-panel">
        <div class="fs-panel-body">
            <form method="GET" action="{{ route('fee-structures.index') }}" class="fs-filter-bar">
                <div class="fs-field">
                    <label class="fs-label">Academic Year</label>
                    <div class="fs-select-wrap">
                        <select name="year" class="fs-select" onchange="this.form.submit()">
                            @foreach(range(date('Y') + 1, date('Y') - 3) as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="padding-bottom:.25rem;">
                    <span class="fs-year-pill">{{ $year }}</span>
                </div>
            </form>
        </div>
    </div>

    

    {{-- ── STRUCTURES ─────────────────────────────────────────────── --}}
    @if($structures->isEmpty())

    <div class="fs-empty">
        <div class="fs-empty-icon"><i class="bi bi-list-task"></i></div>
        <h3>No Fee Structures Yet</h3>
        <p>There are no fee structures configured for {{ $year }}.</p>
        <a href="{{ route('fee-structures.create') }}" class="fs-btn-primary">
            <i class="bi bi-plus-lg"></i>
            Create First Structure
        </a>
    </div>

    @else

    <div class="fs-section-label">{{ $structures->count() }} Grade{{ $structures->count() > 1 ? 's' : '' }} · {{ $year }}</div>

    @foreach($structures->sortKeys() as $grade => $gradeStructures)
    <div class="fs-grade-card">

        {{-- Grade header --}}
        <div class="fs-grade-head">
            <div class="fs-grade-title">
                <div class="fs-grade-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <span class="fs-grade-label">Grade {{ $grade }}</span>
                <span class="fs-term-count">{{ $gradeStructures->count() }} term{{ $gradeStructures->count() > 1 ? 's' : '' }}</span>
            </div>
            <div class="fs-grade-meta">
                <a href="{{ route('fee-structures.create', ['grade' => $grade, 'year' => $year]) }}"
                   class="fs-btn-ghost">
                    <i class="bi bi-plus-lg"></i> Add Term
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="fs-table-wrap">
            <table class="fs-table">
                <thead>
                    <tr>
                        <th>Term</th>
                        <th class="r">Tuition</th>
                        <th class="r">Activity</th>
                        <th class="r">Exam</th>
                        <th class="r">Boarding</th>
                        <th class="r">Transport</th>
                        <th class="r">Other</th>
                        <th class="r">Total</th>
                        <th class="r">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gradeStructures->sortBy('term') as $structure)
                    <tr>
                        <td>
                            <span class="fs-term-badge">{{ $structure->term }}</span>
                        </td>
                        <td class="r">{{ number_format($structure->tuition_fee ?? 0, 2) }}</td>
                        <td class="r">{{ number_format($structure->activity_fee ?? 0, 2) }}</td>
                        <td class="r">{{ number_format($structure->exam_fee ?? 0, 2) }}</td>
                        <td class="r">{{ number_format($structure->boarding_fee ?? 0, 2) }}</td>
                        <td class="r">{{ number_format($structure->transport_fee ?? 0, 2) }}</td>
                        <td class="r">{{ number_format($structure->other_fee ?? 0, 2) }}</td>
                        <td class="r-bold">{{ number_format($structure->total_fee, 2) }}</td>
                        <td>
                            <div class="fs-actions">
                                <a href="{{ route('fee-structures.edit', $structure) }}"
                                   class="fs-icon-btn" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <button type="button"
                                    class="fs-icon-btn fs-icon-btn--danger fs-delete-btn"
                                    data-action="{{ route('fee-structures.destroy', $structure) }}"
                                    data-label="Grade {{ $grade }} {{ $structure->term }}"
                                    title="Delete">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- Annual totals row --}}
                    <tr class="fs-totals-row">
                        <td class="fs-totals-label" style="padding-left:1.4rem;">Annual Total</td>
                        <td class="r">{{ number_format($gradeStructures->sum('tuition_fee'), 2) }}</td>
                        <td class="r">{{ number_format($gradeStructures->sum('activity_fee'), 2) }}</td>
                        <td class="r">{{ number_format($gradeStructures->sum('exam_fee'), 2) }}</td>
                        <td class="r">{{ number_format($gradeStructures->sum('boarding_fee'), 2) }}</td>
                        <td class="r">{{ number_format($gradeStructures->sum('transport_fee'), 2) }}</td>
                        <td class="r">{{ number_format($gradeStructures->sum('other_fee'), 2) }}</td>
                        <td class="r-gold">{{ number_format($gradeStructures->sum('total_fee'), 2) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endforeach

    @endif

</div>{{-- /.fs-page --}}

{{-- ── DELETE MODAL (single, reused) ─────────────────────────────── --}}
<div class="fs-modal-backdrop" id="delete-modal">
    <div class="fs-modal">
        <div class="fs-modal-icon"><i class="bi bi-trash-fill"></i></div>
        <h3>Delete Fee Structure?</h3>
        <p id="delete-modal-msg">This action is permanent and cannot be undone.</p>
        <div class="fs-modal-actions">
            <button type="button" class="fs-modal-cancel" id="delete-cancel-btn">Cancel</button>
            <form id="delete-form" method="POST" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="fs-modal-confirm">
                    <i class="bi bi-trash-fill me-1"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    /* ── Delete modal ──────────────────────────────────────────────── */
    var delModal    = document.getElementById('delete-modal');
    var delForm     = document.getElementById('delete-form');
    var delMsg      = document.getElementById('delete-modal-msg');
    var delCancel   = document.getElementById('delete-cancel-btn');

    document.querySelectorAll('.fs-delete-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            delForm.action = btn.dataset.action;
            delMsg.textContent = 'Delete ' + btn.dataset.label + '? This cannot be undone.';
            delModal.classList.add('open');
        });
    });
    delCancel.addEventListener('click', function () { delModal.classList.remove('open'); });
    delModal.addEventListener('click', function (e) {
        if (e.target === delModal) delModal.classList.remove('open');
    });

    /* ── Bulk copy modal ───────────────────────────────────────────── */
    var copyModal   = document.getElementById('copy-modal');
    var copyTrigger = document.getElementById('copy-trigger-btn');
    var copyCancel  = document.getElementById('copy-cancel-btn');

    if (copyTrigger) {
        copyTrigger.addEventListener('click', function () {
            copyModal.classList.add('open');
        });
    }
    if (copyCancel) {
        copyCancel.addEventListener('click', function () {
            copyModal.classList.remove('open');
        });
    }
    if (copyModal) {
        copyModal.addEventListener('click', function (e) {
            if (e.target === copyModal) copyModal.classList.remove('open');
        });
    }

    /* ── Escape key closes any open modal ─────────────────────────── */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            delModal.classList.remove('open');
            if (copyModal) copyModal.classList.remove('open');
        }
    });
})();
</script>
@endpush
