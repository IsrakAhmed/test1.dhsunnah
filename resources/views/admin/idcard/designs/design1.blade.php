{{-- Design 1: Professional Blue Waves --}}
<div class="id-card-wrapper" style="margin-bottom: 20px; page-break-inside: avoid;">
    {{-- Front Side --}}
    <div class="id-card design1-front">
        {{-- Background Waves --}}
        <div class="wave-background">
            <svg class="wave-top" viewBox="0 0 207 100" preserveAspectRatio="none">
                <path d="M0,40 Q50,10 100,35 T207,40 L207,0 L0,0 Z" fill="#0d6efd"/>
            </svg>
            <svg class="wave-bottom" viewBox="0 0 207 100" preserveAspectRatio="none">
                <path d="M0,50 Q50,80 100,55 T207,50 L207,100 L0,100 Z" fill="#0d6efd"/>
            </svg>
        </div>

        {{-- Logo Placeholder --}}
        <div class="logo-section">
            <div class="logo-box">LOGO</div>
        </div>

        {{-- Student Photo --}}
        <div class="photo-section">
            @if($student->image)
                <img src="{{ asset('storage/' . $student->image) }}" alt="{{ $student->name }}" class="student-photo">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&size=150&background=0d6efd&color=fff" alt="{{ $student->name }}" class="student-photo">
            @endif
        </div>

        {{-- Student Info --}}
        <div class="info-section">
            <h3 class="student-name">{{ strtoupper($student->name) }}</h3>
            <p class="student-class">{{ $student->class }} - {{ $student->section }}</p>
            
            <div class="details">
                <div class="detail-row">
                    <span class="label">Roll:</span>
                    <span class="value">{{ $student->roll_no }}</span>
                </div>
                @if($student->registration_no)
                <div class="detail-row">
                    <span class="label">Reg:</span>
                    <span class="value">{{ $student->registration_no }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="label">Mobile:</span>
                    <span class="value">{{ $student->mobile_no }}</span>
                </div>
                @if($student->blood_group)
                <div class="detail-row">
                    <span class="label">Blood:</span>
                    <span class="value">{{ $student->blood_group }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- School Name Footer --}}
        <div class="school-footer">
            <strong>{{ strtoupper($user->name) }}</strong>
        </div>
    </div>

    {{-- Back Side --}}
    <div class="id-card design1-back">
        {{-- Background Waves --}}
        <div class="wave-background">
            <svg class="wave-top-back" viewBox="0 0 207 80" preserveAspectRatio="none">
                <path d="M0,30 Q50,5 100,28 T207,30 L207,0 L0,0 Z" fill="#0d6efd" opacity="0.3"/>
            </svg>
            <svg class="wave-bottom-back" viewBox="0 0 207 80" preserveAspectRatio="none">
                <path d="M0,40 Q50,70 100,45 T207,40 L207,80 L0,80 Z" fill="#0d6efd" opacity="0.3"/>
            </svg>
        </div>

        {{-- Logo --}}
        <div class="back-logo">
            <div class="logo-box-small">LOGO</div>
        </div>

        {{-- School Info --}}
        <div class="back-info">
            <h4>{{ $user->name }}</h4>
            <ul class="info-list">
                <li>Address: School Address Here</li>
                <li>Phone: +880-XXX-XXXXXX</li>
                <li>Email: {{ $user->email }}</li>
                <li>Website: www.school.edu.bd</li>
            </ul>
        </div>


        {{-- Signature --}}
        <div class="signature-section">
            <div class="signature-line"></div>
            <p class="signature-label">Principal's Signature</p>
        </div>
    </div>
</div>
