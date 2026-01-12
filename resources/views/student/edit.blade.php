@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h4 class="fw-bold fs-4 text-center" style="color: #5a5af3;">Edit Student</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('student.update', $student) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label fw-bold">Student Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $student->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="father_name" class="form-label fw-bold">Father's Name</label>
                                <input type="text" class="form-control" id="father_name" name="father_name" value="{{ old('father_name', $student->father_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="mobile_no" class="form-label fw-bold">Mobile No.</label>
                                <input type="text" class="form-control" id="mobile_no" name="mobile_no" value="{{ old('mobile_no', $student->mobile_no) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="class" class="form-label fw-bold">Class</label>
                                <select class="form-select" id="class" name="class" required>
                                    <option value="" disabled>Select Class</option>
                                    @foreach (['Play','Nursery','One','Two','Three','Four','Five','Six','Seven','Eight','Nine','Ten'] as $class)
                                        <option value="{{ $class }}" {{ old('class', $student->class) === $class ? 'selected' : '' }}>{{ $class }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="section" class="form-label fw-bold">Section</label>
                                <select class="form-select" id="section" name="section" required>
                                    @foreach (['A','B'] as $section)
                                        <option value="{{ $section }}" {{ old('section', $student->section) === $section ? 'selected' : '' }}>{{ $section }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="roll_no" class="form-label fw-bold">Roll No.</label>
                                <input type="number" class="form-control" id="roll_no" name="roll_no" value="{{ old('roll_no', $student->roll_no) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="registration_no" class="form-label fw-bold">Registration No.</label>
                                <input type="text" class="form-control" id="registration_no" name="registration_no" value="{{ old('registration_no', $student->registration_no) }}" placeholder="Optional">
                            </div>
                            <div class="col-md-6">
                                <label for="blood_group" class="form-label fw-bold">Blood Group</label>
                                <select class="form-select" id="blood_group" name="blood_group">
                                    <option value="">Select Blood Group (Optional)</option>
                                    @foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $group)
                                        <option value="{{ $group }}" {{ old('blood_group', $student->blood_group) === $group ? 'selected' : '' }}>{{ $group }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="image" class="form-label fw-bold">Student Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div class="form-text">Uploading a new file will replace the existing optimized WebP image (max 2MB).</div>
                                @if ($student->image)
                                    <div class="mt-2">
                                        <small class="text-muted d-block mb-1">Current Image:</small>
                                        <img src="{{ asset('storage/' . $student->image) }}" alt="{{ $student->name }}" class="rounded" style="max-height: 120px;">
                                    </div>
                                @endif
                            </div>
                            
                            <div class="col-12 mt-4 text-center">
                                <button type="submit" class="btn btn-primary px-5 fw-bold" style="background-color: #5a5af3; border-color: #5a5af3;">Update Student</button>
                                <a href="{{ route('user.dashboard') }}" class="btn btn-secondary px-4 ms-2">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        html: `
            <ul style="text-align: left;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        `,
        confirmButtonColor: '#dc3545'
    });
</script>
@endif
@endsection
