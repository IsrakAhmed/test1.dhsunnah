<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class IdCardController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->get();

        $classesByUser = Student::whereNotNull('class')
            ->select('user_id', 'class')
            ->distinct()
            ->orderBy('class')
            ->get()
            ->groupBy('user_id')
            ->map(fn(Collection $classes) => $classes->pluck('class')->values())
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
            'classesByUser' => $classesByUser,
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
            'design' => 'nullable|in:design1,design2',
            'custom_design' => 'nullable|image|mimes:png|max:5120',
        ]);

        $user = User::findOrFail($validated['user_id']);

        $studentsQuery = Student::where('user_id', $validated['user_id']);

        if (!empty($validated['class'])) {
            $studentsQuery->where('class', $validated['class']);
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

            // Log for debugging
            \Log::info('Custom background uploaded', [
                'path' => $customBackgroundPath,
                'url' => $customBackground,
                'file_exists' => Storage::disk('public')->exists($customBackgroundPath)
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

        return view('admin.idcard.preview', [
            'user' => $user,
            'students' => $students,
            'design' => $design,
            'selectedClass' => $selectedClass,
            'designLabel' => $designLabel,
            'customBackground' => $customBackground,
        ]);
    }

    public function print(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'class' => 'nullable|string',
            'design' => 'required|string',
            'customBackground' => 'nullable|string',
        ]);

        $user = User::findOrFail($request->user_id);

        $students = Student::where('user_id', $request->user_id)
            ->when($request->class, fn($q) => $q->where('class', $request->class))
            ->get();

        $backgroundPath = null;

        if ($request->customBackground) {
            // Convert full URL to public path
            $backgroundPath = public_path(
                str_replace(asset(''), '', $request->customBackground)
            );
        }


        $pdf = Pdf::loadView('admin.idcard.print', [
            'user' => $user,
            'students' => $students,
            'design' => $request->design,
            'customBackground' => $backgroundPath,
        ])->setPaper([0, 0, 155.9, 246.6], 'portrait');


        return $pdf->download('id-cards.pdf');
    }
}
