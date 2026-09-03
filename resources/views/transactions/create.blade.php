@extends('layouts.admin')
@section('title', ($type === 'income' ? 'Add Income' : 'Add Expense'))
@section('page-title', 'Transactions')

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
    --red-glow:   rgba(255,77,106,0.25);
    --amber:      #ffb830;
    --amber-dim:  rgba(255,184,48,0.12);
    --blue:       #4d9fff;
    --blue-dim:   rgba(77,159,255,0.10);

    --accent:     {{ $type === 'income' ? 'var(--green)' : 'var(--red)' }};
    --accent-dim: {{ $type === 'income' ? 'var(--green-dim)' : 'var(--red-dim)' }};
    --accent-glow:{{ $type === 'income' ? 'var(--green-glow)' : 'var(--red-glow)' }};

    --radius:     12px;
    --radius-sm:  8px;
    --font:       'Syne', sans-serif;
    --mono:       'JetBrains Mono', monospace;
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
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 20px;
    align-items: start;
  }
  @media (max-width: 900px) { .cf-layout { grid-template-columns: 1fr; } }

  /* ── Card ── */
  .cf-card {
    background: var(--ink-2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    position: relative;
  }
  .cf-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent, var(--accent), transparent);
  }
  .cf-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 10px;
  }
  .cf-card-icon {
    width: 34px; height: 34px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0;
    background: var(--accent-dim); color: var(--accent);
  }
  .cf-card-title {
    font-size: 12px; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--muted);
  }
  .cf-card-body { padding: 24px; display: flex; flex-direction: column; gap: 20px; }

  /* ── Type Toggle ── */
  .type-toggle {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 0; border-radius: var(--radius-sm);
    border: 1px solid var(--border); overflow: hidden;
    background: var(--ink-3);
  }
  .type-toggle input[type="radio"] { display: none; }
  .type-toggle label {
    display: flex; align-items: center; justify-content: center; gap: 7px;
    padding: 10px 16px; cursor: pointer; font-size: 13px; font-weight: 600;
    color: var(--muted); transition: all .18s; position: relative;
  }
  .type-toggle label:first-of-type { border-right: 1px solid var(--border); }
  .type-toggle input[value="income"]:checked  + label {
    background: var(--green-dim); color: var(--green);
  }
  .type-toggle input[value="expense"]:checked + label {
    background: var(--red-dim);   color: var(--red);
  }

  /* ── Form Fields ── */
  .field { display: flex; flex-direction: column; gap: 7px; }
  .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  @media (max-width: 600px) { .field-row { grid-template-columns: 1fr; } }

  .field-label {
    font-family: var(--mono); font-size: 10px; letter-spacing: .14em;
    text-transform: uppercase; color: var(--muted);
    display: flex; align-items: center; gap: 6px;
  }
  .field-label .req { color: var(--accent); font-size: 13px; line-height: 1; }

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
  .field-input.is-invalid { border-color: var(--red); }

  textarea.field-input { resize: vertical; min-height: 90px; line-height: 1.6; }

  select.field-input {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b6b80' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 12px center; padding-right: 32px; cursor: pointer;
  }
  option { background: var(--ink-3); color: var(--text); }
  optgroup { color: var(--muted); font-size: 11px; }

  /* Amount field wrapper */
  .amount-wrap { position: relative; }
  .amount-prefix {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    font-family: var(--mono); font-size: 12px; color: var(--muted);
    pointer-events: none; user-select: none;
  }
  .amount-wrap .field-input { padding-left: 48px; font-size: 18px; font-weight: 700; letter-spacing: -.01em; }

  /* Error messages */
  .field-error {
    font-family: var(--mono); font-size: 11px; color: var(--red);
    display: flex; align-items: center; gap: 5px;
  }

  /* ── Submit ── */
  .btn-submit {
    width: 100%; padding: 13px 24px;
    border-radius: var(--radius-sm); border: none; cursor: pointer;
    font-family: var(--font); font-size: 14px; font-weight: 700;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: all .18s; position: relative; overflow: hidden;
    background: var(--accent-dim); color: var(--accent);
    border: 1px solid color-mix(in srgb, var(--accent) 30%, transparent);
  }
  .btn-submit:hover {
    background: color-mix(in srgb, var(--accent) 22%, transparent);
    box-shadow: 0 0 20px var(--accent-glow);
    transform: translateY(-1px);
  }
  .btn-submit:active { transform: translateY(0); }

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

  /* Summary preview card */
  .preview-card {
    background: var(--ink-3); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 16px;
  }
  .preview-label { font-family: var(--mono); font-size: 10px; letter-spacing: .12em; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; }
  .preview-amount {
    font-size: 28px; font-weight: 800; color: var(--accent);
    font-variant-numeric: tabular-nums; letter-spacing: -.02em;
    min-height: 34px;
  }
  .preview-meta { font-size: 12px; color: var(--muted); margin-top: 4px; }

  /* ── Animations ── */
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .animate-in { animation: fadeInUp .3s ease both; }
  .d1 { animation-delay: .05s; } .d2 { animation-delay: .1s; } .d3 { animation-delay: .15s; }
</style>
@endpush

@section('content')
<div class="cf">

  {{-- Header --}}
  <div class="cf-header animate-in">
    <div class="cf-header-left">
      <span class="cf-eyebrow"><i class="ti ti-arrows-exchange"></i>&nbsp; Transactions</span>
      <h1 class="cf-title">{{ $type === 'income' ? 'Record Income' : 'Record Expense' }}</h1>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
      <span class="cf-type-pill">
        <i class="ti ti-{{ $type === 'income' ? 'trending-up' : 'trending-down' }}"></i>
        {{ ucfirst($type) }}
      </span>
      <a href="{{ route('transactions.index') }}" class="btn-back">
        <i class="ti ti-arrow-left"></i> Back
      </a>
    </div>
  </div>

  {{-- Validation Errors --}}
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

  {{-- Layout --}}
  <div class="cf-layout animate-in d1">

    {{-- Main Form --}}
    <form method="POST" action="{{ route('transactions.store') }}" id="txn-form">
      @csrf

      {{-- Type & Core --}}
      <div class="cf-card" style="margin-bottom:20px">
        <div class="cf-card-header">
          <div class="cf-card-icon"><i class="ti ti-file-description"></i></div>
          <div class="cf-card-title">Transaction Details</div>
        </div>
        <div class="cf-card-body">

          {{-- Type Toggle --}}
          <div class="field">
            <label class="field-label">Transaction Type <span class="req">*</span></label>
            <div class="type-toggle">
              <input type="radio" name="type" id="type-income"  value="income"  {{ old('type',$type)==='income' ?'checked':'' }}>
              <label for="type-income"><i class="ti ti-trending-up"></i> Income</label>
              <input type="radio" name="type" id="type-expense" value="expense" {{ old('type',$type)==='expense'?'checked':'' }}>
              <label for="type-expense"><i class="ti ti-trending-down"></i> Expense</label>
            </div>
          </div>

          {{-- Amount --}}
          <div class="field">
            <label class="field-label" for="amount">Amount <span class="req">*</span></label>
            <div class="amount-wrap">
              <span class="amount-prefix">KES</span>
              <input type="number" name="amount" id="amount"
                     class="field-input {{ $errors->has('amount') ? 'is-invalid' : '' }}"
                     placeholder="0.00" step="0.01" min="1"
                     value="{{ old('amount') }}"
                     oninput="updatePreview()">
            </div>
            @error('amount')<span class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</span>@enderror
          </div>

          {{-- Description --}}
          <div class="field">
            <label class="field-label" for="description">Description <span class="req">*</span></label>
            <input type="text" name="description" id="description"
                   class="field-input {{ $errors->has('description') ? 'is-invalid' : '' }}"
                   placeholder="e.g. Term 1 school fees — Grade 6B"
                   value="{{ old('description') }}" maxlength="255">
            @error('description')<span class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</span>@enderror
          </div>

          {{-- Category & Date --}}
          <div class="field-row">
            <div class="field">
              <label class="field-label" for="category">Category <span class="req">*</span></label>
              <select name="category" id="category"
                      class="field-input {{ $errors->has('category') ? 'is-invalid' : '' }}">
                <option value="">Select category…</option>
                <optgroup label="─ Income ─" id="income-cats">
                  @foreach($incomeCategories as $c)
                    <option value="{{ $c }}" {{ old('category')===$c?'selected':'' }}>{{ $c }}</option>
                  @endforeach
                </optgroup>
                <optgroup label="─ Expense ─" id="expense-cats">
                  @foreach($expenseCategories as $c)
                    <option value="{{ $c }}" {{ old('category')===$c?'selected':'' }}>{{ $c }}</option>
                  @endforeach
                </optgroup>
              </select>
              @error('category')<span class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</span>@enderror
            </div>

            <div class="field">
              <label class="field-label" for="transaction_date">Date <span class="req">*</span></label>
              <input type="date" name="transaction_date" id="transaction_date"
                     class="field-input {{ $errors->has('transaction_date') ? 'is-invalid' : '' }}"
                     value="{{ old('transaction_date', date('Y-m-d')) }}"
                     oninput="updatePreview()">
              @error('transaction_date')<span class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</span>@enderror
            </div>
          </div>

        </div>
      </div>

      {{-- Payment & Metadata --}}
      <div class="cf-card" style="margin-bottom:20px">
        <div class="cf-card-header">
          <div class="cf-card-icon"><i class="ti ti-credit-card"></i></div>
          <div class="cf-card-title">Payment & Classification</div>
        </div>
        <div class="cf-card-body">

          <div class="field-row">
            <div class="field">
              <label class="field-label" for="payment_method">Payment Method</label>
              <select name="payment_method" id="payment_method" class="field-input">
                <option value="">Select method…</option>
                @foreach(['cash','bank_transfer','mpesa','cheque','card','other'] as $m)
                  <option value="{{ $m }}" {{ old('payment_method')===$m?'selected':'' }}>
                    {{ ucwords(str_replace('_',' ',$m)) }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="field">
              <label class="field-label" for="status">Status</label>
              <select name="status" id="status" class="field-input">
                @foreach(['completed','pending','cancelled'] as $s)
                  <option value="{{ $s }}" {{ old('status','completed')===$s?'selected':'' }}>
                    {{ ucfirst($s) }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="field-row">
            <div class="field">
              <label class="field-label" for="academic_year">Academic Year <span class="req">*</span></label>
              <select name="academic_year" id="academic_year"
                      class="field-input {{ $errors->has('academic_year') ? 'is-invalid' : '' }}">
                <option value="">Select year…</option>
                @foreach(range(date('Y'), date('Y') - 3) as $y)
                  @php $label = $y . '/' . ($y + 1); @endphp
                  <option value="{{ $label }}" {{ old('academic_year', date('Y') . '/' . (date('Y')+1)) === $label ? 'selected' : '' }}>
                    {{ $label }}
                  </option>
                @endforeach
              </select>
              @error('academic_year')<span class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</span>@enderror
            </div>

            <div class="field">
              <label class="field-label" for="term">Term</label>
              <select name="term" id="term" class="field-input">
                <option value="">— None —</option>
                @foreach(['Term 1','Term 2','Term 3'] as $t)
                  <option value="{{ $t }}" {{ old('term')===$t?'selected':'' }}>{{ $t }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="field-row">
            <div class="field">
              <label class="field-label" for="reference_number">Reference No.</label>
              <input type="text" name="reference_number" id="reference_number"
                     class="field-input {{ $errors->has('reference_number') ? 'is-invalid' : '' }}"
                     placeholder="e.g. TXN-20250101"
                     value="{{ old('reference_number') }}" maxlength="100">
              @error('reference_number')<span class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</span>@enderror
            </div>
          </div>

          <div class="field">
            <label class="field-label" for="notes">Notes <span style="color:var(--muted);font-size:9px;letter-spacing:.1em">(OPTIONAL)</span></label>
            <textarea name="notes" id="notes" class="field-input"
                      placeholder="Any additional notes or context…">{{ old('notes') }}</textarea>
          </div>

        </div>
      </div>

      {{-- Submit --}}
      <button type="submit" class="btn-submit">
        <i class="ti ti-check"></i>
        Save {{ $type === 'income' ? 'Income' : 'Expense' }} Entry
      </button>

    </form>

    {{-- Sidebar --}}
    <div class="animate-in d2" style="display:flex;flex-direction:column;gap:16px">

      {{-- Live Preview --}}
      <div class="cf-card">
        <div class="cf-card-header">
          <div class="cf-card-icon"><i class="ti ti-eye"></i></div>
          <div class="cf-card-title">Live Preview</div>
        </div>
        <div class="cf-card-body">
          <div class="preview-card">
            <div class="preview-label">Entry Amount</div>
            <div class="preview-amount" id="preview-amount">KES 0.00</div>
            <div class="preview-meta" id="preview-meta">No date selected</div>
          </div>
          <div style="display:flex;flex-direction:column;gap:8px;margin-top:4px">
            <div style="display:flex;justify-content:space-between;font-size:12px;padding:6px 0;border-bottom:1px solid var(--border)">
              <span style="color:var(--muted)">Type</span>
              <span id="preview-type" style="font-weight:600">—</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12px;padding:6px 0;border-bottom:1px solid var(--border)">
              <span style="color:var(--muted)">Status</span>
              <span id="preview-status" style="font-weight:600">—</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12px;padding:6px 0">
              <span style="color:var(--muted)">Method</span>
              <span id="preview-method" style="font-weight:600">—</span>
            </div>
          </div>
        </div>
      </div>

      {{-- Tips --}}
      <div class="sidebar-tip">
        <div class="tip-title"><i class="ti ti-bulb" style="color:var(--amber)"></i> Quick Tips</div>
        <div class="tip-list">
          <div class="tip-item">
            <div class="tip-icon"><i class="ti ti-hash"></i></div>
            <div class="tip-text">Reference numbers help track payments against invoices or receipts.</div>
          </div>
          <div class="tip-item">
            <div class="tip-icon"><i class="ti ti-calendar"></i></div>
            <div class="tip-text">Use the actual transaction date, not today's date, for accurate records.</div>
          </div>
          <div class="tip-item">
            <div class="tip-icon"><i class="ti ti-tag"></i></div>
            <div class="tip-text">Assign the right term and category for clean financial reports.</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
(function () {
  const $ = id => document.getElementById(id);

  function fmt(n) {
    return 'KES ' + Number(n).toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function updatePreview() {
    const amt  = parseFloat($('amount')?.value) || 0;
    const date = $('transaction_date')?.value;
    const type = document.querySelector('input[name="type"]:checked')?.value || '—';
    const stat = $('status')?.value || '—';
    const meth = $('payment_method')?.value?.replace(/_/g,' ') || '—';

    $('preview-amount').textContent = amt > 0 ? fmt(amt) : 'KES 0.00';
    $('preview-amount').style.color = type === 'income' ? 'var(--green)' : 'var(--red)';

    if (date) {
      const d = new Date(date);
      $('preview-meta').textContent = d.toLocaleDateString('en-KE', { day:'numeric', month:'long', year:'numeric' });
    } else {
      $('preview-meta').textContent = 'No date selected';
    }

    $('preview-type').textContent   = type.charAt(0).toUpperCase() + type.slice(1);
    $('preview-status').textContent = stat.charAt(0).toUpperCase() + stat.slice(1);
    $('preview-method').textContent = meth.charAt(0).toUpperCase() + meth.slice(1);
  }

  // Wire all inputs
  ['amount','transaction_date','status','payment_method'].forEach(id => {
    const el = $(id);
    if (el) el.addEventListener('input', updatePreview);
    if (el) el.addEventListener('change', updatePreview);
  });
  document.querySelectorAll('input[name="type"]').forEach(r => r.addEventListener('change', updatePreview));

  updatePreview();
})();
</script>
@endsection