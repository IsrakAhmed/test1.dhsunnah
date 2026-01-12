@extends('layouts.admin')

@section('title', 'স্কুল - ইউজার লিস্ট')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">স্কুল - সকল ইউজার</h4>
            </div>
            <div class="card-body">
                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>নাম</th>
                                    <th>ইমেইল</th>
                                    <th>স্ট্যাটাস</th>
                                    <th>অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $index => $user)
                                    <tr>
                                        <td>{{ $users->firstItem() + $index }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.school.students', $user->id) }}" class="btn btn-sm btn-info">
                                                স্টুডেন্ট দেখুন
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="alert alert-info">
                        কোনো ইউজার পাওয়া যায়নি।
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
