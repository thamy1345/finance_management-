@extends('layouts.admin')
@section('title','Edit Student')
@section('page-title','Edit Student')

@section('content')
<div class="page-header">
    <h1>Edit — {{ $student->full_name }}</h1>
    <div class="actions">
        <a href="{{ route('students.show', $student) }}" class="btn"><i class="ti ti-arrow-left"></i> Back</a>
    </div>
</div>

<div class="card" style="max-width:780px">
    <div class="card-header"><span class="card-title">Update Student Details</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('students.update', $student) }}">
            @csrf @method('PUT')
            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label">Admission Number *</label>
                    <input type="text" name="admission_number" class="form-control" value="{{ old('admission_number', $student->admission_number) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">First Name *</label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $student->first_name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name *</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $student->last_name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Grade *</label>
                    <select name="grade" class="form-control" required>
                        @foreach(range(1,9) as $g)
                            <option value="{{ $g }}" {{ old('grade',$student->grade)==$g?'selected':''}}>Grade {{ $g }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Stream</label>
                    <input type="text" name="stream" class="form-control" value="{{ old('stream', $student->stream) }}" placeholder="A, B, C">
                </div>
                <div class="form-group">
                    <label class="form-label">Gender *</label>
                    <select name="gender" class="form-control" required>
                        <option value="male"   {{ old('gender',$student->gender)==='male'?'selected':''}}>Male</option>
                        <option value="female" {{ old('gender',$student->gender)==='female'?'selected':''}}>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        @foreach(['active','inactive','transferred'] as $st)
                            <option value="{{ $st }}" {{ old('status',$student->status)===$st?'selected':''}}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr style="border:none;border-top:1px solid var(--border);margin:16px 0">
            <p class="fw-600" style="margin-bottom:12px;font-size:13px">Parent / Guardian</p>
            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label">Parent Name *</label>
                    <input type="text" name="parent_name" class="form-control" value="{{ old('parent_name', $student->parent_name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone *</label>
                    <input type="text" name="parent_phone" class="form-control" value="{{ old('parent_phone', $student->parent_phone) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="parent_email" class="form-control" value="{{ old('parent_email', $student->parent_email) }}">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes', $student->notes) }}</textarea>
            </div>
            <div style="display:flex;gap:8px;justify-content:space-between;margin-top:4px;align-items:center">
                <div style="display:flex;gap:8px">
                    <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Save Changes</button>
                    <a href="{{ route('students.show', $student) }}" class="btn">Cancel</a>
                </div>
                <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Permanently delete this student and all their records?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"><i class="ti ti-trash"></i> Delete Student</button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection
BLADE
echo "edit OK"
Output

edit OK

Write student ledger view
bash

cat > /home/claude/school-accounting/resources/views/students/ledger.blade.php << 'BLADE'
@extends('layouts.admin')
@section('title', 'Fee Ledger — '.$student->full_name)
@section('page-title', 'Fee Ledger')

@section('content')
<div class="page-header">
    <div>
        <h1>Fee Ledger — {{ $student->full_name }}</h1>
        <p class="text-muted" style="font-size:13px;margin-top:2px">Grade {{ $student->grade }} &middot; Adm: {{ $student->admission_number }} &middot; Academic Year {{ $year }}</p>
    </div>
    <div class="actions">
        <form method="GET" style="display:flex;gap:6px">
            @foreach(range(date('Y'),date('Y')-3) as $y)
                <button type="submit" name="year" value="{{ $y }}" class="btn {{ $year==$y ? 'btn-primary' : '' }}" style="font-size:12px;padding:5px 12px">{{ $y }}</button>
            @endforeach
        </form>
        <a href="{{ route('students.show', $student) }}" class="btn"><i class="ti ti-arrow-left"></i> Profile</a>
        <a href="{{ route('fee-payments.create') }}?student_id={{ $student->id }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Payment</a>
    </div>
</div>

@php
    $grandExpected = collect($ledger)->sum('expected');
    $grandPaid     = collect($ledger)->sum('paid');
    $grandBalance  = $grandExpected - $grandPaid;
@endphp

{{-- Year totals --}}
<div class="metrics" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
    <div class="metric-tile">
        <div class="metric-label">Total Expected ({{ $year }})</div>
        <div class="metric-value">KES {{ number_format($grandExpected) }}</div>
    </div>
    <div class="metric-tile">
        <div class="metric-label">Total Paid</div>
        <div class="metric-value text-green">KES {{ number_format($grandPaid) }}</div>
        @if($grandExpected > 0)
        <div class="progress-bar"><div class="progress-fill" style="width:{{ min(100,round(($grandPaid/$grandExpected)*100)) }}%"></div></div>
        @endif
    </div>
    <div class="metric-tile">
        <div class="metric-label">Outstanding Balance</div>
        <div class="metric-value {{ $grandBalance > 0 ? 'text-red' : 'text-green' }}">KES {{ number_format(abs($grandBalance)) }}</div>
        <div class="metric-sub {{ $grandBalance > 0 ? 'down' : 'up' }}">{{ $grandBalance <= 0 ? 'Fully paid' : 'Outstanding' }}</div>
    </div>
</div>

{{-- One section per term --}}
@foreach(['Term 1','Term 2','Term 3'] as $term)
@php $row = $ledger[$term] ?? ['expected'=>0,'paid'=>0,'balance'=>0,'payments'=>collect()]; @endphp
<div class="card" style="margin-bottom:16px">
    <div class="card-header" style="background:{{ $row['balance'] <= 0 && $row['expected'] > 0 ? '#EAF3DE' : '#F9FAFB' }}">
        <span class="card-title">{{ $term }}</span>
        <div style="display:flex;align-items:center;gap:12px">
            @if($row['expected'] > 0)
                @php $pct = round(($row['paid']/$row['expected'])*100); @endphp
                <span style="font-size:12px;color:var(--muted)">{{ $pct }}% paid</span>
                <span class="badge {{ $row['balance'] <= 0 ? 'badge-success' : ($row['paid'] > 0 ? 'badge-warning' : 'badge-danger') }}">
                    {{ $row['balance'] <= 0 ? 'Cleared' : ($row['paid'] > 0 ? 'Partial' : 'Unpaid') }}
                </span>
            @else
                <span class="badge badge-neutral">No fee structure</span>
            @endif
            <a href="{{ route('fee-payments.create') }}?student_id={{ $student->id }}&term={{ urlencode($term) }}&year={{ $year }}" class="btn btn-sm btn-primary"><i class="ti ti-plus"></i> Pay</a>
        </div>
    </div>
    <div class="card-body" style="padding:0">
        @if($row['expected'] > 0)
        <div style="display:flex;gap:0;border-bottom:1px solid var(--border)">
            <div style="flex:1;padding:14px 20px;border-right:1px solid var(--border)">
                <div class="text-muted" style="font-size:11px;margin-bottom:2px">EXPECTED</div>
                <div class="fw-600">KES {{ number_format($row['expected']) }}</div>
            </div>
            <div style="flex:1;padding:14px 20px;border-right:1px solid var(--border)">
                <div class="text-muted" style="font-size:11px;margin-bottom:2px">PAID</div>
                <div class="fw-600 text-green">KES {{ number_format($row['paid']) }}</div>
            </div>
            <div style="flex:1;padding:14px 20px">
                <div class="text-muted" style="font-size:11px;margin-bottom:2px">BALANCE</div>
                <div class="fw-600 {{ $row['balance'] > 0 ? 'text-red' : 'text-green' }}">KES {{ number_format(abs($row['balance'])) }}</div>
            </div>
        </div>
        @endif

        @if($row['payments']->count())
        <table class="tbl">
            <thead><tr><th>Receipt</th><th>Amount</th><th>Method</th><th>Date</th><th>M-Pesa / Ref</th><th>Notes</th><th></th></tr></thead>
            <tbody>
            @foreach($row['payments'] as $p)
            <tr>
                <td><a href="{{ route('fee-payments.show', $p) }}" class="mono" style="color:var(--blue-text)">{{ $p->receipt_number }}</a></td>
                <td class="text-green fw-600">KES {{ number_format($p->amount_paid) }}</td>
                <td><span class="badge {{ $p->payment_method==='mpesa'?'badge-success':($p->payment_method==='cash'?'badge-neutral':'badge-info') }}">{{ $p->payment_method_label }}</span></td>
                <td class="text-muted">{{ $p->payment_date->format('d M Y') }}</td>
                <td class="mono" style="font-size:11px">{{ $p->mpesa_code ?? $p->bank_reference ?? '—' }}</td>
                <td class="text-muted">{{ $p->notes ?? '—' }}</td>
                <td>
                    <form method="POST" action="{{ route('fee-payments.destroy', $p) }}" onsubmit="return confirm('Delete payment {{ $p->receipt_number }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state" style="padding:20px"><i class="ti ti-cash-off"></i>No payments for {{ $term }}</div>
        @endif
    </div>
</div>
@endforeach
@endsection

