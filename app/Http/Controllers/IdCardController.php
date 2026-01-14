<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf;

class IdCardController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->get();

        $dataByUser = Student::whereNotNull('class')
            ->select('user_id', 'class', 'section')
            ->distinct()
            ->orderBy('class')
            ->orderBy('section')
            ->get()
            ->groupBy('user_id')
            ->map(function(Collection $students) {
                return $students->groupBy('class')->map(function(Collection $classStudents) {
                    return $classStudents->pluck('section')->filter()->unique()->values();
                });
            })
            ->toArray();

        //$sampleStudent = Student::with('user')->latest()->first();

        $sampleStudentsByClass = Student::with('user')
            ->whereNotNull('class')
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('class')
            ->map(fn(Collection $students) => $students->first())
            ->toArray();


        return view('admin.idcard.index', [
            'users' => $users,
            'dataByUser' => $dataByUser,
            'sampleStudents' => $sampleStudentsByClass,
        ]);
    }

    public function generate(Request $request)
    {
        // Simplified validation - if custom_design is uploaded, don't require 'design'
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'class' => [
                'nullable',
                'string',
                'max:50',
                Rule::exists('students', 'class')->where(fn($query) => $query->where('user_id', $request->input('user_id'))),
            ],
            'section' => 'nullable|string|max:50',
            'design' => 'nullable|in:design1,design2',
            'custom_design' => 'nullable|image|mimes:png,jpeg,jpg|max:5120',
        ]);

        $user = User::findOrFail($validated['user_id']);

        $studentsQuery = Student::where('user_id', $validated['user_id']);

        if (!empty($validated['class'])) {
            $studentsQuery->where('class', $validated['class']);
        }

        if (!empty($validated['section'])) {
            $studentsQuery->where('section', $validated['section']);
        }

        $students = $studentsQuery->get();

        // Handle custom background upload
        $customBackgroundPath = null;
        $customBackground = null;

        if ($request->hasFile('custom_design')) {
            // Store the file
            $customBackgroundPath = $request->file('custom_design')->store('idcard_backgrounds', 'public');

            // Generate the full URL
            $customBackground = asset('storage/' . $customBackgroundPath);

            // Generate Base64 for reliable preview
            $fullPath = storage_path('app/public/' . $customBackgroundPath);
             if (file_exists($fullPath)) {
                $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                $data = file_get_contents($fullPath);
                $customBackground = 'data:image/' . $type . ';base64,' . base64_encode($data);
             }

            // Log for debugging
            \Log::info('Custom background uploaded', [
                'path' => $customBackgroundPath,
                'url' => $customBackground, // Now Base64
                'file_exists' => file_exists($fullPath)
            ]);
        }

        // Determine which design to use
        if ($customBackgroundPath) {
            $design = 'custom';
            $designLabel = 'Custom Upload';
        } else {
            $design = $validated['design'] ?? 'design1';
            $designLabel = match ($design) {
                'design2' => 'Classic Orange',
                default => 'Professional Blue',
            };
        }

        $selectedClass = $validated['class'] ?? null;
        $selectedSection = $validated['section'] ?? null;

        return view('admin.idcard.preview', [
            'user' => $user,
            'students' => $students,
            'design' => $design,
            'selectedClass' => $selectedClass,
            'selectedSection' => $selectedSection,
            'designLabel' => $designLabel,
            'customBackground' => $customBackground,
            'customBackgroundPath' => $customBackgroundPath,
        ]);
    }

    public function print(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'class' => 'nullable|string',
            'section' => 'nullable|string',
            'design' => 'required|string',
            'customBackground' => 'nullable|string',
            'customBackgroundPath' => 'nullable|string',
        ]);

        $user = User::findOrFail($request->user_id);

        $students = Student::where('user_id', $request->user_id)
            ->when($request->class, fn($q) => $q->where('class', $request->class))
            ->when($request->section, fn($q) => $q->where('section', $request->section))
            ->get();

        $backgroundPath = null;
        if ($request->customBackgroundPath) {
             $fullPath = storage_path('app/public/' . $request->customBackgroundPath);
             
             if (file_exists($fullPath)) {
                $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                $data = file_get_contents($fullPath);
                $backgroundPath = 'data:image/' . $type . ';base64,' . base64_encode($data);
             }
        } elseif ($request->customBackground) {
            $relativePath = str_replace(asset('storage/'), '', $request->customBackground);
            $fullPath = storage_path('app/public/' . $relativePath);

            if (!file_exists($fullPath)) {
                $permissionPath = public_path(str_replace(asset(''), '', $request->customBackground));
                if (file_exists($permissionPath)) {
                    $fullPath = $permissionPath;
                }
            }

            if (file_exists($fullPath)) {
                $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                $data = file_get_contents($fullPath);
                $backgroundPath = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }

        // Configure mPDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => [55, 87],
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 0,
            'orientation' => 'P',
            'tempDir' => storage_path('app/temp'),
            'fontDir' => [storage_path('fonts')],
            'fontdata' => [
                'nikosh' => [
                    'R' => 'Nikosh.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font' => 'nikosh',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        // Process each student
        foreach ($students as $index => $student) {
            if ($index > 0) {
                $mpdf->AddPage();
            }

            // Set background image for this page using HTML header with proper positioning
            if ($backgroundPath) {
                $headerHtml = '<div style="position: absolute; top: 0; left: 0; width: 55mm; height: 87mm; overflow: hidden;">
                    <img src="' . $backgroundPath . '" style="width: 55mm; height: 87mm; display: block;" />
                </div>';
                $mpdf->SetHTMLHeader($headerHtml);
                $mpdf->SetHTMLFooter(''); // Clear footer to prevent overlap
            }

            // Prepare student photo
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

            // Render content for this student with relative positioning
            $html = view('admin.idcard.print_mpdf_single', [
                'student' => $student,
                'photoSrc' => $photoSrc,
            ])->render();

            $mpdf->WriteHTML($html);
        }

        return $mpdf->Output('id-cards.pdf', 'D');
    }

    public function printBrowsershot(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'class' => 'nullable|string',
            'section' => 'nullable|string',
            'design' => 'required|string',
            'customBackground' => 'nullable|string',
            'customBackgroundPath' => 'nullable|string',
        ]);

        $user = User::findOrFail($request->user_id);

        $students = Student::where('user_id', $request->user_id)
            ->when($request->class, fn($q) => $q->where('class', $request->class))
            ->when($request->section, fn($q) => $q->where('section', $request->section))
            ->get();

        $backgroundPath = null;
        if ($request->customBackgroundPath) {
             $fullPath = storage_path('app/public/' . $request->customBackgroundPath);
             
             if (file_exists($fullPath)) {
                $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                $data = file_get_contents($fullPath);
                $backgroundPath = 'data:image/' . $type . ';base64,' . base64_encode($data);
             }
        } elseif ($request->customBackground) {
            $relativePath = str_replace(asset('storage/'), '', $request->customBackground);
            $fullPath = storage_path('app/public/' . $relativePath);

            if (!file_exists($fullPath)) {
                $permissionPath = public_path(str_replace(asset(''), '', $request->customBackground));
                if (file_exists($permissionPath)) {
                    $fullPath = $permissionPath;
                }
            }

            if (file_exists($fullPath)) {
                $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                $data = file_get_contents($fullPath);
                $backgroundPath = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }

        // Render the view to HTML
        $html = view('admin.idcard.print_browsershot', [
            'user' => $user,
            'students' => $students,
            'design' => $request->design,
            'customBackground' => $backgroundPath,
        ])->render();

        // Generate PDF using Snappy (wkhtmltopdf)
        return SnappyPdf::loadHTML($html)
            ->setOption('page-width', '55mm')
            ->setOption('page-height', '87mm')
            ->setOption('margin-top', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0)
            ->setOption('enable-local-file-access', true)
            ->setOption('encoding', 'UTF-8')
            ->download('id-cards.pdf');
    }
}
