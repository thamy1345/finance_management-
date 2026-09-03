@extends('layouts.admin')

@section('title', 'Students')
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&family=Instrument+Sans:wght@400;500;600&display=swap');

/* ── MATCH DARK SYSTEM ── */
:root {
--void:     #070810;
--deep:     #0b0d18;
--surface:  #0f1120;
--raised:   #141728;
--rim:      #1e2235;
--line:     rgba(255,255,255,0.06);

--muted:    #4a5068;
--dim:      #6b7390;
--body:     #c8cad8;
--bright:   #eceef8;
--white:    #ffffff;

--electric: #2dd4a0;
--electric-glow: rgba(79,142,247,0.18);
--mint:     #2dd4a0;
--amber:    #f5a623;
--rose:     #f56b6b;

--font-display: 'Syne', sans-serif;
--font-body: 'Instrument Sans', sans-serif;
--font-mono: 'JetBrains Mono', monospace;

--radius: 10px;
--radius-lg: 16px;
--radius-xl: 22px;

--transition: 0.2s ease;
}

/* ── BASE ── */
body {
margin:0;
background: var(--void);
font-family: var(--font-body);
color: var(--body);
}

/* ── PAGE ── */
.page-root {
min-height:100vh;
padding:32px 24px;
position:relative;
}

.page-root::before {
content:'';
position:fixed;
top:-200px;
left:-200px;
width:600px;
height:600px;
background: radial-gradient(circle, rgba(79,142,247,0.06), transparent 70%);
}

/* ── HEADER ── */
.page-header {
display:flex;
justify-content:space-between;
align-items:flex-start;
margin-bottom:24px;
}

.page-header h1 {
font-family: var(--font-display);
font-size:22px;
color: var(--white);
margin:0;
}

.page-header p {
font-size:13px;
color: var(--dim);
margin-top:4px;
}

.btn-primary {
background: var(--electric);
color:white;
padding:10px 18px;
border-radius:var(--radius);
text-decoration:none;
font-weight:600;
transition: var(--transition);
}

.btn-primary:hover {
background:#5f9bff;
box-shadow:0 6px 20px var(--electric-glow);
}

/* ── STATS ── */
.stat-grid {
display:grid;
grid-template-columns:repeat(3,1fr);
gap:12px;
margin-bottom:18px;
}

.stat-card {
background:var(--surface);
border:1px solid var(--line);
border-radius:var(--radius-lg);
padding:16px;
}

.stat-value {
font-family:var(--font-display);
font-size:20px;
color:var(--electric);
}

.stat-label {
font-size:11px;
color:var(--dim);
text-transform:uppercase;
margin-top:4px;
}

.stat-icon {
margin-bottom:8px;
}

/* ── FILTERS ── */
.filter-bar {
display:grid;
grid-template-columns:2fr 1fr 1fr 1fr 1fr auto;
gap:10px;
margin-bottom:18px;
}

.form-control {
background:var(--deep);
border:1px solid var(--rim);
color:var(--body);
padding:10px 12px;
border-radius:var(--radius);
font-size:13px;
}

/* ── TABLE ── */
.table-wrap {
background:var(--surface);
border:1px solid var(--line);
border-radius:var(--radius-xl);
overflow:hidden;
}

.tbl {
width:100%;
border-collapse:collapse;
font-size:13px;
}

.tbl th {
text-align:left;
padding:12px 16px;
font-size:11px;
text-transform:uppercase;
color:var(--dim);
background:rgba(255,255,255,0.02);
}

.tbl td {
padding:12px 16px;
border-bottom:1px solid var(--line);
}

.tbl tbody tr:hover {
background:rgba(79,142,247,0.05);
}

/* ── STUDENT ── */
.student {
display:flex;
align-items:center;
gap:10px;
}

.avatar {
width:32px;
height:32px;
border-radius:8px;
background:var(--deep);
display:flex;
align-items:center;
justify-content:center;
font-family:var(--font-mono);
color:var(--electric);
}

/* ── STATUS ── */
.pill {
padding:3px 10px;
border-radius:999px;
font-size:11px;
border:1px solid;
}

.pill-active {
background:rgba(45,212,160,0.1);
color:var(--mint);
border-color:rgba(45,212,160,0.25);
}

.pill-inactive {
background:rgba(245,107,107,0.1);
color:var(--rose);
border-color:rgba(245,107,107,0.25);
}

/* ── ACTIONS ── */
.actions a {
font-size:12px;
padding:5px 10px;
border:1px solid var(--rim);
border-radius:8px;
text-decoration:none;
color:var(--body);
margin-right:6px;
}

.actions a:hover {
border-color:var(--electric);
color:var(--electric);
}

</style>

<div class="page-root">

{{-- HEADER --}}
<div class="page-header">
<div>
<h1>Students</h1>
<p>Manage student records and fee tracking</p>
</div>

<a href="{{ route('students.create') }}" class="btn-primary">
Enroll Student
</a>
</div>

{{-- STATS --}}
<div class="stat-grid">
<div class="stat-card">
<div class="stat-value">{{ $students->total() }}</div>
<div class="stat-label">Total Students</div>
</div>

<div class="stat-card">
<div class="stat-value">{{ \App\Models\Student::where('status','active')->count() }}</div>
<div class="stat-label">Active Students</div>
</div>

<div class="stat-card">
<div class="stat-value">{{ $term }}</div>
<div class="stat-label">Current Term</div>
</div>
</div>

{{-- FILTERS --}}
<form method="GET" class="filter-bar">
<input class="form-control" name="search" placeholder="Search student..." value="{{ request('search') }}">

<select class="form-control" name="grade">
<option value="">Grade</option>
@foreach(range(1,9) as $g)
<option value="{{ $g }}">Grade {{ $g }}</option>
@endforeach
</select>

<select class="form-control" name="status">
<option value="">Status</option>
<option value="active">Active</option>
<option value="inactive">Inactive</option>
<option value="transferred">Transferred</option>
</select>

<select class="form-control" name="year">
@foreach(range(date('Y'), date('Y')-3) as $y)
<option value="{{ $y }}">{{ $y }}</option>
@endforeach
</select>

<select class="form-control" name="term">
@foreach(['Term 1','Term 2','Term 3'] as $t)
<option value="{{ $t }}">{{ $t }}</option>
@endforeach
</select>

<button class="btn-primary">Filter</button>
</form>

{{-- TABLE --}}
<div class="table-wrap">
<table class="tbl">
<thead>
<tr>
<th>Student</th>
<th>Grade</th>
<th>Parent</th>
<th>Phone</th>
<th>Paid</th>
<th>Status</th>
<th>Actions</th>
</tr>
</thead>

<tbody>
@forelse($students as $s)
<tr>
<td>
<div class="student">
<div class="avatar">
{{ strtoupper(substr($s->first_name ?? $s->full_name,0,1)) }}
</div>
<div>
<strong style="color:var(--bright)">{{ $s->full_name }}</strong><br>
<small style="color:var(--dim)">{{ $s->admission_number }}</small>
</div>
</div>
</td>

<td>Grade {{ $s->grade }}</td>
<td>{{ $s->parent_name }}</td>
<td>{{ $s->parent_phone }}</td>

<td style="font-family:var(--font-mono); color:var(--mint);">
KES {{ number_format($s->totalPaid($year,$term)) }}
</td>

<td>
<span class="pill {{ $s->status=='active' ? 'pill-active':'pill-inactive' }}">
{{ ucfirst($s->status) }}
</span>
</td>

<td class="actions">
<a href="{{ route('students.show',$s) }}">View</a>
<a href="{{ route('fee-payments.create') }}?student_id={{ $s->id }}">Pay</a>
<a href="{{ route('students.edit',$s) }}">Edit</a>
</td>
</tr>
@empty
<tr>
<td colspan="7" style="text-align:center;color:var(--dim);padding:30px;">
No students found
</td>
</tr>
@endforelse
</tbody>
</table>
</div>

<div style="margin-top:15px;">
{{ $students->links() }}
</div>

</div>
@endsection