@php
$background = $customBackground ?? '';
@endphp

<div class="id-card-wrapper">
    @php
    // Debug: Check if background URL exists
    $bgUrl = $customBackground ?? '';
    // Uncomment below to debug
    // \Log::info('Rendering custom card', ['background' => $bgUrl]);
    @endphp

    <div class="id-card custom-card-alt" style="background-image: url('{{ $customBackground }}'); background-size: cover; background-position: center;">
        <div class="custom-content">
            <small class="school-name-top"></small>

            @php
            $photoName = $student->name ?? 'Student';
            $photoUrl = !empty($student->image)
            ? asset('storage/' . $student->image)
            : 'https://ui-avatars.com/api/?name=' . urlencode($photoName) . '&background=cccccc&color=222';

            $classValue = $student->class ?? 'Class';
            $sectionValue = $student->section ?? null;
            $displayClass = trim($classValue . ($sectionValue ? ' - ' . $sectionValue : ''));
            @endphp

            <div class="student-photo-custom">
                <img src="{{ $photoUrl }}" alt="{{ $student->name }}">
            </div>

            <h6 class="student-name-custom">{{ $student->name ?? 'Student Name' }}</h6>

            <div class="info-item">
                <span class="label-custom">Father</span>
                <span style="font-weight: bold; display: inline-block; margin-left: -6px; margin-right: 9px;">:</span>
                <span class="value-custom">{{ $student->father_name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="label-custom">Class</span>
                <span style="font-weight: bold; display: inline-block; margin-left: -6px; margin-right: 9px;">:</span>
                <span class="value-custom">{{ $displayClass }}</span>
            </div>
            <div class="info-item">
                <span class="label-custom">ID No</span>
                <span style="font-weight: bold; display: inline-block; margin-left: -6px; margin-right: 9px;">:</span>
                <span class="value-custom">{{ $student->registration_no ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="label-custom">Roll</span>
                <span style="font-weight: bold; display: inline-block; margin-left: -6px; margin-right: 9px;">:</span>
                <span class="value-custom">{{ $student->roll_no ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="label-custom">Mobile</span>
                <span style="font-weight: bold; display: inline-block; margin-left: -6px; margin-right: 9px;">:</span>
                <span class="value-custom">{{ $student->mobile_no ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="label-custom">Blood</span>
                <span style="font-weight: bold; display: inline-block; margin-left: -6px; margin-right: 9px;">:</span>
                <span class="value-custom">{{ $student->blood_group ?? 'N/A' }}</span>
            </div>

        </div>
    </div>
</div>
