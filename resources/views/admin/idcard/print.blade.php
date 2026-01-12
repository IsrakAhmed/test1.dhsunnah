<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: DejaVu Sans, sans-serif;
        }

        /* One student = one page */
        .page {
            page-break-after: always;
            width: 210mm;
            height: 297mm;
            position: relative;
            overflow: hidden;
        }

        /* ID Card */
        .id-card {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);

            width: 55mm;
            height: 87mm;

            /* CRITICAL FIXES */
            overflow: hidden;
            /* 🔑 stops bleed */
            background-repeat: no-repeat;
            background-position: 0 0;
            background-size: 100% 100%;

            background-image: url('{{ $customBackground }}');

            box-sizing: border-box;
        }

        .content {
            position: absolute;
            inset: 0;
            text-align: center;
            overflow: hidden;
        }

        .school {
            margin-top: 6mm;
            font-size: 9px;
            font-weight: bold;
        }

        .photo {
            width: 23mm;
            height: 28mm;
            margin: 10mm auto 2mm;
            border: 1px solid #000;
            background-size: cover;
            background-position: center;
        }

        .name {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 2mm;
        }

        .info {
            font-size: 8px;
            text-align: left;
            padding: 0 6mm;
        }

        .row {
            display: flex;
            align-items: flex-start;
            /* ⬅ keeps text aligned at top */
            margin-bottom: 1mm;
        }

        .label {
            width: 22mm;
            font-weight: bold;
            flex-shrink: 0;
        }
    </style>
</head>

<body>

    @foreach ($students as $student)
    @php
    $photo = $student->image
    ? public_path('storage/' . $student->image)
    : 'https://ui-avatars.com/api/?name=' . urlencode($student->name);
    @endphp

    <div class="page">
        <div class="id-card">
            <div class="content">

                <div class="school">{{ $user->name }}</div>

                <div class="photo" style="background-image:url('{{ $photo }}')"></div>

                <div class="name">{{ $student->name }}</div>

                <div class="info">
                    <div class="row"><span class="label">Father:</span>{{ $student->father_name }}</div>
                    <div class="row"><span class="label">Class:</span>{{ $student->class }}</div>
                    <div class="row"><span class="label">ID No:</span>{{ $student->registration_no }}</div>
                    <div class="row"><span class="label">Roll:</span>{{ $student->roll_no }}</div>
                    <div class="row"><span class="label">Mobile:</span>{{ $student->mobile_no }}</div>
                    <div class="row"><span class="label">Blood:</span>{{ $student->blood_group }}</div>
                </div>

            </div>
        </div>
    </div>
    @endforeach

</body>

</html>
