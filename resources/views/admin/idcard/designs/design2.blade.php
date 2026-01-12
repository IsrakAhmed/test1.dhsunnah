{{-- Design 2: Classic Orange --}}
<div class="id-card-wrapper" style="margin-bottom: 20px; page-break-inside: avoid;">
    {{-- Front --}}
    <div class="id-card design2-front">
        <div class="design2-curve"></div>
        <div class="emblem-circle">LOGO</div>

        <div class="school-block">
            <p class="bn-name">{{ $user->name }}</p>
            <p class="en-name">Student Identity Card</p>
        </div>

        <div class="id-pill">ID CARD</div>

        <div class="photo-frame">
            @if($student->image)
                <img src="{{ asset('storage/' . $student->image) }}" alt="{{ $student->name }}">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&size=150&background=ef6c00&color=fff" alt="{{ $student->name }}">
            @endif
        </div>

        <div class="student-highlight">
            <h3>{{ $student->name }}</h3>
        </div>

        <div class="info-card">
            <div class="info-row">
                <span class="label">Father :</span>
                <span class="value">{{ $student->father_name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Class :</span>
                <span class="value">{{ $student->class }}{{ $student->section ? ' - ' . $student->section : '' }}</span>
            </div>
            <div class="info-row">
                <span class="label">ID No :</span>
                <span class="value">{{ $student->registration_no ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Roll :</span>
                <span class="value">{{ $student->roll_no }}</span>
            </div>
            <div class="info-row">
                <span class="label">Mobile :</span>
                <span class="value">{{ $student->mobile_no }}</span>
            </div>
            @if($student->blood_group)
            <div class="info-row">
                <span class="label">Blood :</span>
                <span class="value">{{ $student->blood_group }}</span>
            </div>
            @endif
        </div>

        <div class="signature-strip">
            <span class="signature-text">Signature</span>
        </div>

        <div class="headteacher-bar">
            <span>Head Teacher</span>
        </div>
    </div>

    {{-- Back --}}
    <div class="id-card design2-back">
        <div class="back-header">
            <div class="logo-square">LOGO</div>
            <h4>{{ $user->name }}</h4>
            <p>Student Identity Card</p>
        </div>

        <div class="back-details">
            <div class="detail-row">
                <span>Student Name</span>
                <strong>{{ $student->name }}</strong>
            </div>
            <div class="detail-row">
                <span>Class & Section</span>
                <strong>{{ $student->class }}{{ $student->section ? ' - ' . $student->section : '' }}</strong>
            </div>
            <div class="detail-row">
                <span>Roll</span>
                <strong>{{ $student->roll_no }}</strong>
            </div>
            <div class="detail-row">
                <span>Guardian Mobile</span>
                <strong>{{ $student->mobile_no }}</strong>
            </div>
        </div>

        <div class="instruction-box">
            <p>Please return to school if found.</p>
            <ul>
                <li>Keep this card with the student.</li>
                <li>Report immediately if lost.</li>
                <li>Valid for current academic year.</li>
            </ul>
        </div>

        <div class="contact-box">
            <p>Phone: {{ $user->email ? 'Contact via ' . $user->email : '+880-XXXX-XXXXXX' }}</p>
            <p>Address: School Address Here</p>
        </div>

        <div class="back-signature">
            <div class="line"></div>
            <span>Authorized Signature</span>
        </div>
    </div>
</div>
