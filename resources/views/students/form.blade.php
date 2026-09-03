@extends('layouts.admin')
@section('title', isset($student) ? 'Edit Student' : 'Add Student')
@section('page-title', isset($student) ? 'Edit Student' : 'Add Student')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">

        <div class="mb-4">
            <a href="{{ route('students.index') }}" class="text-decoration-none" style="font-size:14px;color:#6b7280;">
                <i class="bi bi-arrow-left me-1"></i>Back to Students
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-badge me-2 text-muted"></i>
                {{ isset($student) ? 'Edit Student Details' : 'New Student Registration' }}
            </div>
            <div class="card-body p-4">
                <form action="{{ isset($student) ? route('students.update', $student) : route('students.store') }}"
                      method="POST">
                    @csrf
                    @if(isset($student)) @method('PUT') @endif

                    <div class="row g-3">
                        {{-- Personal Info --}}
                        <div class="col-12">
                            <h6 style="font-size:12px;letter-spacing:.8px;text-transform:uppercase;color:#9ca3af;font-weight:600;" class="mb-3">
                                Personal Information
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $student->name ?? '') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="John Kamau Mwangi" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Admission Number <span class="text-danger">*</span></label>
                            <input type="text" name="admission_number"
                                   value="{{ old('admission_number', $student->admission_number ?? '') }}"
                                   class="form-control @error('admission_number') is-invalid @enderror"
                                   placeholder="ADM/2024/001" required>
                            @error('admission_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Grade <span class="text-danger">*</span></label>
                            <select name="grade" class="form-select @error('grade') is-invalid @enderror" required>
                                <option value="">Select grade…</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('grade', $student->grade ?? '') == $i ? 'selected' : '' }}>
                                        Grade {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('grade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth"
                                   value="{{ old('date_of_birth', isset($student) ? $student->date_of_birth?->format('Y-m-d') : '') }}"
                                   class="form-control @error('date_of_birth') is-invalid @enderror">
                            @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Select…</option>
                                <option value="male"   {{ old('gender', $student->gender ?? '') == 'male'   ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $student->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        {{-- Parent/Guardian Info --}}
                        <div class="col-12 mt-2">
                            <h6 style="font-size:12px;letter-spacing:.8px;text-transform:uppercase;color:#9ca3af;font-weight:600;" class="mb-3">
                                Parent / Guardian
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Parent Name</label>
                            <input type="text" name="parent_name"
                                   value="{{ old('parent_name', $student->parent_name ?? '') }}"
                                   class="form-control" placeholder="Jane Mwangi">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Parent Phone</label>
                            <input type="tel" name="parent_phone"
                                   value="{{ old('parent_phone', $student->parent_phone ?? '') }}"
                                   class="form-control" placeholder="+254 700 000 000">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Parent Email</label>
                            <input type="email" name="parent_email"
                                   value="{{ old('parent_email', $student->parent_email ?? '') }}"
                                   class="form-control" placeholder="parent@email.com">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Home Address</label>
                            <input type="text" name="address"
                                   value="{{ old('address', $student->address ?? '') }}"
                                   class="form-control" placeholder="P.O Box 123, Nairobi">
                        </div>

                        {{-- Enrollment --}}
                        <div class="col-12 mt-2">
                            <h6 style="font-size:12px;letter-spacing:.8px;text-transform:uppercase;color:#9ca3af;font-weight:600;" class="mb-3">
                                Enrollment
                            </h6>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Enrollment Date</label>
                            <input type="date" name="enrollment_date"
                                   value="{{ old('enrollment_date', isset($student) ? $student->enrollment_date?->format('Y-m-d') : date('Y-m-d')) }}"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status', $student->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $student->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="transferred" {{ old('status', $student->status ?? '') == 'transferred' ? 'selected' : '' }}>Transferred</option>
                            </select>
                        </div>

                        <div class="col-12 mt-3 d-flex gap-3">
                            <button type="submit" class="btn btn-accent px-4">
                                <i class="bi bi-check-lg me-1"></i>
                                {{ isset($student) ? 'Update Student' : 'Register Student' }}
                            </button>
                            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection