<!DOCTYPE html>
<html lang="bn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* mPDF-compatible CSS for ID cards */
        @page {
            size: 55mm 87mm;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'nikosh', sans-serif;
            font-size: 8pt;
            width: 55mm;
            height: 87mm;
        }

        .page {
            page-break-after: always;
            width: 55mm;
            height: 87mm;
            position: relative;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        /* Background image as img tag - full page */
        .bg-image {
            width: 55mm;
            height: 87mm;
            position: absolute;
            top: 0;
            left: 0;
        }

        /* Content wrapper - positioned absolutely on top */
        .content-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 55mm;
            height: 87mm;
        }

        .content {
            width: 100%;
            height: 100%;
            text-align: center;
            padding-top: 6mm;
        }

        .photo {
            width: 23mm;
            height: 28mm;
            margin: 10mm auto 3mm;
            border: 2pt solid #333;
            overflow: hidden;
            display: block;
        }

        .photo img {
            width: 23mm;
            height: 28mm;
            display: block;
        }

        .name {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 3mm;
            text-align: center;
            color: #c41e3a;
        }

        .info {
            font-size: 8pt;
            text-align: left;
            padding: 0 6mm;
        }

        /* Use table for better alignment in mPDF */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            vertical-align: top;
            padding: 0;
            padding-bottom: 1mm;
            line-height: 1.3;
        }

        .label-td {
            width: 14mm;
            font-weight: bold;
            white-space: nowrap;
            text-align: right;
        }

        .colon-td {
            width: 2mm;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

@foreach ($students as $student)
@php
    $photoSrc = 'https://ui-avatars.com/api/?name=' . urlencode($student->name);
    
    if ($student->image) {
        $path = storage_path('app/public/' . $student->image);
        
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
    @if($customBackground)
    <img src="{{ $customBackground }}" class="bg-image" alt="">
    @endif
    
    <div class="content-wrapper">
        <div class="content">
            <div class="photo">
                <img src="{{ $photoSrc }}" alt="{{ $student->name }}">
            </div>

            <div class="name">{{ $student->name }}</div>

            <div class="info">
                <table class="info-table">
                    <tr>
                        <td class="label-td">Father</td>
                        <td class="colon-td">:</td>
                        <td>{{ $student->father_name }}</td>
                    </tr>
                    <tr>
                        <td class="label-td">Class</td>
                        <td class="colon-td">:</td>
                        <td>{{ $student->class }}{{ $student->section ? ' - ' . $student->section : '' }}</td>
                    </tr>
                    <tr>
                        <td class="label-td">ID No</td>
                        <td class="colon-td">:</td>
                        <td>{{ $student->registration_no }}</td>
                    </tr>
                    <tr>
                        <td class="label-td">Roll</td>
                        <td class="colon-td">:</td>
                        <td>{{ $student->roll_no }}</td>
                    </tr>
                    <tr>
                        <td class="label-td">Mobile</td>
                        <td class="colon-td">:</td>
                        <td>{{ $student->mobile_no }}</td>
                    </tr>
                    <tr>
                        <td class="label-td">Blood</td>
                        <td class="colon-td">:</td>
                        <td>{{ $student->blood_group }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach

</body>
</html>
