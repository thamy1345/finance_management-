@extends('layouts.admin')
@section('title', 'Edit ' . ucfirst($transaction->type))
@section('page-title', 'Transactions')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=JetBrains+Mono:wght@300;400;500&display=swap');
:root {
    --ink:          #0a0a0f;
    --ink-2:        #14141c;
    --ink-3:        #1e1e2a;
    --surface:      #161620;
    --border:       rgba(255,255,255,0.07);
    --border-hi:    rgba(255,255,255,0.14);
    --text:         #e8e8f0;
    --muted:        #6b6b80;
    --faint:        #2e2e3d;
    --green:        #00e5a0;
    --green-dim:    rgba(0,229,160,0.12);
    --green-glow:   rgba(0,229,160,0.25);
    --red:          #ff4d6a;
    --red-dim:      rgba(255,77,106,0.12);
    --red-glow:     rgba(255,77,106,0.25);
    --amber:        #ffb830;
    --amber-dim:    rgba(255,184,48,0.12);
    --blue:         #4d9fff;
    --blue-dim:     rgba(77,159,255,0.10);
    --radius:       12px;
    --radius-sm:    8px;
    --font:         'Syne', sans-serif;
    --mono:         'JetBrains Mono', monospace;
}

/* accent switches live on the transaction type — set by JS on load */
:root {
    --accent:      var(--green);
    --accent-dim:  var(--green-dim);
    --accent-glow: var(--green-glow);
}

.cf *, .cf *::before, .cf *::after { box-sizing: border-box; margin: 0; padding: 0; }
.cf { font-family: var(--font); color: var(--text); }

/* ── Header ── */
.cf-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 28px; flex-wrap: wrap; gap: 12px;
}
.cf-header-left { display: flex; flex-direction: column; gap: 4px; }
.cf-eyebrow {
    font-family: var(--mono); font-size: 10px; letter-spacing: .18em;
    text-transform: uppercase; color: var(--muted);
    display: flex; align-items: center; gap: 6px;
}
.cf-title {
    font-size: 26px; font-weight: 800; line-height: 1;
    background: linear-gradient(135deg, var(--text) 30%, var(--muted));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.cf-type-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 700;
    letter-spacing: .04em;
    background: var(--accent-dim); color: var(--accent);
    border: 1px solid color-mix(in srgb, var(--accent) 30%, transparent);
    transition: background .2s, color .2s, border-color .2s;
}
.cf-txn-id {
    font-family: var(--mono); font-size: 11px; color: var(--muted);
    background: var(--faint); border: 1px solid var(--border-hi);
    border-radius: 6px; padding: 3px 10px; letter-spacing: .08em;
}

/* ── Back btn ── */
.btn-back {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 16px; border-radius: var(--radius-sm);
    background: var(--faint); color: var(--muted);
    border: 1px solid var(--border-hi); font-family: var(--font);
    font-size: 13px; font-weight: 600; text-decoration: none;
    transition: all .15s;
}
.btn-back:hover { color: var(--text); border-color: rgba(255,255,255,.22); }

/* ── Layout ── */
.cf-layout {
    display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start;
}
@media (max-width: 900px) { .cf-layout { grid-template-columns: 1fr; } }

/* ── Card ── */
.cf-card {
    background: var(--ink-2); border: 1px solid var(--border);
    border-radius: var(--radius); overflow: hidden; position: relative;
}
.cf-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent, var(--accent), transparent);
    transition: background .3s;
}
.cf-card-header {
    padding: 20px 24px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 10px;
}
.cf-card-icon {
    width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 16px;
    background: var(--accent-dim); color: var(--accent);
    transition: background .2s, color .2s;
}
.cf-card-title {
    font-size: 12px; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--muted);
}
.cf-card-body { padding: 24px; display: flex; flex-direction: column; gap: 20px; }

/* ── Type Toggle ── */
.type-toggle {
    display: grid; grid-template-columns: 1fr 1fr; gap: 0;
    border-radius: var(--radius-sm); border: 1px solid var(--border);
    overflow: hidden; background: var(--ink-3);
}
.type-toggle input[type="radio"] { display: none; }
.type-toggle label {
    display: flex; align-items: center; justify-content: center; gap: 7px;
    padding: 10px 16px; cursor: pointer; font-size: 13px; font-weight: 600;
    color: var(--muted); transition: all .18s;
}
.type-toggle label:first-of-type { border-right: 1px solid var(--border); }
#type-income:checked  + label { background: var(--green-dim); color: var(--green); }
#type-expense:checked + label { background: var(--red-dim);   color: var(--red);   }

/* ── Form Fields ── */
.field { display: flex; flex-direction: column; gap: 7px; }
.field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
@media (max-width: 600px) { .field-row { grid-template-columns: 1fr; } }

.field-label {
    font-family: var(--mono); font-size: 10px; letter-spacing: .14em;
    text-transform: uppercase; color: var(--muted);
    display: flex; align-items: center; gap: 6px;
}
.field-label .req { color: var(--accent); font-size: 13px; line-height: 1; transition: color .2s; }

.field-input {
    width: 100%; background: var(--ink-3); color: var(--text);
    border: 1px solid var(--border); border-radius: var(--radius-sm);
    padding: 10px 14px; font-family: var(--font); font-size: 13px;
    transition: border-color .15s, box-shadow .15s; outline: none;
    -webkit-appearance: none; appearance: none;
}
.field-input:focus {
    border-color: color-mix(in srgb, var(--accent) 60%, transparent);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 12%, transparent);
}
.field-input::placeholder { color: var(--muted); }
.field-input.is-invalid { border-color: var(--red) !important; box-shadow: 0 0 0 3px var(--red-dim) !important; }
textarea.field-input { resize: vertical; min-height: 90px; line-height: 1.6; }
select.field-input {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b6b80' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 12px center;
    padding-right: 32px; cursor: pointer;
}
option { background: var(--ink-3); color: var(--text); }
optgroup { color: var(--muted); font-size: 11px; }

/* Amount */
.amount-wrap { position: relative; }
.amount-prefix {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    font-family: var(--mono); font-size: 12px; color: var(--muted);
    pointer-events: none; user-select: none;
}
.amount-wrap .field-input {
    padding-left: 48px; font-size: 18px; font-weight: 700; letter-spacing: -.01em;
    font-family: var(--mono);
}

/* Error */
.field-error {
    font-family: var(--mono); font-size: 11px; color: var(--red);
    display: flex; align-items: center; gap: 5px;
}

/* ── Unsaved indicator ── */
.unsaved-bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 16px; border-radius: var(--radius-sm);
    background: var(--amber-dim); border: 1px solid rgba(255,184,48,.25);
    font-size: 12px; font-weight: 600; color: var(--amber);
    opacity: 0; pointer-events: none;
    transition: opacity .2s; margin-bottom: 16px;
}
.unsaved-bar.show { opacity: 1; pointer-events: auto; }
.unsaved-dot {
    width: 6px; height: 6px; border-radius: 50%; background: var(--amber);
    animation: pulse-dot 1.5s ease-in-out infinite;
}
@keyframes pulse-dot {
    0%,100% { opacity: .6; transform: scale(1); }
    50%      { opacity: 1;  transform: scale(1.4); }
}

/* ── Submit / Cancel ── */
.btn-group { display: flex; gap: 10px; }
.btn-submit {
    flex: 1; padding: 13px 24px; border-radius: var(--radius-sm);
    border: 1px solid color-mix(in srgb, var(--accent) 30%, transparent);
    cursor: pointer; font-family: var(--font); font-size: 14px; font-weight: 700;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: all .18s;
    background: var(--accent-dim); color: var(--accent);
}
.btn-submit:hover {
    background: color-mix(in srgb, var(--accent) 22%, transparent);
    box-shadow: 0 0 20px var(--accent-glow);
    transform: translateY(-1px);
}
.btn-submit:active { transform: translateY(0); }
.btn-submit:disabled { opacity: .5; cursor: not-allowed; transform: none; box-shadow: none; }
.btn-cancel {
    padding: 13px 20px; border-radius: var(--radius-sm);
    background: var(--faint); color: var(--muted);
    border: 1px solid var(--border-hi); font-family: var(--font);
    font-size: 13px; font-weight: 600; text-decoration: none;
    display: flex; align-items: center; justify-content: center; gap: 7px;
    transition: all .15s; white-space: nowrap;
}
.btn-cancel:hover { color: var(--text); border-color: rgba(255,255,255,.22); }

/* ── Sidebar ── */
.sidebar-tip {
    background: var(--ink-2); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 20px;
    display: flex; flex-direction: column; gap: 16px;
}
.tip-title {
    font-size: 11px; font-weight: 700; letter-spacing: .1em;
    text-transform: uppercase; color: var(--muted);
    display: flex; align-items: center; gap: 7px;
}
.tip-list { display: flex; flex-direction: column; gap: 12px; }
.tip-item { display: flex; gap: 10px; }
.tip-icon {
    width: 28px; height: 28px; border-radius: 7px; flex-shrink: 0;
    background: var(--faint); color: var(--muted);
    display: flex; align-items: center; justify-content: center; font-size: 13px;
}
.tip-text { font-size: 12px; color: var(--muted); line-height: 1.5; padding-top: 4px; }

/* Preview card */
.preview-card {
    background: var(--ink-3); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 16px;
}
.preview-label {
    font-family: var(--mono); font-size: 10px; letter-spacing: .12em;
    text-transform: uppercase; color: var(--muted); margin-bottom: 8px;
}
.preview-amount {
    font-size: 28px; font-weight: 800; color: var(--accent);
    font-variant-numeric: tabular-nums; letter-spacing: -.02em; min-height: 34px;
    transition: color .2s;
}
.preview-meta { font-size: 12px; color: var(--muted); margin-top: 4px; }
.preview-row {
    display: flex; justify-content: space-between; font-size: 12px;
    padding: 6px 0; border-bottom: 1px solid var(--border);
}
.preview-row:last-child { border-bottom: none; }

/* Changed indicator on fields */
.field-changed .field-label::after {
    content: '●'; color: var(--amber); font-size: 8px; margin-left: 4px;
    vertical-align: middle;
}

/* ── Audit strip ── */
.audit-strip {
    background: var(--ink-3); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 12px 14px;
    display: flex; gap: 20px; flex-wrap: wrap;
}
.audit-item { display: flex; flex-direction: column; gap: 2px; }
.audit-key {
    font-family: var(--mono); font-size: 9px; letter-spacing: .14em;
    text-transform: uppercase; color: var(--muted);
}
.audit-val { font-size: 12px; font-weight: 600; color: var(--text); }

/* ── Animations ── */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}
.animate-in { animation: fadeInUp .3s ease both; }
.d1 { animation-delay: .05s; }
.d2 { animation-delay: .10s; }
.d3 { animation-delay: .15s; }

@keyframes spin { to { transform: rotate(360deg); } }
.spin { display: inline-block; animation: spin .7s linear infinite; }
</style>
@endpush

@section('content')
<div class="cf">

    {{-- ── Header ──────────────────────────────────────────────── --}}
    <div class="cf-header animate-in">
        <div class="cf-header-left">
            <span class="cf-eyebrow">
                <i class="ti ti-arrows-exchange"></i>
                Transactions
            </span>
            <h1 class="cf-title">Edit Transaction</h1>
        </div>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <span class="cf-txn-id">#{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</span>
            <span class="cf-type-pill" id="header-type-pill">
                <i class="ti ti-trending-up" id="header-type-icon"></i>
                <span id="header-type-text">{{ ucfirst($transaction->type) }}</span>
            </span>
            <a href="{{ route('transactions.index') }}" class="btn-back" id="cancel-btn">
                <i class="ti ti-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- ── Session success ─────────────────────────────────────── --}}
    @if(session('success'))
    <div style="background:var(--green-dim);border:1px solid rgba(0,229,160,.3);border-radius:var(--radius-sm);padding:14px 18px;margin-bottom:20px;display:flex;gap:10px;align-items:center" class="animate-in">
        <i class="ti ti-circle-check" style="color:var(--green);font-size:18px;flex-shrink:0"></i>
        <span style="font-size:13px;font-weight:600;color:var(--green)">{{ session('success') }}</span>
    </div>
    @endif

    {{-- ── Validation errors ───────────────────────────────────── --}}
    @if($errors->any())
    <div style="background:var(--red-dim);border:1px solid rgba(255,77,106,.3);border-radius:var(--radius-sm);padding:14px 18px;margin-bottom:20px;display:flex;gap:10px;align-items:flex-start" class="animate-in">
        <i class="ti ti-alert-circle" style="color:var(--red);font-size:18px;margin-top:1px;flex-shrink:0"></i>
        <div>
            <div style="font-size:13px;font-weight:700;color:var(--red);margin-bottom:6px">Please fix the following errors</div>
            <ul style="list-style:none;display:flex;flex-direction:column;gap:4px">
                @foreach($errors->all() as $error)
                    <li style="font-size:12px;color:rgba(255,77,106,.85);font-family:var(--mono)">→ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- ── Unsaved bar ─────────────────────────────────────────── --}}
    <div class="unsaved-bar" id="unsaved-bar">
        <div style="display:flex;align-items:center;gap:8px">
            <span class="unsaved-dot"></span>
            You have unsaved changes
        </div>
        <span style="font-size:11px;font-family:var(--mono);opacity:.7">Press Save to keep them</span>
    </div>

    {{-- ── Layout ──────────────────────────────────────────────── --}}
    <div class="cf-layout animate-in d1">

        {{-- ── MAIN FORM ─────────────────────────────────────── --}}
        <form method="POST"
              action="{{ route('transactions.update', $transaction) }}"
              id="txn-form"
              novalidate>
            @csrf
            @method('PUT')

            {{-- ── Transaction Details card ── --}}
            <div class="cf-card" style="margin-bottom:20px">
                <div class="cf-card-header">
                    <div class="cf-card-icon"><i class="ti ti-file-description"></i></div>
                    <div class="cf-card-title">Transaction Details</div>
                </div>
                <div class="cf-card-body">

                    {{-- Type Toggle --}}
                    <div class="field" id="field-type">
                        <label class="field-label">Transaction Type <span class="req">*</span></label>
                        <div class="type-toggle">
                            <input type="radio" name="type" id="type-income"  value="income"
                                {{ old('type', $transaction->type) === 'income'  ? 'checked' : '' }}>
                            <label for="type-income"><i class="ti ti-trending-up"></i> Income</label>
                            <input type="radio" name="type" id="type-expense" value="expense"
                                {{ old('type', $transaction->type) === 'expense' ? 'checked' : '' }}>
                            <label for="type-expense"><i class="ti ti-trending-down"></i> Expense</label>
                        </div>
                    </div>

                    {{-- Amount --}}
                    <div class="field" id="field-amount">
                        <label class="field-label" for="amount">Amount <span class="req">*</span></label>
                        <div class="amount-wrap">
                            <span class="amount-prefix">KES</span>
                            <input type="number" name="amount" id="amount"
                                class="field-input {{ $errors->has('amount') ? 'is-invalid' : '' }}"
                                placeholder="0.00" step="0.01" min="0.01"
                                value="{{ old('amount', $transaction->amount) }}"
                                data-orig="{{ $transaction->amount }}">
                        </div>
                        @error('amount')
                            <span class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="field" id="field-description">
                        <label class="field-label" for="description">Description <span class="req">*</span></label>
                        <input type="text" name="description" id="description"
                            class="field-input {{ $errors->has('description') ? 'is-invalid' : '' }}"
                            placeholder="e.g. Term 1 school fees — Grade 6B"
                            value="{{ old('description', $transaction->description) }}"
                            maxlength="255"
                            data-orig="{{ $transaction->description }}">
                        @error('description')
                            <span class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Category + Date --}}
                    <div class="field-row">
                        <div class="field" id="field-category">
                            <label class="field-label" for="category">Category <span class="req">*</span></label>
                            <select name="category" id="category"
                                class="field-input {{ $errors->has('category') ? 'is-invalid' : '' }}"
                                data-orig="{{ $transaction->category }}">
                                <option value="">Select category…</option>
                                <optgroup label="─ Income ─">
                                    @foreach($incomeCategories as $c)
                                        <option value="{{ $c }}" {{ old('category', $transaction->category) === $c ? 'selected' : '' }}>{{ $c }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="─ Expense ─">
                                    @foreach($expenseCategories as $c)
                                        <option value="{{ $c }}" {{ old('category', $transaction->category) === $c ? 'selected' : '' }}>{{ $c }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            @error('category')
                                <span class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field" id="field-date">
                            <label class="field-label" for="transaction_date">Date <span class="req">*</span></label>
                            <input type="date" name="transaction_date" id="transaction_date"
                                class="field-input {{ $errors->has('transaction_date') ? 'is-invalid' : '' }}"
                                value="{{ old('transaction_date', $transaction->transaction_date ? \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d') : '') }}"
                                data-orig="{{ $transaction->transaction_date ? \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d') : '' }}">
                            @error('transaction_date')
                                <span class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Payment & Classification card ── --}}
            <div class="cf-card" style="margin-bottom:20px">
                <div class="cf-card-header">
                    <div class="cf-card-icon"><i class="ti ti-credit-card"></i></div>
                    <div class="cf-card-title">Payment & Classification</div>
                </div>
                <div class="cf-card-body">

                    <div class="field-row">
                        <div class="field" id="field-method">
                            <label class="field-label" for="payment_method">Payment Method</label>
                            <select name="payment_method" id="payment_method"
                                class="field-input"
                                data-orig="{{ $transaction->payment_method }}">
                                <option value="">Select method…</option>
                                @foreach(['cash','bank_transfer','mpesa','cheque','card','other'] as $m)
                                    <option value="{{ $m }}" {{ old('payment_method', $transaction->payment_method) === $m ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $m)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field" id="field-status">
                            <label class="field-label" for="status">Status</label>
                            <select name="status" id="status"
                                class="field-input"
                                data-orig="{{ $transaction->status }}">
                                @foreach(['completed','pending','cancelled'] as $s)
                                    <option value="{{ $s }}" {{ old('status', $transaction->status) === $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field" id="field-year">
                            <label class="field-label" for="academic_year">Academic Year <span class="req">*</span></label>
                            <select name="academic_year" id="academic_year"
                                class="field-input {{ $errors->has('academic_year') ? 'is-invalid' : '' }}"
                                data-orig="{{ $transaction->academic_year }}">
                                <option value="">Select year…</option>
                                @foreach(range(date('Y') + 1, date('Y') - 3) as $y)
                                    @php $label = $y . '/' . ($y + 1); @endphp
                                    <option value="{{ $label }}" {{ old('academic_year', $transaction->academic_year) === $label ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year')
                                <span class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field" id="field-term">
                            <label class="field-label" for="term">Term</label>
                            <select name="term" id="term"
                                class="field-input"
                                data-orig="{{ $transaction->term }}">
                                <option value="">— None —</option>
                                @foreach(['Term 1','Term 2','Term 3'] as $t)
                                    <option value="{{ $t }}" {{ old('term', $transaction->term) === $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field" id="field-ref">
                            <label class="field-label" for="reference_number">Reference No.</label>
                            <input type="text" name="reference_number" id="reference_number"
                                class="field-input {{ $errors->has('reference_number') ? 'is-invalid' : '' }}"
                                placeholder="e.g. TXN-20250101"
                                value="{{ old('reference_number', $transaction->reference_number) }}"
                                maxlength="100"
                                data-orig="{{ $transaction->reference_number }}"
                                style="font-family:var(--mono);font-size:13px;">
                            @error('reference_number')
                                <span class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="field" id="field-notes">
                        <label class="field-label" for="notes">
                            Notes
                            <span style="color:var(--muted);font-size:9px;letter-spacing:.1em">(OPTIONAL)</span>
                        </label>
                        <textarea name="notes" id="notes"
                            class="field-input"
                            placeholder="Any additional notes or context…"
                            data-orig="{{ $transaction->notes }}">{{ old('notes', $transaction->notes) }}</textarea>
                    </div>

                </div>
            </div>

            {{-- ── Audit trail strip ── --}}
            <div class="audit-strip" style="margin-bottom:20px">
                <div class="audit-item">
                    <span class="audit-key">Created</span>
                    <span class="audit-val">{{ $transaction->created_at->format('M j, Y · g:i A') }}</span>
                </div>
                <div class="audit-item">
                    <span class="audit-key">Last Updated</span>
                    <span class="audit-val">{{ $transaction->updated_at->diffForHumans() }}</span>
                </div>
                <div class="audit-item">
                    <span class="audit-key">Transaction ID</span>
                    <span class="audit-val" style="font-family:var(--mono)">#{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>

            {{-- ── Action buttons ── --}}
            <div class="btn-group">
                <a href="{{ route('transactions.index') }}" class="btn-cancel" id="cancel-btn-form">
                    <i class="ti ti-x"></i> Cancel
                </a>
                <button type="submit" class="btn-submit" id="submit-btn">
                    <i class="ti ti-device-floppy"></i>
                    Save Changes
                </button>
            </div>

        </form>

        {{-- ── SIDEBAR ───────────────────────────────────────── --}}
        <div class="animate-in d2" style="display:flex;flex-direction:column;gap:16px">

            {{-- Live Preview --}}
            <div class="cf-card">
                <div class="cf-card-header">
                    <div class="cf-card-icon"><i class="ti ti-eye"></i></div>
                    <div class="cf-card-title">Live Preview</div>
                </div>
                <div class="cf-card-body">
                    <div class="preview-card">
                        <div class="preview-label">Transaction Amount</div>
                        <div class="preview-amount" id="preview-amount">KES 0.00</div>
                        <div class="preview-meta" id="preview-meta">—</div>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:0;margin-top:4px">
                        <div class="preview-row">
                            <span style="color:var(--muted)">Type</span>
                            <span id="preview-type" style="font-weight:600">—</span>
                        </div>
                        <div class="preview-row">
                            <span style="color:var(--muted)">Category</span>
                            <span id="preview-category" style="font-weight:600">—</span>
                        </div>
                        <div class="preview-row">
                            <span style="color:var(--muted)">Status</span>
                            <span id="preview-status" style="font-weight:600">—</span>
                        </div>
                        <div class="preview-row" style="border-bottom:none">
                            <span style="color:var(--muted)">Method</span>
                            <span id="preview-method" style="font-weight:600">—</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Diff summary (what changed) --}}
            <div class="cf-card" id="diff-card" style="display:none">
                <div class="cf-card-header">
                    <div class="cf-card-icon" style="background:var(--amber-dim);color:var(--amber)">
                        <i class="ti ti-git-diff"></i>
                    </div>
                    <div class="cf-card-title">What Changed</div>
                </div>
                <div class="cf-card-body" style="gap:8px">
                    <div id="diff-list" style="display:flex;flex-direction:column;gap:6px;font-size:11px;font-family:var(--mono)"></div>
                </div>
            </div>

            {{-- Tips --}}
            <div class="sidebar-tip">
                <div class="tip-title"><i class="ti ti-bulb" style="color:var(--amber)"></i> Edit Tips</div>
                <div class="tip-list">
                    <div class="tip-item">
                        <div class="tip-icon"><i class="ti ti-edit"></i></div>
                        <div class="tip-text">Changed fields are highlighted with an amber dot in the label.</div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon"><i class="ti ti-history"></i></div>
                        <div class="tip-text">Original values are tracked — the "What Changed" panel shows your edits in real time.</div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon"><i class="ti ti-shield-check"></i></div>
                        <div class="tip-text">Leaving without saving will prompt a warning if you have unsaved changes.</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    /* ── submitting flag kills beforeunload ─────────────────────── */
    var submitting = false;

    /* ── helpers ────────────────────────────────────────────────── */
    var $ = function (id) { return document.getElementById(id); };
    function fmt(n) {
        return 'KES ' + Number(n).toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    function cap(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : '—'; }

    /* ── accent colour follows transaction type ─────────────────── */
    function setAccent(type) {
        var isIncome = type === 'income';
        var accent     = isIncome ? 'var(--green)'      : 'var(--red)';
        var accentDim  = isIncome ? 'var(--green-dim)'  : 'var(--red-dim)';
        var accentGlow = isIncome ? 'var(--green-glow)' : 'var(--red-glow)';
        document.documentElement.style.setProperty('--accent',      accent);
        document.documentElement.style.setProperty('--accent-dim',  accentDim);
        document.documentElement.style.setProperty('--accent-glow', accentGlow);

        var pill = $('header-type-pill');
        var icon = $('header-type-icon');
        var txt  = $('header-type-text');
        if (pill) { pill.style.background = accentDim; pill.style.color = accent; }
        if (icon) { icon.className = isIncome ? 'ti ti-trending-up' : 'ti ti-trending-down'; }
        if (txt)  { txt.textContent = cap(type); }
    }

    /* ── live preview ───────────────────────────────────────────── */
    function updatePreview() {
        var type = (document.querySelector('input[name="type"]:checked') || {}).value || '';
        var amt  = parseFloat(($('amount') || {}).value) || 0;
        var date = ($('transaction_date') || {}).value;
        var cat  = ($('category') || {}).value;
        var stat = ($('status') || {}).value || '';
        var meth = (($('payment_method') || {}).value || '').replace(/_/g, ' ');

        setAccent(type);

        var amtEl = $('preview-amount');
        if (amtEl) {
            amtEl.textContent = amt > 0 ? fmt(amt) : 'KES 0.00';
            amtEl.style.color = type === 'income' ? 'var(--green)' : 'var(--red)';
        }
        var metaEl = $('preview-meta');
        if (metaEl && date) {
            var d = new Date(date + 'T00:00:00');
            metaEl.textContent = d.toLocaleDateString('en-KE', { day: 'numeric', month: 'long', year: 'numeric' });
        } else if (metaEl) {
            metaEl.textContent = '—';
        }
        var ptEl = $('preview-type');     if (ptEl) ptEl.textContent = cap(type);
        var pcEl = $('preview-category'); if (pcEl) pcEl.textContent = cat || '—';
        var psEl = $('preview-status');   if (psEl) psEl.textContent = cap(stat);
        var pmEl = $('preview-method');   if (pmEl) pmEl.textContent = cap(meth);
    }

    /* ── dirty / changed tracking ───────────────────────────────── */
    var origType = '{{ $transaction->type }}';

    function getFieldId(el) {
        return el.closest('[id^="field-"]') ? el.closest('[id^="field-"]').id : null;
    }

    function isDirty() {
        var dirty = false;
        document.querySelectorAll('[data-orig]').forEach(function (el) {
            if ((el.value || '') !== ((el.dataset.orig || ''))) dirty = true;
        });
        var typeChecked = (document.querySelector('input[name="type"]:checked') || {}).value || '';
        if (typeChecked !== origType) dirty = true;
        return dirty;
    }

    function updateDiff() {
        var changes = [];
        var labels  = {
            amount: 'Amount', description: 'Description', category: 'Category',
            transaction_date: 'Date', payment_method: 'Method', status: 'Status',
            academic_year: 'Year', term: 'Term', reference_number: 'Reference', notes: 'Notes'
        };

        document.querySelectorAll('[data-orig]').forEach(function (el) {
            var orig = el.dataset.orig || '';
            var curr = el.value || '';
            if (curr !== orig) {
                var fieldWrap = el.closest('[id^="field-"]');
                if (fieldWrap) fieldWrap.classList.add('field-changed');
                changes.push({ key: labels[el.name] || el.name, from: orig || '(empty)', to: curr || '(empty)' });
            } else {
                var fw2 = el.closest('[id^="field-"]');
                if (fw2) fw2.classList.remove('field-changed');
            }
        });

        var typeChecked = (document.querySelector('input[name="type"]:checked') || {}).value || '';
        if (typeChecked !== origType) {
            changes.push({ key: 'Type', from: origType, to: typeChecked });
            document.getElementById('field-type') && document.getElementById('field-type').classList.add('field-changed');
        } else {
            document.getElementById('field-type') && document.getElementById('field-type').classList.remove('field-changed');
        }

        var unsaved  = $('unsaved-bar');
        var diffCard = $('diff-card');
        var diffList = $('diff-list');

        if (unsaved)  unsaved.classList.toggle('show', changes.length > 0);
        if (diffCard) diffCard.style.display = changes.length > 0 ? '' : 'none';

        if (diffList && changes.length > 0) {
            diffList.innerHTML = changes.map(function (c) {
                return '<div style="display:flex;flex-direction:column;gap:2px;padding:6px 8px;background:var(--ink-3);border-radius:6px;border:1px solid var(--border)">'
                    + '<span style="color:var(--muted);font-size:9px;letter-spacing:.1em;text-transform:uppercase">' + c.key + '</span>'
                    + '<span style="color:var(--red);text-decoration:line-through;opacity:.7;font-size:10px">' + c.from + '</span>'
                    + '<span style="color:var(--green);font-size:10px">' + c.to + '</span>'
                    + '</div>';
            }).join('');
        }
    }

    function onChange() { updatePreview(); updateDiff(); }

    /* ── wire events ────────────────────────────────────────────── */
    ['amount', 'transaction_date', 'status', 'payment_method',
     'category', 'description', 'academic_year', 'term',
     'reference_number', 'notes'].forEach(function (id) {
        var el = $(id);
        if (el) {
            el.addEventListener('input',  onChange);
            el.addEventListener('change', onChange);
        }
    });
    document.querySelectorAll('input[name="type"]').forEach(function (r) {
        r.addEventListener('change', onChange);
    });

    /* ── beforeunload guard ─────────────────────────────────────── */
    window.addEventListener('beforeunload', function (e) {
        if (!submitting && isDirty()) { e.preventDefault(); e.returnValue = ''; }
    });

    /* ── cancel bypasses guard ──────────────────────────────────── */
    [$('cancel-btn'), $('cancel-btn-form')].forEach(function (btn) {
        if (btn) btn.addEventListener('click', function () { submitting = true; });
    });

    /* ── submit: loading state + flag ──────────────────────────── */
    var form    = $('txn-form');
    var saveBtn = $('submit-btn');
    if (form) {
        form.addEventListener('submit', function () {
            submitting = true;
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="ti ti-loader spin"></i> Saving…';
            }
        });
    }

    /* ── init ───────────────────────────────────────────────────── */
    updatePreview();
    updateDiff();

})();
</script>
@endsection