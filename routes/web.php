<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminAuthenticate']);
Route::get('/user/login', [AuthController::class, 'userLogin'])->name('user.login');
Route::post('/user/login', [AuthController::class, 'userAuthenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\IdCardController;

Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // User Management Routes
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::patch('/admin/users/{id}/toggle', [AdminUserController::class, 'toggleStatus'])->name('admin.users.toggle');
    Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    // School Routes
    Route::get('/admin/school', [SchoolController::class, 'index'])->name('admin.school.index');
    Route::get('/admin/school/{userId}/students', [SchoolController::class, 'showStudents'])->name('admin.school.students');
    Route::get('/admin/school/{userId}/export', [SchoolController::class, 'exportStudents'])->name('admin.school.export');

    // ID Card Routes
    Route::get('/admin/idcard', [IdCardController::class, 'index'])->name('admin.idcard.index');
    Route::post('/admin/idcard/generate', [IdCardController::class, 'generate'])->name('admin.idcard.generate');

    Route::post('/admin/idcard/print', [IdCardController::class, 'print'])
    ->name('admin.idcard.print');
});

use App\Http\Controllers\StudentController;

Route::middleware(['user'])->group(function () {
    Route::get('/user/dashboard', [StudentController::class, 'index'])->name('user.dashboard');

    // Student Entry Routes
    Route::get('/student/entry', [StudentController::class, 'create'])->name('student.create');
    Route::post('/student/entry', [StudentController::class, 'store'])->name('student.store');
    Route::get('/student/{student}/edit', [StudentController::class, 'edit'])->name('student.edit');
    Route::put('/student/{student}', [StudentController::class, 'update'])->name('student.update');
    Route::delete('/student/{student}', [StudentController::class, 'destroy'])->name('student.destroy');
});
