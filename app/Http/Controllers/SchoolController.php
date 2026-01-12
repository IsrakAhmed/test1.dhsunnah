<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Exports\StudentsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SchoolController extends Controller
{
    public function index()
    {
        // Get all users except admin
        $users = User::where('role', 'user')->paginate(15);
        return view('admin.school.index', compact('users'));
    }

    public function showStudents($userId)
    {
        // Get the user
        $user = User::findOrFail($userId);
        
        // Get all students for this user with pagination
        $students = Student::where('user_id', $userId)->paginate(20);
        
        return view('admin.school.students', compact('user', 'students'));
    }

    public function exportStudents($userId)
    {
        // Get the user
        $user = User::findOrFail($userId);
        
        // Get all students for this user
        $students = Student::where('user_id', $userId)->get();
        
        // Generate filename
        $filename = 'students_' . $user->name . '_' . date('Y-m-d') . '.xlsx';
        
        // Download Excel file
        return Excel::download(new StudentsExport($students, $userId), $filename);
    }
}
