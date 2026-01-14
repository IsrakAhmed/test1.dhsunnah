<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    @php
    // Try Nikosh first (better Bangla support), fallback to SolaimanLipi
    $fontPath = storage_path('fonts/Nikosh.ttf');
    if (!file_exists($fontPath)) {
        $fontPath = storage_path('fonts/SolaimanLipi.ttf');
    }
    
    $fontData = '';
    if (file_exists($fontPath)) {
        $fontData = base64_encode(file_get_contents($fontPath));
    }
    @endphp

    <style>
        @font-face {
            font-family: 'bangla';
            font-style: normal;
            font-weight: normal;
            src: url(data:font/truetype;charset=utf-8;base64,{{ $fontData }}) format('truetype');
        }

        @page {
            size: 55mm 87mm;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: 'bangla', 'Nikosh', 'SolaimanLipi', DejaVu Sans, sans-serif;
            width: 55mm;
            height: 87mm;
        }

        /* One student = one page */
        .page {
            page-break-after: always;
            width: 55mm;
            height: 87mm;
            position: relative;
            overflow: hidden;
        }

        /* Background Image (Absolute Positioned) */
        .bg-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            /* Behind content */
        }

        /* ID Card Container */
        .id-card {
            width: 100%;
            height: 100%;
            position: relative;
            box-sizing: border-box;
        }

        .content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            text-align: center;
        }

        .school {
            margin-top: 6mm;
            font-size: 6.75pt;
            /* 9px -> 6.75pt */
            font-weight: bold;
        }

        .photo {
            width: 23mm;
            height: 28mm;
            margin: 10mm auto 2mm;
            border: 0.75pt solid #000;
            overflow: hidden; /* Ensure image stays within border */
        }

        .name {
            font-size: 8.25pt;
            /* 11px -> 8.25pt */
            font-weight: bold;
            margin-bottom: 2mm;
        }

        .info {
            font-size: 6pt;
            /* 8px -> 6pt */
            text-align: left;
            padding: 0 6mm;
        }

        /* Use Table for reliable layout in PDF */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            vertical-align: top;
            padding: 0;
            padding-bottom: 1pt; /* Tighter spacing */
            line-height: 1.2;
        }

        .label-td {
            width: 13mm; /* Reduced from 22mm to minimize gap */
            font-weight: bold;
            white-space: nowrap;
        }
    </style>
</head>

<body>

    @foreach ($students as $student)
    @php
        $photoSrc = 'https://ui-avatars.com/api/?name=' . urlencode($student->name);
        
        if ($student->image) {
            $path = storage_path('app/public/' . $student->image);
            
            // Try public_path if storage_path fails (fallback)
            if (!file_exists($path)) {
                $path = public_path('storage/' . $student->image);
            }

            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $photoSrc = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }
    @endphp

    <div class="page">
        <div class="id-card">
            @if($customBackground)
            <img src="{{ $customBackground }}" class="bg-image">
            @endif

            <div class="content">

                <div class="school"></div>

                <div class="photo">
                    <img src="{{ $photoSrc }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>

                <div class="name">{{ $student->name }}</div>

                <div class="info">
                    <table class="info-table">
                        <tr><td class="label-td">Father:</td><td>{{ $student->father_name }}</td></tr>
                        <tr><td class="label-td">Class:</td><td>{{ $student->class }}{{ $student->section ? ' - ' . $student->section : '' }}</td></tr>
                        <tr><td class="label-td">ID No:</td><td>{{ $student->registration_no }}</td></tr>
                        <tr><td class="label-td">Roll:</td><td>{{ $student->roll_no }}</td></tr>
                        <tr><td class="label-td">Mobile:</td><td>{{ $student->mobile_no }}</td></tr>
                        <tr><td class="label-td">Blood:</td><td>{{ $student->blood_group }}</td></tr>
                    </table>
                </div>

            </div>
        </div>
    </div>
    @endforeach

</body>

</html>
