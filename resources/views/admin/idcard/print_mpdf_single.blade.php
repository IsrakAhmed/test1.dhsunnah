<div style="width: 55mm; height: 87mm; position: relative; z-index: 1;">
    <div style="text-align: center; padding-top: 17mm; position: relative;">
        <div style="width: 23mm; height: 28mm; margin: 0 auto 2mm; border: 2pt solid #333; overflow: hidden; background: white;">
            <img src="{{ $photoSrc }}" style="width: 23mm; height: 28mm; display: block;" />
        </div>

        <div style="font-size: 11pt; font-weight: bold; margin-bottom: 2mm; color: #c41e3a; text-shadow: 0 0 3px white, 0 0 6px white;">
            {{ $student->name }}
        </div>

        <table style="width: 46mm; margin: 0 auto; border-collapse: collapse; font-size: 8pt;">
            <tr>
                <td style="width: 14mm; padding-bottom: 0.5mm; text-shadow: 0 0 2px white; text-align: left;">Father</td>
                <td style="width: 2mm; padding-bottom: 0.5mm; text-shadow: 0 0 2px white; text-align: center;">:</td>
                <td style="padding-bottom: 0.5mm; text-shadow: 0 0 2px white;">{{ $student->father_name }}</td>
            </tr>
            <tr>
                <td style="width: 14mm; padding-bottom: 0.5mm; text-shadow: 0 0 2px white; text-align: left;">Class</td>
                <td style="width: 2mm; padding-bottom: 0.5mm; text-shadow: 0 0 2px white; text-align: center;">:</td>
                <td style="padding-bottom: 0.5mm; text-shadow: 0 0 2px white;">{{ $student->class }}{{ $student->section ? ' - ' . $student->section : '' }}</td>
            </tr>
            <tr>
                <td style="width: 14mm; padding-bottom: 0.5mm; text-shadow: 0 0 2px white; text-align: left;">ID No</td>
                <td style="width: 2mm; padding-bottom: 0.5mm; text-shadow: 0 0 2px white; text-align: center;">:</td>
                <td style="padding-bottom: 0.5mm; text-shadow: 0 0 2px white;">{{ $student->registration_no }}</td>
            </tr>
            <tr>
                <td style="width: 14mm; padding-bottom: 0.5mm; text-shadow: 0 0 2px white; text-align: left;">Roll</td>
                <td style="width: 2mm; padding-bottom: 0.5mm; text-shadow: 0 0 2px white; text-align: center;">:</td>
                <td style="padding-bottom: 0.5mm; text-shadow: 0 0 2px white;">{{ $student->roll_no }}</td>
            </tr>
            <tr>
                <td style="width: 14mm; padding-bottom: 0.5mm; text-shadow: 0 0 2px white; text-align: left;">Mobile</td>
                <td style="width: 2mm; padding-bottom: 0.5mm; text-shadow: 0 0 2px white; text-align: center;">:</td>
                <td style="padding-bottom: 0.5mm; text-shadow: 0 0 2px white;">{{ $student->mobile_no }}</td>
            </tr>
            <tr>
                <td style="width: 14mm; padding-bottom: 0.5mm; text-shadow: 0 0 2px white; text-align: left;">Blood</td>
                <td style="width: 2mm; padding-bottom: 0.5mm; text-shadow: 0 0 2px white; text-align: center;">:</td>
                <td style="padding-bottom: 0.5mm; text-shadow: 0 0 2px white;">{{ $student->blood_group }}</td>
            </tr>
        </table>
    </div>
</div>  
