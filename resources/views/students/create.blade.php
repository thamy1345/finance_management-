@extends('layouts.admin')
@section('title', isset($student) ? 'Edit Student' : 'Enrol New Student')
@section('page-title', isset($student) ? 'Edit Student' : 'Enrol New Student')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=JetBrains+Mono:wght@300;400;500&display=swap');

/* ── Reset page-inner padding so we own the full canvas ── */
#main-content .page-inner { padding: 0 !important; }

:root {
  --ink:         #0a0a0f;
  --ink-2:       #111118;
  --ink-3:       #18181f;
  --ink-4:       #22222c;
  --border:      rgba(255,255,255,.07);
  --border-hi:   rgba(255,255,255,.13);
  --text:        #eeeef5;
  --muted:       #6b6b82;
  --faint:       #2a2a36;
  --cyan:        #00e5c8;
  --cyan-dim:    rgba(0,229,200,.10);
  --cyan-glow:   rgba(0,229,200,.22);
  --amber:       #ffb830;
  --amber-dim:   rgba(255,184,48,.10);
  --red:         #ff4d6a;
  --red-dim:     rgba(255,77,106,.10);
  --green:       #00e5a0;
  --green-dim:   rgba(0,229,160,.10);
  --font:        'Syne', sans-serif;
  --mono:        'JetBrains Mono', monospace;
  --r:           14px;
  --r-sm:        9px;
}

.sf-wrap {
  display: grid;
  grid-template-columns: 300px 1fr;
  min-height: calc(100vh - 64px);
  font-family: var(--font);
  color: var(--text);
  background: var(--ink);
}

/* ────────────────── LEFT PANEL ────────────────── */
.sf-panel {
  background: var(--ink-2);
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  padding: 40px 32px;
  position: sticky;
  top: 64px;
  height: calc(100vh - 64px);
  overflow-y: auto;
}

.sf-back {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 7px 14px; border-radius: var(--r-sm);
  background: var(--ink-4); border: 1px solid var(--border-hi);
  color: var(--muted); text-decoration: none;
  font-size: 12px; font-weight: 600;
  transition: color .15s, border-color .15s;
  margin-bottom: 36px; align-self: flex-start;
}
.sf-back:hover { color: var(--text); border-color: rgba(255,255,255,.22); }

.sf-panel-icon {
  width: 52px; height: 52px; border-radius: 14px;
  background: var(--cyan-dim);
  border: 1px solid color-mix(in srgb, var(--cyan) 28%, transparent);
  display: flex; align-items: center; justify-content: center;
  font-size: 24px; color: var(--cyan);
  margin-bottom: 20px;
}

.sf-panel-eyebrow {
  font-family: var(--mono); font-size: 10px;
  letter-spacing: .16em; text-transform: uppercase;
  color: var(--muted); margin-bottom: 6px;
}
.sf-panel-title {
  font-size: 28px; font-weight: 800; line-height: 1.1;
  background: linear-gradient(135deg, var(--text) 40%, var(--muted));
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  background-clip: text; margin-bottom: 12px;
}
.sf-panel-desc {
  font-size: 13px; color: var(--muted); line-height: 1.65;
  margin-bottom: 36px;
}

/* Step nav */
.sf-steps { display: flex; flex-direction: column; gap: 4px; }
.sf-step {
  display: flex; align-items: center; gap: 12px;
  padding: 10px 12px; border-radius: var(--r-sm);
  cursor: pointer; transition: background .15s;
  border: 1px solid transparent;
}
.sf-step:hover { background: var(--ink-4); }
.sf-step.active {
  background: var(--cyan-dim);
  border-color: color-mix(in srgb, var(--cyan) 22%, transparent);
}
.sf-step-num {
  width: 26px; height: 26px; border-radius: 50%;
  background: var(--faint); border: 1px solid var(--border-hi);
  display: flex; align-items: center; justify-content: center;
  font-family: var(--mono); font-size: 11px; font-weight: 700;
  color: var(--muted); flex-shrink: 0; transition: all .15s;
}
.sf-step.active .sf-step-num,
.sf-step.done  .sf-step-num {
  background: var(--cyan-dim); border-color: var(--cyan); color: var(--cyan);
}
.sf-step.done .sf-step-num::before { content: '✓'; }
.sf-step-label {
  font-size: 13px; font-weight: 600; color: var(--muted); transition: color .15s;
}
.sf-step.active .sf-step-label { color: var(--cyan); }
.sf-step.done  .sf-step-label { color: var(--text); }
.sf-step-sub   { font-size: 11px; color: var(--muted); margin-top: 1px; }

.sf-panel-footer {
  margin-top: auto; padding-top: 32px;
  border-top: 1px solid var(--border);
  font-size: 11px; color: var(--muted); line-height: 1.6;
}

/* ────────────────── RIGHT (FORM) ────────────────── */
.sf-form-area {
  background: var(--ink);
  padding: 48px 52px;
  overflow-y: auto;
}

/* Section block */
.sf-section {
  display: none;
  animation: sfFadeIn .25s ease both;
}
.sf-section.active { display: block; }

@keyframes sfFadeIn {
  from { opacity:0; transform:translateY(8px); }
  to   { opacity:1; transform:translateY(0); }
}

.sf-section-heading {
  display: flex; align-items: center; gap: 14px;
  margin-bottom: 32px;
  padding-bottom: 20px;
  border-bottom: 1px solid var(--border);
}
.sf-section-icon {
  width: 40px; height: 40px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 18px; flex-shrink: 0;
}
.sf-section-title  { font-size: 18px; font-weight: 700; }
.sf-section-sub    { font-size: 12px; color: var(--muted); margin-top: 2px; }

/* Fields */
.sf-grid   { display: grid; gap: 20px; margin-bottom: 20px; }
.sf-grid-2 { grid-template-columns: 1fr 1fr; }
.sf-grid-3 { grid-template-columns: 1fr 1fr 1fr; }
@media (max-width: 680px) { .sf-grid-2, .sf-grid-3 { grid-template-columns: 1fr; } }

.sf-field { display: flex; flex-direction: column; gap: 7px; }
.sf-label {
  font-family: var(--mono); font-size: 10px; letter-spacing: .13em;
  text-transform: uppercase; color: var(--muted);
  display: flex; align-items: center; gap: 6px;
}
.sf-label .req { color: var(--cyan); font-size: 14px; line-height: 1; }

.sf-input {
  width: 100%; background: var(--ink-3); color: var(--text);
  border: 1px solid var(--border); border-radius: var(--r-sm);
  padding: 10px 14px; font-family: var(--font); font-size: 13px;
  outline: none; transition: border-color .15s, box-shadow .15s;
  -webkit-appearance: none; appearance: none;
}
.sf-input:focus {
  border-color: color-mix(in srgb, var(--cyan) 55%, transparent);
  box-shadow: 0 0 0 3px var(--cyan-dim);
}
.sf-input::placeholder { color: var(--muted); }
.sf-input.is-invalid   { border-color: var(--red); }

select.sf-input {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b6b82' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
  background-repeat: no-repeat; background-position: right 12px center;
  padding-right: 32px; cursor: pointer;
}
option { background: var(--ink-3); color: var(--text); }

.sf-error {
  font-family: var(--mono); font-size: 11px; color: var(--red);
  display: flex; align-items: center; gap: 5px;
}

/* Input with prefix icon */
.sf-input-wrap { position: relative; }
.sf-input-icon {
  position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
  color: var(--muted); font-size: 14px; pointer-events: none;
}
.sf-input-wrap .sf-input { padding-left: 36px; }

/* Nav buttons */
.sf-nav {
  display: flex; align-items: center; justify-content: space-between;
  margin-top: 40px; padding-top: 28px;
  border-top: 1px solid var(--border);
}
.sf-btn {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 11px 22px; border-radius: var(--r-sm);
  font-family: var(--font); font-size: 13px; font-weight: 700;
  cursor: pointer; transition: all .16s; border: none; outline: none;
  text-decoration: none;
}
.sf-btn-ghost {
  background: var(--ink-4); color: var(--muted);
  border: 1px solid var(--border-hi);
}
.sf-btn-ghost:hover { color: var(--text); border-color: rgba(255,255,255,.22); }

.sf-btn-next {
  background: var(--cyan-dim); color: var(--cyan);
  border: 1px solid color-mix(in srgb, var(--cyan) 30%, transparent);
}
.sf-btn-next:hover {
  background: color-mix(in srgb, var(--cyan) 20%, transparent);
  box-shadow: 0 0 18px var(--cyan-glow);
  transform: translateY(-1px);
}

.sf-btn-submit {
  background: var(--cyan); color: var(--ink);
  border: 1px solid var(--cyan);
  font-weight: 800;
}
.sf-btn-submit:hover {
  background: color-mix(in srgb, var(--cyan) 85%, #fff);
  box-shadow: 0 0 24px var(--cyan-glow);
  transform: translateY(-1px);
}

/* Progress bar */
.sf-progress-wrap {
  height: 3px; background: var(--faint);
  border-radius: 2px; margin-bottom: 40px; overflow: hidden;
}
.sf-progress-bar {
  height: 100%; background: var(--cyan);
  border-radius: 2px; transition: width .3s ease;
  box-shadow: 0 0 8px var(--cyan-glow);
}

/* Validation error banner */
.sf-errors {
  background: var(--red-dim); border: 1px solid rgba(255,77,106,.28);
  border-radius: var(--r-sm); padding: 14px 18px;
  margin-bottom: 28px;
  display: flex; gap: 10px; align-items: flex-start;
}
.sf-errors ul { list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:4px; }
.sf-errors li { font-size:12px; color:rgba(255,77,106,.85); font-family:var(--mono); }
</style>
@endpush

@section('content')
<div class="sf-wrap">

  {{-- ── LEFT PANEL ── --}}
  <aside class="sf-panel">
    <a href="{{ route('students.index') }}" class="sf-back">
      <i class="bi bi-arrow-left"></i> Students
    </a>

    <div class="sf-panel-icon">
      <i class="bi bi-person-badge"></i>
    </div>
    <div class="sf-panel-eyebrow">
      {{ isset($student) ? 'Edit record' : 'New registration' }}
    </div>
    <h1 class="sf-panel-title">
      {{ isset($student) ? 'Edit Student' : 'Enrol New Student' }}
    </h1>
    <p class="sf-panel-desc">
      Complete all sections to {{ isset($student) ? 'update the student record' : 'register a student and optionally assign fee structure' }} so the system can serve better.
    </p>

    {{-- Step nav --}}
    <nav class="sf-steps" id="stepNav">
      <div class="sf-step active" data-step="1" onclick="goToStep(1)">
        <div class="sf-step-num">1</div>
        <div>
          <div class="sf-step-label">Personal Info</div>
          <div class="sf-step-sub">Name, grade, DOB</div>
        </div>
      </div>
      <div class="sf-step" data-step="2" onclick="goToStep(2)">
        <div class="sf-step-num">2</div>
        <div>
          <div class="sf-step-label">Parent / Guardian</div>
          <div class="sf-step-sub">Contact details</div>
        </div>
      </div>
      <div class="sf-step" data-step="3" onclick="goToStep(3)">
        <div class="sf-step-num">3</div>
        <div>
          <div class="sf-step-label">Enrollment</div>
          <div class="sf-step-sub">Date &amp; status</div>
        </div>
      </div>
    </nav>

    <div class="sf-panel-footer">
      Fields marked <span style="color:var(--cyan)">*</span> are required.<br>
      You can come back and edit any record at any time.
    </div>
  </aside>

  {{-- ── RIGHT: FORM ── --}}
  <main class="sf-form-area">

    {{-- Progress bar --}}
    <div class="sf-progress-wrap">
      <div class="sf-progress-bar" id="progressBar" style="width:33%"></div>
    </div>

    {{-- Validation errors --}}
    @if($errors->any())
    <div class="sf-errors">
      <i class="bi bi-exclamation-circle" style="color:var(--red);font-size:18px;flex-shrink:0;margin-top:1px;"></i>
      <div>
        <div style="font-size:13px;font-weight:700;color:var(--red);margin-bottom:6px;">Please fix the following</div>
        <ul>
          @foreach($errors->all() as $e)
            <li>→ {{ $e }}</li>
          @endforeach
        </ul>
      </div>
    </div>
    @endif

    <form action="{{ isset($student) ? route('students.update', $student) : route('students.store') }}"
          method="POST" id="studentForm">
      @csrf
      @if(isset($student)) @method('PUT') @endif

      {{-- ── STEP 1: Personal Info ── --}}
      <section class="sf-section active" id="step-1">
        <div class="sf-section-heading">
          <div class="sf-section-icon" style="background:var(--cyan-dim);color:var(--cyan);">
            <i class="bi bi-person"></i>
          </div>
          <div>
            <div class="sf-section-title">Personal Information</div>
            <div class="sf-section-sub">Core identity fields for the student record</div>
          </div>
        </div>

        <div class="sf-grid sf-grid-2">
          <div class="sf-field">
            <label class="sf-label">First Name <span class="req">*</span></label>
            <div class="sf-input-wrap">
              <i class="bi bi-person sf-input-icon"></i>
              <input type="text" name="first_name"
                     value="{{ old('first_name', $student->first_name ?? '') }}"
                     class="sf-input {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                     placeholder="John" required>
            </div>
            @error('first_name')<span class="sf-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</span>@enderror
          </div>
          <div class="sf-field">
            <label class="sf-label">Last Name <span class="req">*</span></label>
            <div class="sf-input-wrap">
              <i class="bi bi-person sf-input-icon"></i>
              <input type="text" name="last_name"
                     value="{{ old('last_name', $student->last_name ?? '') }}"
                     class="sf-input {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                     placeholder="Kamau" required>
            </div>
            @error('last_name')<span class="sf-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="sf-grid sf-grid-2">
          <div class="sf-field">
            <label class="sf-label">Admission Number <span class="req">*</span></label>
            <div class="sf-input-wrap">
              <i class="bi bi-hash sf-input-icon"></i>
              <input type="text" name="admission_number"
                     value="{{ old('admission_number', $student->admission_number ?? '') }}"
                     class="sf-input {{ $errors->has('admission_number') ? 'is-invalid' : '' }}"
                     placeholder="ADM/2024/001" required>
            </div>
            @error('admission_number')<span class="sf-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</span>@enderror
          </div>
          <div class="sf-field">
            <label class="sf-label">Stream</label>
            <div class="sf-input-wrap">
              <i class="bi bi-diagram-3 sf-input-icon"></i>
              <input type="text" name="stream"
                     value="{{ old('stream', $student->stream ?? '') }}"
                     class="sf-input" placeholder="e.g. North, East">
            </div>
          </div>
        </div>

        <div class="sf-grid sf-grid-3">
          <div class="sf-field">
            <label class="sf-label">Grade <span class="req">*</span></label>
            <select name="grade" class="sf-input {{ $errors->has('grade') ? 'is-invalid' : '' }}" required>
              <option value="">Select…</option>
              @for($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}" {{ old('grade', $student->grade ?? '') == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
              @endfor
            </select>
            @error('grade')<span class="sf-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</span>@enderror
          </div>
          <div class="sf-field">
            <label class="sf-label">Date of Birth</label>
            <input type="date" name="date_of_birth"
                   value="{{ old('date_of_birth', isset($student) ? $student->date_of_birth?->format('Y-m-d') : '') }}"
                   class="sf-input {{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}">
            @error('date_of_birth')<span class="sf-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</span>@enderror
          </div>
          <div class="sf-field">
            <label class="sf-label">Gender</label>
            <select name="gender" class="sf-input">
              <option value="">Select…</option>
              <option value="male"   {{ old('gender', $student->gender ?? '') == 'male'   ? 'selected' : '' }}>Male</option>
              <option value="female" {{ old('gender', $student->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
            </select>
          </div>
        </div>

        <div class="sf-nav">
          <a href="{{ route('students.index') }}" class="sf-btn sf-btn-ghost">
            <i class="bi bi-x-lg"></i> Cancel
          </a>
          <button type="button" class="sf-btn sf-btn-next" onclick="goToStep(2)">
            Next: Parent Details <i class="bi bi-arrow-right"></i>
          </button>
        </div>
      </section>

      {{-- ── STEP 2: Parent / Guardian ── --}}
      <section class="sf-section" id="step-2">
        <div class="sf-section-heading">
          <div class="sf-section-icon" style="background:var(--amber-dim);color:var(--amber);">
            <i class="bi bi-people"></i>
          </div>
          <div>
            <div class="sf-section-title">Parent / Guardian</div>
            <div class="sf-section-sub">Contact details for communications and fee reminders</div>
          </div>
        </div>

        <div class="sf-grid sf-grid-2">
          <div class="sf-field">
            <label class="sf-label">Guardian Name <span class="req">*</span></label>
            <div class="sf-input-wrap">
              <i class="bi bi-person-heart sf-input-icon"></i>
              <input type="text" name="guardian_name"
                     value="{{ old('guardian_name', $student->parent_name ?? '') }}"
                     class="sf-input" placeholder="Jane Kamau">
            </div>
          </div>
          <div class="sf-field">
            <label class="sf-label">Guardian Phone <span class="req">*</span></label>
            <div class="sf-input-wrap">
              <i class="bi bi-telephone sf-input-icon"></i>
              <input type="tel" name="guardian_phone"
                     value="{{ old('guardian_phone', $student->parent_phone ?? '') }}"
                     class="sf-input" placeholder="+254 700 000 000">
            </div>
          </div>
        </div>

        <div class="sf-grid sf-grid-2">
          <div class="sf-field">
            <label class="sf-label">Email Address</label>
            <div class="sf-input-wrap">
              <i class="bi bi-envelope sf-input-icon"></i>
              <input type="email" name="guardian_email"
                     value="{{ old('guardian_email', $student->parent_email ?? '') }}"
                     class="sf-input" placeholder="parent@email.com">
            </div>
          </div>
          <div class="sf-field">
            <label class="sf-label">Home Address</label>
            <div class="sf-input-wrap">
              <i class="bi bi-geo-alt sf-input-icon"></i>
              <input type="text" name="address"
                     value="{{ old('address', $student->address ?? '') }}"
                     class="sf-input" placeholder="P.O Box 123, Nairobi">
            </div>
          </div>
        </div>

        <div class="sf-nav">
          <button type="button" class="sf-btn sf-btn-ghost" onclick="goToStep(1)">
            <i class="bi bi-arrow-left"></i> Back
          </button>
          <button type="button" class="sf-btn sf-btn-next" onclick="goToStep(3)">
            Next: Enrollment <i class="bi bi-arrow-right"></i>
          </button>
        </div>
      </section>

      {{-- ── STEP 3: Enrollment ── --}}
      <section class="sf-section" id="step-3">
        <div class="sf-section-heading">
          <div class="sf-section-icon" style="background:var(--green-dim);color:var(--green);">
            <i class="bi bi-calendar-check"></i>
          </div>
          <div>
            <div class="sf-section-title">Enrollment</div>
            <div class="sf-section-sub">Enrollment date and current student status</div>
          </div>
        </div>

        <div class="sf-grid sf-grid-3">
          <div class="sf-field">
            <label class="sf-label">Academic Year <span class="req">*</span></label>
            <input type="text" name="academic_year"
                   value="{{ old('academic_year', date('Y')) }}"
                   class="sf-input {{ $errors->has('academic_year') ? 'is-invalid' : '' }}"
                   placeholder="2025" maxlength="10" required>
            @error('academic_year')<span class="sf-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</span>@enderror
          </div>
          <div class="sf-field">
            <label class="sf-label">Enrollment Date</label>
            <input type="date" name="enrollment_date"
                   value="{{ old('enrollment_date', isset($student) ? $student->enrollment_date?->format('Y-m-d') : date('Y-m-d')) }}"
                   class="sf-input">
          </div>
          <div class="sf-field">
            <label class="sf-label">Status</label>
            <select name="status" class="sf-input">
              <option value="active"      {{ old('status', $student->status ?? 'active')      == 'active'      ? 'selected' : '' }}>Active</option>
              <option value="inactive"    {{ old('status', $student->status ?? '')             == 'inactive'    ? 'selected' : '' }}>Inactive</option>
              <option value="transferred" {{ old('status', $student->status ?? '')             == 'transferred' ? 'selected' : '' }}>Transferred</option>
            </select>
          </div>
        </div>

        <div class="sf-field" style="margin-bottom:20px;">
          <label class="sf-label">Notes <span style="color:var(--muted);font-size:9px;">(OPTIONAL)</span></label>
          <textarea name="notes" class="sf-input" rows="3"
                    placeholder="Any relevant notes about this student…"
                    style="resize:vertical;">{{ old('notes', $student->notes ?? '') }}</textarea>
        </div>

        <div class="sf-nav">
          <button type="button" class="sf-btn sf-btn-ghost" onclick="goToStep(2)">
            <i class="bi bi-arrow-left"></i> Back
          </button>
          <button type="submit" class="sf-btn sf-btn-submit">
            <i class="bi bi-check-lg"></i>
            {{ isset($student) ? 'Update Student' : 'Register Student' }}
          </button>
        </div>
      </section>

    </form>
  </main>
</div>


@endsection

@push('scripts')
<script>
(function(){
  const STEPS = 3;
  let current = 1;

  @if($errors->any())
    const errorFields = @json($errors->keys());
    const step1Fields = ['first_name','last_name','admission_number','grade','date_of_birth'];
    const step2Fields = ['guardian_name','guardian_phone','guardian_email','address'];
    if (errorFields.some(f => step2Fields.includes(f))) current = 2;
    else if (errorFields.some(f => step1Fields.includes(f))) current = 1;
    else current = 3;
    goToStep(current);
  @endif

  window.goToStep = function(n) {
    current = n;
    document.querySelectorAll('.sf-section').forEach((s,i) => {
      s.classList.toggle('active', i + 1 === n);
    });
    document.querySelectorAll('.sf-step').forEach((s,i) => {
      s.classList.remove('active','done');
      if (i + 1 === n) s.classList.add('active');
      if (i + 1 <  n) s.classList.add('done');
    });
    document.getElementById('progressBar').style.width = (n / STEPS * 100) + '%';
    document.querySelector('.sf-form-area').scrollTop = 0;
  };
})();
</script>
@endpush