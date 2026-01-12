<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $classFilter = $request->query('class');

        $studentsQuery = Student::where('user_id', auth()->id());

        if ($classFilter) {
            $studentsQuery->where('class', $classFilter);
        }

        $students = $studentsQuery->paginate(20)->withQueryString();

        $classes = Student::where('user_id', auth()->id())
            ->select('class')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        return view('user.dashboard', [
            'students' => $students,
            'classes' => $classes,
            'classFilter' => $classFilter,
        ]);
    }

    public function create()
    {
        return view('student.create');
    }

    public function edit(Student $student)
    {
        $this->ensureStudentOwner($student);

        return view('student.edit', compact('student'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:15',
            'class' => 'required|string|max:50',
            'section' => 'required|string|max:50',
            'roll_no' => 'required|string|max:50',
            'registration_no' => 'nullable|string|max:100',
            'blood_group' => 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->compressAndStoreImage($request->file('image'));
        }

        Student::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'father_name' => $validated['father_name'],
            'mobile_no' => $validated['mobile_no'],
            'class' => $validated['class'],
            'section' => $validated['section'],
            'roll_no' => $validated['roll_no'],
            'registration_no' => $validated['registration_no'] ?? null,
            'blood_group' => $validated['blood_group'] ?? null,
            'image' => $imagePath,
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Student added successfully.');
    }

    public function update(Request $request, Student $student)
    {
        $this->ensureStudentOwner($student);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:15',
            'class' => 'required|string|max:50',
            'section' => 'required|string|max:50',
            'roll_no' => 'required|string|max:50',
            'registration_no' => 'nullable|string|max:100',
            'blood_group' => 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if ($request->hasFile('image')) {
            if ($student->image) {
                Storage::disk('public')->delete($student->image);
            }

            $validated['image'] = $this->compressAndStoreImage($request->file('image'));
        }

        $student->update($validated);

        return redirect()->route('user.dashboard')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $this->ensureStudentOwner($student);

        if ($student->image) {
            Storage::disk('public')->delete($student->image);
        }

        $student->delete();

        return redirect()->route('user.dashboard')->with('success', 'Student deleted successfully.');
    }

    /**
     * Resize + convert uploaded image via Intervention/Image
     */
    private function compressAndStoreImage(UploadedFile $uploadedFile): string
    {
        $manager = ImageManager::gd();

        $image = $manager->read($uploadedFile)
            ->orient()
            ->scaleDown(600, 600);

        $maxFileSize = 2 * 1024 * 1024;
        $quality = 85;
        $encoded = $image->toWebp(quality: $quality, strip: true);

        while ($encoded->size() > $maxFileSize && $quality > 40) {
            $quality -= 5;
            $encoded = $image->toWebp(quality: $quality, strip: true);
        }

        $path = 'students/' . Str::uuid() . '.webp';
        Storage::disk('public')->put($path, $encoded->toString());

        return $path;
    }

    private function ensureStudentOwner(Student $student): void
    {
        abort_unless($student->user_id === auth()->id(), 403);
    }
}
