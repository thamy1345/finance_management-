@extends('layouts.admin')
@section('title', 'Add Fee Structure')

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-start gap-3">
    <div>
        <h1>Add Fee Structure</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('fee-structures.index') }}">Fee Structures</a></li>
                <li class="breadcrumb-item active">New</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('fee-structures.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><span class="card-title">Fee Structure Details</span></div>
            <div class="card-body">
                <form action="{{ route('fee-structures.store') }}" method="POST">
                    @csrf

                    <div class="form-section-title">Class & Term</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Grade <span class="text-danger">*</span></label>
                            <select name="grade" class="form-select @error('grade') is-invalid @enderror" required>
                                <option value="">Select Grade</option>
                                @foreach(['1','2','3','4','5','6','7','8','9'] as $g)
                                <option value="{{ $g }}" {{ old('grade', request('grade')) == $g ? 'selected' : '' }}>
                                    Grade {{ $g }}
                                </option>
                                @endforeach
                            </select>
                            @error('grade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Term <span class="text-danger">*</span></label>
                            <select name="term" class="form-select @error('term') is-invalid @enderror" required>
                                <option value="">Select Term</option>
                                <option value="Term 1" {{ old('term') == 'Term 1' ? 'selected' : '' }}>Term 1</option>
                                <option value="Term 2" {{ old('term') == 'Term 2' ? 'selected' : '' }}>Term 2</option>
                                <option value="Term 3" {{ old('term') == 'Term 3' ? 'selected' : '' }}>Term 3</option>
                            </select>
                            @error('term')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Academic Year <span class="text-danger">*</span></label>
                            <select name="academic_year" class="form-select @error('academic_year') is-invalid @enderror" required>
                                @foreach(range(date('Y') + 1, date('Y') - 2) as $y)
                                <option value="{{ $y }}" {{ old('academic_year', request('year', date('Y'))) == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                                @endforeach
                            </select>
                            @error('academic_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-section-title">Fee Breakdown</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Tuition Fee <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-muted small">KES</span>
                                <input type="number" name="tuition_fee"
                                    class="form-control @error('tuition_fee') is-invalid @enderror"
                                    value="{{ old('tuition_fee', 0) }}" min="0" step="0.01" required>
                                @error('tuition_fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Activity Fee</label>
                            <div class="input-group">
                                <span class="input-group-text text-muted small">KES</span>
                                <input type="number" name="activity_fee"
                                    class="form-control" value="{{ old('activity_fee', 0) }}" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Exam Fee</label>
                            <div class="input-group">
                                <span class="input-group-text text-muted small">KES</span>
                                <input type="number" name="exam_fee"
                                    class="form-control" value="{{ old('exam_fee', 0) }}" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Boarding Fee</label>
                            <div class="input-group">
                                <span class="input-group-text text-muted small">KES</span>
                                <input type="number" name="boarding_fee"
                                    class="form-control" value="{{ old('boarding_fee', 0) }}" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Transport Fee</label>
                            <div class="input-group">
                                <span class="input-group-text text-muted small">KES</span>
                                <input type="number" name="transport_fee"
                                    class="form-control" value="{{ old('transport_fee', 0) }}" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Other Fee</label>
                            <div class="input-group">
                                <span class="input-group-text text-muted small">KES</span>
                                <input type="number" name="other_fee"
                                    class="form-control" value="{{ old('other_fee', 0) }}" min="0" step="0.01">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-primary d-flex justify-content-between align-items-center py-2 px-3">
                        <span class="fw-semibold">Total Fee</span>
                        <span class="fw-bold fs-5" id="totalDisplay">KES 0.00</span>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-check-lg me-1"></i> Save Fee Structure
                        </button>
                        <a href="{{ route('fee-structures.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const feeFields = ['tuition_fee','activity_fee','exam_fee','boarding_fee','transport_fee','other_fee'];
function recalc() {
    const total = feeFields.reduce((sum, name) => {
        return sum + (parseFloat(document.querySelector(`[name="${name}"]`)?.value) || 0);
    }, 0);
    document.getElementById('totalDisplay').textContent =
        'KES ' + total.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
feeFields.forEach(name => document.querySelector(`[name="${name}"]`)?.addEventListener('input', recalc));
recalc();
</script>
@endpush