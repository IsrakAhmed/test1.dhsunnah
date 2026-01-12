@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Top Action Buttons -->
    <div class="d-flex flex-wrap gap-3 mb-4">
        <a href="{{ route('user.dashboard') }}" class="btn btn-primary px-4 fw-bold" style="background-color: #5a5af3; border-color: #5a5af3;">
            <i class="bi bi-house-door-fill me-2"></i> Dashboard
        </a>
        <a href="{{ route('student.create') }}" class="btn btn-outline-success px-4 fw-bold" style="color: #28a745; border-color: #ced4da; background: white;">
            <i class="bi bi-person-plus-fill me-2"></i> Student Entry Form
        </a>
    </div>

    <!-- Filter -->
    @if($classes->count())
    <form method="GET" action="{{ route('user.dashboard') }}" class="row g-2 align-items-end mb-4">
        <div class="col-md-4 col-lg-3">
            <label for="classFilter" class="form-label fw-bold text-secondary small text-uppercase">Filter by Class</label>
            <select id="classFilter" name="class" class="form-select">
                <option value="">All Classes</option>
                @foreach ($classes as $class)
                    <option value="{{ $class }}" {{ $classFilter === $class ? 'selected' : '' }}>
                        {{ $class }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-lg-2">
            <button type="submit" class="btn btn-primary w-100 fw-bold" style="background-color: #5a5af3; border-color: #5a5af3;">Apply</button>
        </div>
        @if($classFilter)
        <div class="col-md-2 col-lg-2">
            <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
        @endif
    </form>
    @endif

    <!-- School Banner -->
    <div class="card border-0 text-white mb-4" style="background: linear-gradient(90deg, #6c5ce7, #a29bfe); border-radius: 15px;">
        <div class="card-body p-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="bg-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <!-- Placeholder Logo -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#6c5ce7" class="bi bi-book-fill" viewBox="0 0 16 16">
                        <path d="M8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="h4 fw-bold mb-0">ABC School</h2>
                    <p class="mb-0 opacity-75 small">"Empowering Education"</p>
                </div>
            </div>
            <div class="text-end">
                <h3 class="h4 fw-bold mb-0">{{ $students->count() }} Total Students</h3>
                <p class="mb-0 opacity-75 small">Dhaka</p>
            </div>
        </div>
    </div>

    <!-- Student Records Table -->
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <h5 class="fw-bold mb-0 text-dark">Student Records Overview</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="text-secondary small text-uppercase">
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <th class="border-0 ps-3">Image</th>
                            <th class="border-0">Name</th>
                            <th class="border-0">Father's Name</th>
                            <th class="border-0">Mobile No.</th>
                            <th class="border-0">Class</th>
                            <th class="border-0">Section</th>
                            <th class="border-0">Roll No.</th>
                            <th class="border-0 pe-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td class="ps-3">
                                @if($student->image)
                                    <img src="{{ asset('storage/' . $student->image) }}" class="rounded-circle" width="35" height="35" alt="{{ $student->name }}">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random" class="rounded-circle" width="35" height="35" alt="{{ $student->name }}">
                                @endif
                            </td>
                            <td class="fw-bold text-dark">{{ $student->name }}</td>
                            <td class="text-secondary">{{ $student->father_name }}</td>
                            <td class="text-secondary">{{ $student->mobile_no }}</td>
                            <td class="text-secondary">{{ $student->class }}</td>
                            <td class="text-secondary">{{ $student->section }}</td>
                            <td class="fw-bold text-primary">{{ $student->roll_no }}</td>
                            <td class="text-end pe-3">
                                <div class="d-inline-flex gap-3">
                                    <a href="{{ route('student.edit', $student) }}" class="text-decoration-none fw-bold" style="color: #5a5af3;">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('student.destroy', $student) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link p-0 text-decoration-none fw-bold" style="color: #dc3545;">
                                            <i class="bi bi-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox" style="font-size: 48px;"></i>
                                <p class="mt-3 mb-0">No students added yet. Click "Student Entry Form" to add students.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            @if($students->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $students->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Icons CDN (Bootstrap Icons) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#28a745',
        confirmButtonText: 'OK'
    });
</script>
@endif
@endsection
