@extends('layouts.admin')
@section('title', 'Fee Defaulters')

@section('content')
<div class="page-header" style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
    <a href="{{ route('reports.index') }}" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:#1a1d27;border:1px solid #2a2d3e;color:#6b7280;text-decoration:none;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h1 style="font-size:1.35rem;font-weight:700;color:#e8eaf0;margin:0;">Fee Defaulters</h1>
        <p style="font-size:.8rem;color:#6b7280;margin:0;">{{ $academicYear }} &mdash; {{ $term }}{{ $grade ? ' · Grade '.$grade : '' }}</p>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-3">
                <label class="form-label mb-1" style="font-size:.75rem;color:#9ca3af;text-transform:uppercase;">Academic Year</label>
                <input type="text" name="academic_year" class="form-control" value="{{ $academicYear }}">
            </div>
            <div class="col-sm-2">
                <label class="form-label mb-1" style="font-size:.75rem;color:#9ca3af;text-transform:uppercase;">Term</label>
                <select name="term" class="form-select">
                    @foreach(['Term 1','Term 2','Term 3'] as $t)
                        <option value="{{ $t }}" {{ $term === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <label class="form-label mb-1" style="font-size:.75rem;color:#9ca3af;text-transform:uppercase;">Grade</label>
                <select name="grade" class="form-select">
                    <option value="">All</option>
                    @foreach(range(1,9) as $g)
                        <option value="{{ $g }}" {{ $grade == $g ? 'selected' : '' }}>Grade {{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

{{-- Summary --}}
<div class="card mb-4 p-3" style="background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.2);">
    <div style="display:flex;gap:24px;flex-wrap:wrap;align-items:center;">
        <div>
            <div style="font-size:.72rem;color:#6b7280;text-transform:uppercase;">Defaulters</div>
            <div style="font-size:1.5rem;font-weight:700;color:#ef4444;">{{ $students->count() }}</div>
        </div>
        <div>
            <div style="font-size:.72rem;color:#6b7280;text-transform:uppercase;">Total Outstanding</div>
            <div style="font-size:1.5rem;font-weight:700;color:#ef4444;">KSh {{ number_format($totalOutstanding) }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Grade</th>
                    <th>Parent</th>
                    <th>Phone</th>
                    <th class="text-end">Balance Due</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $i => $student)
                <tr>
                    <td style="color:#6b7280;">{{ $i + 1 }}</td>
                    <td>
                        <div style="font-weight:500;">{{ $student->name }}</div>
                        <div style="font-size:.75rem;color:#6b7280;">{{ $student->admission_number }}</div>
                    </td>
                    <td><span class="badge" style="background:#eff6ff;color:#1d4ed8;">Grade {{ $student->grade }}</span></td>
                    <td style="color:#9ca3af;">{{ $student->parent_name }}</td>
                    <td style="color:#9ca3af;">{{ $student->parent_phone }}</td>
                    <td class="text-end fw-bold text-danger">
                        KSh {{ number_format($student->getOutstandingBalance($academicYear, $term)) }}
                    </td>
                    <td>
                        <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-check-circle fs-2 d-block mb-2 text-success"></i>
                        No defaulters found for this period.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection