@extends('layouts.admin')

@section('title', 'স্টুডেন্ট ডেটা')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ $user->name }} এর স্টুডেন্ট ডেটা</h4>
                <div>
                    <a href="{{ route('admin.school.export', $user->id) }}" class="btn btn-sm btn-success me-2">
                        📥 Excel ডাউনলোড
                    </a>
                    <a href="{{ route('admin.school.index') }}" class="btn btn-sm btn-light">
                        ← ফিরে যান
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>ছবি</th>
                                    <th>নাম</th>
                                    <th>পিতার নাম</th>
                                    <th>মোবাইল নম্বর</th>
                                    <th>ক্লাস</th>
                                    <th>সেকশন</th>
                                    <th>রোল নং</th>
                                    <th>রেজিস্ট্রেশন নং</th>
                                    <th>রক্তের গ্রুপ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $student)
                                    <tr>
                                        <td>{{ $students->firstItem() + $index }}</td>
                                        <td>
                                            @if($student->image)
                                                <img src="{{ asset('storage/' . $student->image) }}" 
                                                     alt="{{ $student->name }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->father_name }}</td>
                                        <td>{{ $student->mobile_no }}</td>
                                        <td>{{ $student->class }}</td>
                                        <td>{{ $student->section }}</td>
                                        <td>{{ $student->roll_no }}</td>
                                        <td>{{ $student->registration_no ?? 'N/A' }}</td>
                                        <td>{{ $student->blood_group ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    @if($students->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $students->links() }}
                    </div>
                    @endif
                @else
                    <div class="alert alert-info">
                        <strong>{{ $user->name }}</strong> এর কোনো স্টুডেন্ট ডেটা পাওয়া যায়নি।
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
