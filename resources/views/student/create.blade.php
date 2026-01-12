@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h4 class="fw-bold fs-4 text-center" style="color: #5a5af3;">Student Entry Form</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('student.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label fw-bold">Student Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="father_name" class="form-label fw-bold">Father's Name</label>
                                <input type="text" class="form-control" id="father_name" name="father_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="mobile_no" class="form-label fw-bold">Mobile No.</label>
                                <input type="text" class="form-control" id="mobile_no" name="mobile_no" required>
                            </div>
                            <div class="col-md-4">
                                <label for="class" class="form-label fw-bold">Class</label>
                                <select class="form-select" id="class" name="class" required>
                                    <option value="" selected disabled>Select Class</option>
                                    <option value="Play">Play</option>
                                    <option value="Nursery">Nursery</option>
                                    <option value="One">One</option>
                                    <option value="Two">Two</option>
                                    <option value="Three">Three</option>
                                    <option value="Four">Four</option>
                                    <option value="Five">Five</option>
                                    <option value="Six">Six</option>
                                    <option value="Seven">Seven</option>
                                    <option value="Eight">Eight</option>
                                    <option value="Nine">Nine</option>
                                    <option value="Ten">Ten</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="section" class="form-label fw-bold">Section</label>
                                <select class="form-select" id="section" name="section" required>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="roll_no" class="form-label fw-bold">Roll No.</label>
                                <input type="number" class="form-control" id="roll_no" name="roll_no" required>
                            </div>
                            <div class="col-md-6">
                                <label for="registration_no" class="form-label fw-bold">Registration No.</label>
                                <input type="text" class="form-control" id="registration_no" name="registration_no" placeholder="Optional">
                            </div>
                            <div class="col-md-6">
                                <label for="blood_group" class="form-label fw-bold">Blood Group</label>
                                <select class="form-select" id="blood_group" name="blood_group">
                                    <option value="" selected>Select Blood Group (Optional)</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="image" class="form-label fw-bold">Student Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div class="form-text">Image will automatically convert to an optimized WebP (max 2MB) via Intervention Image. Accepted formats: jpeg, png, jpg, gif.</div>
                            </div>
                            
                            <div class="col-12 mt-4 text-center">
                                <button type="submit" class="btn btn-primary px-5 fw-bold" style="background-color: #28a745; border-color: #28a745;">Save Student</button>
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
