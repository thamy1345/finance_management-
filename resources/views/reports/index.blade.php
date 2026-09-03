@extends('layouts.admin')
@section('title', 'Reports')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Instrument+Sans:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
<style>

/* ─────────────────────────────────────────
   ROOT & RESET
───────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg:        #0d0f18;
    --bg2:       #12151f;
    --bg3:       #181c2a;
    --border:    rgba(255,255,255,.07);
    --border-hi: rgba(255,255,255,.14);

    --ink:       #eef0f8;
    --ink2:      #9499b4;
    --ink3:      #50556e;

    --blue:      #4f8ef7;
    --blue-glow: rgba(79,142,247,.18);
    --green:     #34d89b;
    --green-glow:rgba(52,216,155,.15);
    --amber:     #f5a623;
    --amber-glow:rgba(245,166,35,.15);
    --red:       #f05c6a;
    --red-glow:  rgba(240,92,106,.15);
    --purple:    #b27cff;
    --purple-glow:rgba(178,124,255,.15);
    --cyan:      #2ed8f0;
    --cyan-glow: rgba(46,216,240,.15);

    --r:  16px;
    --rs: 10px;
    --font-display: 'Syne', sans-serif;
    --font-body:    'Instrument Sans', sans-serif;
}

body {
    font-family: var(--font-body);
    background: var(--bg);
    color: var(--ink);
}

/* ─────────────────────────────────────────
   PAGE WRAPPER
───────────────────────────────────────── */
.rp-wrap {
    padding: 36px 32px 60px;
    max-width: 1200px;
}

/* ─────────────────────────────────────────
   PAGE HEADER
───────────────────────────────────────── */
.rp-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 40px;
    gap: 16px;
    flex-wrap: wrap;
}

.rp-eyebrow {
    font-family: var(--font-body);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--ink3);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.rp-eyebrow::before {
    content: '';
    display: inline-block;
    width: 20px; height: 1px;
    background: var(--ink3);
}

.rp-title {
    font-family: var(--font-display);
    font-size: clamp(26px, 3vw, 38px);
    font-weight: 800;
    color: var(--ink);
    letter-spacing: -.02em;
    line-height: 1.1;
}

.rp-sub {
    font-size: 13px;
    color: var(--ink2);
    margin-top: 6px;
}

.rp-timestamp {
    font-size: 12px;
    color: var(--ink3);
    text-align: right;
    line-height: 1.7;
}
.rp-timestamp strong {
    display: block;
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 700;
    color: var(--ink2);
}

/* ─────────────────────────────────────────
   STAT STRIP
───────────────────────────────────────── */
.stat-strip {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 12px;
    margin-bottom: 44px;
}

.stat-box {
    position: relative;
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 20px 22px 18px;
    overflow: hidden;
    transition: border-color .2s, transform .2s;
}
.stat-box:hover {
    border-color: var(--border-hi);
    transform: translateY(-2px);
}

/* colored top bar */
.stat-box::before {
    content: '';
    position: absolute;
    top: 0; left: 20px; right: 20px;
    height: 2px;
    border-radius: 0 0 4px 4px;
    opacity: .7;
}
.stat-box.green::before  { background: var(--green); }
.stat-box.red::before    { background: var(--red); }
.stat-box.net-pos::before{ background: var(--green); }
.stat-box.net-neg::before{ background: var(--red); }
.stat-box.amber::before  { background: var(--amber); }
.stat-box.blue::before   { background: var(--blue); }

/* glow blob behind value */
.stat-box::after {
    content: '';
    position: absolute;
    bottom: -20px; right: -20px;
    width: 80px; height: 80px;
    border-radius: 50%;
    pointer-events: none;
    opacity: .4;
}
.stat-box.green::after   { background: var(--green-glow);  }
.stat-box.red::after     { background: var(--red-glow);    }
.stat-box.net-pos::after { background: var(--green-glow);  }
.stat-box.net-neg::after { background: var(--red-glow);    }
.stat-box.amber::after   { background: var(--amber-glow);  }
.stat-box.blue::after    { background: var(--blue-glow);   }

.stat-box .label {
    font-size: 10.5px;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--ink3);
    margin-bottom: 10px;
}

.stat-box .value {
    font-family: var(--font-display);
    font-size: 22px;
    font-weight: 800;
    letter-spacing: -.02em;
    line-height: 1;
}
.stat-box.green  .value { color: var(--green); }
.stat-box.red    .value { color: var(--red); }
.stat-box.net-pos .value { color: var(--green); }
.stat-box.net-neg .value { color: var(--red); }
.stat-box.amber  .value { color: var(--amber); }
.stat-box.blue   .value { color: var(--blue); }

.stat-box .value-sub {
    font-size: 11px;
    color: var(--ink3);
    margin-top: 6px;
    font-weight: 400;
}

/* ─────────────────────────────────────────
   SECTION LABEL
───────────────────────────────────────── */
.section-label {
    font-family: var(--font-display);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--ink3);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}

/* ─────────────────────────────────────────
   REPORT CARDS GRID
───────────────────────────────────────── */
.report-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 14px;
}

.report-card {
    position: relative;
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 26px 24px 22px;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    gap: 0;
    overflow: hidden;
    transition: border-color .2s, transform .22s, box-shadow .22s;
    cursor: pointer;
}

.report-card::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: var(--r);
    opacity: 0;
    transition: opacity .25s;
    pointer-events: none;
}

.report-card:hover {
    transform: translateY(-4px);
    border-color: var(--border-hi);
    color: inherit;
    text-decoration: none;
}

/* per-color accent on hover */
.report-card.blue:hover   { box-shadow: 0 12px 40px var(--blue-glow);   border-color: rgba(79,142,247,.35);  }
.report-card.green:hover  { box-shadow: 0 12px 40px var(--green-glow);  border-color: rgba(52,216,155,.35);  }
.report-card.amber:hover  { box-shadow: 0 12px 40px var(--amber-glow);  border-color: rgba(245,166,35,.35);  }
.report-card.red:hover    { box-shadow: 0 12px 40px var(--red-glow);    border-color: rgba(240,92,106,.35);  }
.report-card.purple:hover { box-shadow: 0 12px 40px var(--purple-glow); border-color: rgba(178,124,255,.35); }
.report-card.cyan:hover   { box-shadow: 0 12px 40px var(--cyan-glow);   border-color: rgba(46,216,240,.35);  }

/* corner glow */
.report-card .corner-glow {
    position: absolute;
    top: -30px; right: -30px;
    width: 100px; height: 100px;
    border-radius: 50%;
    opacity: .12;
    transition: opacity .25s, transform .25s;
    pointer-events: none;
}
.report-card:hover .corner-glow { opacity: .22; transform: scale(1.2); }

.report-card.blue   .corner-glow { background: var(--blue); }
.report-card.green  .corner-glow { background: var(--green); }
.report-card.amber  .corner-glow { background: var(--amber); }
.report-card.red    .corner-glow { background: var(--red); }
.report-card.purple .corner-glow { background: var(--purple); }
.report-card.cyan   .corner-glow { background: var(--cyan); }

/* icon */
.report-icon {
    width: 44px; height: 44px;
    border-radius: var(--rs);
    display: flex; align-items: center; justify-content: center;
    font-size: 19px;
    margin-bottom: 20px;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}
.report-card.blue   .report-icon { background: var(--blue-glow);   color: var(--blue);   }
.report-card.green  .report-icon { background: var(--green-glow);  color: var(--green);  }
.report-card.amber  .report-icon { background: var(--amber-glow);  color: var(--amber);  }
.report-card.red    .report-icon { background: var(--red-glow);    color: var(--red);    }
.report-card.purple .report-icon { background: var(--purple-glow); color: var(--purple); }
.report-card.cyan   .report-icon { background: var(--cyan-glow);   color: var(--cyan);   }

.report-title {
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 6px;
    position: relative; z-index: 1;
}

.report-desc {
    font-size: 12.5px;
    color: var(--ink2);
    line-height: 1.5;
    flex: 1;
    position: relative; z-index: 1;
}

/* arrow indicator */
.report-arrow {
    margin-top: 20px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 600;
    letter-spacing: .04em;
    opacity: 0;
    transform: translateX(-6px);
    transition: opacity .2s, transform .2s;
    position: relative; z-index: 1;
}
.report-card:hover .report-arrow { opacity: 1; transform: translateX(0); }
.report-card.blue   .report-arrow { color: var(--blue); }
.report-card.green  .report-arrow { color: var(--green); }
.report-card.amber  .report-arrow { color: var(--amber); }
.report-card.red    .report-arrow { color: var(--red); }
.report-card.purple .report-arrow { color: var(--purple); }
.report-card.cyan   .report-arrow { color: var(--cyan); }

/* left accent bar */
.report-card::before {
    content: '';
    position: absolute;
    top: 20px; bottom: 20px; left: 0;
    width: 3px;
    border-radius: 0 3px 3px 0;
    opacity: 0;
    transition: opacity .2s;
}
.report-card:hover::before { opacity: 1; }
.report-card.blue::before   { background: var(--blue); }
.report-card.green::before  { background: var(--green); }
.report-card.amber::before  { background: var(--amber); }
.report-card.red::before    { background: var(--red); }
.report-card.purple::before { background: var(--purple); }
.report-card.cyan::before   { background: var(--cyan); }

/* ─────────────────────────────────────────
   STAGGER ANIMATION
───────────────────────────────────────── */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
}

.stat-box, .report-card {
    animation: fadeUp .45s both;
}
.stat-box:nth-child(1) { animation-delay: .05s; }
.stat-box:nth-child(2) { animation-delay: .10s; }
.stat-box:nth-child(3) { animation-delay: .15s; }
.stat-box:nth-child(4) { animation-delay: .20s; }
.stat-box:nth-child(5) { animation-delay: .25s; }

.report-card:nth-child(1) { animation-delay: .30s; }
.report-card:nth-child(2) { animation-delay: .37s; }
.report-card:nth-child(3) { animation-delay: .44s; }
.report-card:nth-child(4) { animation-delay: .51s; }
.report-card:nth-child(5) { animation-delay: .58s; }
.report-card:nth-child(6) { animation-delay: .65s; }

</style>
@endpush

@section('content')

<div class="rp-wrap">

    {{-- ── Page Header ── --}}
    <div class="rp-header">
        <div>
            <div class="rp-eyebrow">Overview</div>
            <h1 class="rp-title">Reports</h1>
            <p class="rp-sub">Financial &amp; academic overview</p>
        </div>
        <div class="rp-timestamp">
            <strong>{{ now()->format('d M Y') }}</strong>
            {{ now()->format('l · H:i') }}
        </div>
    </div>

    {{-- ── Summary Stats ── --}}
    @php $net = $totalIncome - $totalExpenses; @endphp

    <div class="stat-strip">
        <div class="stat-box green">
            <div class="label">Total Income</div>
            <div class="value">KSh {{ number_format($totalIncome) }}</div>
            <div class="value-sub">All-time collected</div>
        </div>
        <div class="stat-box red">
            <div class="label">Total Expenses</div>
            <div class="value">KSh {{ number_format($totalExpenses) }}</div>
            <div class="value-sub">All-time spent</div>
        </div>
        <div class="stat-box {{ $net >= 0 ? 'net-pos' : 'net-neg' }}">
            <div class="label">Net Balance</div>
            <div class="value">KSh {{ number_format(abs($net)) }}</div>
            <div class="value-sub">{{ $net >= 0 ? 'Surplus' : 'Deficit' }}</div>
        </div>
        <div class="stat-box amber">
            <div class="label">Fee Defaulters</div>
            <div class="value">{{ $defaultersCount }}</div>
            <div class="value-sub">Students owing</div>
        </div>
        <div class="stat-box blue">
            <div class="label">Today's Collections</div>
            <div class="value">KSh {{ number_format($todayCollections) }}</div>
            <div class="value-sub">{{ now()->format('d M Y') }}</div>
        </div>
    </div>

    {{-- ── Report Links ── --}}
    <div class="section-label">Available Reports</div>

    <div class="report-grid">

        <a href="{{ route('reports.fee-collection') }}" class="report-card green">
            <div class="corner-glow"></div>
            <div class="report-icon"><i class="bi bi-cash-stack"></i></div>
            <div class="report-title">Fee Collection</div>
            <div class="report-desc">Collection rates by grade and term</div>
            <div class="report-arrow">View report <i class="bi bi-arrow-right"></i></div>
        </a>

        <a href="{{ route('reports.defaulters') }}" class="report-card red">
            <div class="corner-glow"></div>
            <div class="report-icon"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="report-title">Fee Defaulters</div>
            <div class="report-desc">Students with outstanding balances</div>
            <div class="report-arrow">View report <i class="bi bi-arrow-right"></i></div>
        </a>

        <a href="{{ route('reports.income-expense') }}" class="report-card blue">
            <div class="corner-glow"></div>
            <div class="report-icon"><i class="bi bi-bar-chart-line"></i></div>
            <div class="report-title">Income vs Expense</div>
            <div class="report-desc">Breakdown by category</div>
            <div class="report-arrow">View report <i class="bi bi-arrow-right"></i></div>
        </a>

        <a href="{{ route('reports.daily') }}" class="report-card amber">
            <div class="corner-glow"></div>
            <div class="report-icon"><i class="bi bi-calendar-day"></i></div>
            <div class="report-title">Daily Report</div>
            <div class="report-desc">All transactions for a single day</div>
            <div class="report-arrow">View report <i class="bi bi-arrow-right"></i></div>
        </a>

        <a href="{{ route('reports.payment-methods') }}" class="report-card purple">
            <div class="corner-glow"></div>
            <div class="report-icon"><i class="bi bi-credit-card-2-front"></i></div>
            <div class="report-title">Payment Methods</div>
            <div class="report-desc">Cash, M-Pesa, bank breakdown</div>
            <div class="report-arrow">View report <i class="bi bi-arrow-right"></i></div>
        </a>

    </div>

</div>

@endsection