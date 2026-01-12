@extends('layouts.admin')

@section('title', 'ID Card Preview')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3>ID Cards - {{ $user->name }}</h3>
                <div>
                    <form action="{{ route('admin.idcard.print') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="class" value="{{ $selectedClass }}">
                        <input type="hidden" name="design" value="{{ $design }}">
                        <input type="hidden" name="customBackground" value="{{ $customBackground }}">

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-file-earmark-pdf"></i> Download PDF
                        </button>
                    </form>
                    <a href="{{ route('admin.idcard.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <p class="text-muted">
                Total Students: {{ $students->count() }} |
                Class Filter: {{ $selectedClass ?? 'All Classes' }} |
                Design: {{ $designLabel }}
            </p>
        </div>
    </div>

    @if($students->count() > 0)
    <div class="cards-container">
        @foreach($students as $student)
        @include('admin.idcard.designs.' . $design, [
        'student' => $student,
        'user' => $user,
        'customBackground' => $customBackground ?? null,
        ])
        @endforeach
    </div>
    @else
    <div class="alert alert-info">
        <strong>{{ $user->name }}</strong> এর কোনো স্টুডেন্ট ডেটা পাওয়া যায়নি।
    </div>
    @endif
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- ID Card Styles -->
<link rel="stylesheet" href="{{ asset('css/idcard.css') }}">

<style>
    @media screen {
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 30px;
            padding: 20px;
        }
    }

    @media print {

        /* Hide everything first */
        body * {
            visibility: hidden;
        }

        /* Show only front card containers */
        .cards-container,
        .cards-container * {
            visibility: visible;
        }

        /* Hide back side cards */
        .design1-back,
        .design2-back {
            display: none !important;
            visibility: hidden !important;
        }

        /* Grid layout for front cards */
        .cards-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8mm;
            padding: 5mm;
        }

        /* Hide UI elements */
        .btn,
        nav,
        .text-muted,
        h3,
        .alert {
            display: none !important;
            visibility: hidden !important;
        }

        /* Page settings */
        @page {
            size: A4;
            margin: 5mm;
        }

        /* Remove card wrapper margins */
        .id-card-wrapper {
            margin: 0 !important;
            page-break-inside: avoid;
        }
    }
</style>
@endsection
