<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class StudentsExport implements FromCollection, WithHeadings, WithDrawings, WithColumnWidths, WithStyles
{
    protected $students;
    protected $userId;

    public function __construct($students, $userId)
    {
        $this->students = $students;
        $this->userId = $userId;
    }

    public function collection()
    {
        // Map students data to collection
        return $this->students->map(function ($student, $index) {
            return [
                'sl' => $index + 1,
                'name' => $student->name,
                'father_name' => $student->father_name,
                'mobile_no' => $student->mobile_no,
                'class' => $student->class,
                'section' => $student->section,
                'roll_no' => $student->roll_no,
                'registration_no' => $student->registration_no ?? 'N/A',
                'blood_group' => $student->blood_group ?? 'N/A',
                'image' => '', // Leave empty for image column
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ক্রমিক',
            'নাম',
            'পিতার নাম',
            'মোবাইল নম্বর',
            'ক্লাস',
            'সেকশন',
            'রোল নং',
            'রেজিস্ট্রেশন নং',
            'রক্তের গ্রুপ',
            'ছবি',
        ];
    }

    public function drawings()
    {
        $drawings = [];
        
        foreach ($this->students as $index => $student) {
            if ($student->image && file_exists(storage_path('app/public/' . $student->image))) {
                $drawing = new Drawing();
                $drawing->setName('Student Image');
                $drawing->setDescription('Student Image');
                $drawing->setPath(storage_path('app/public/' . $student->image));
                $drawing->setHeight(50);
                $drawing->setWidth(50);
                // Position: Column J (image column) and row index + 2 (for header)
                $drawing->setCoordinates('J' . ($index + 2));
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(5);
                
                $drawings[] = $drawing;
            }
        }
        
        return $drawings;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ক্রমিক
            'B' => 20,  // নাম
            'C' => 20,  // পিতার নাম
            'D' => 15,  // মোবাইল নম্বর
            'E' => 10,  // ক্লাস
            'F' => 10,  // সেকশন
            'G' => 10,  // রোল নং
            'H' => 18,  // রেজিস্ট্রেশন নং
            'I' => 15,  // রক্তের গ্রুপ
            'J' => 12,  // ছবি
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D3D3D3'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Set row height for all data rows to accommodate images
        foreach ($this->students as $index => $student) {
            $rowNumber = $index + 2;
            $sheet->getRowDimension($rowNumber)->setRowHeight(60);
        }

        return [];
    }
}
