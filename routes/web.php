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

// Temp route to register fonts
Route::get('/register-fonts', function() {
    try {
        $fontDir = storage_path('fonts');
        if (!file_exists($fontDir)) {
            mkdir($fontDir, 0755, true);
        }

        // 1. Prepare source (Download Nikosh if missing)
        $fontFile = 'Nikosh.ttf';
        $localFontFile = $fontDir . '/' . $fontFile; 
        
        if (!file_exists($localFontFile)) {
            // Attempt to download from a reliable source
            $url = 'https://raw.githubusercontent.com/itcon-bd/bangla-fonts/master/Nikosh.ttf';
            $content = @file_get_contents($url);
            if ($content) {
                file_put_contents($localFontFile, $content);
            } else {
                // Fallback to SolaimanLipi if download fails and it exists
                $solaiman = public_path('fonts/SolaimanLipi.ttf');
                if (file_exists($solaiman)) {
                    copy($solaiman, $localFontFile);
                    $fontFile = 'SolaimanLipi.ttf';
                    return "Nikosh download failed. Using SolaimanLipi fallback. Please try again.";
                }
                return "Failed to download Nikosh and no fallback found.";
            }
        }

        // 2. Register
        $options = new \Dompdf\Options();
        $options->set('fontDir', $fontDir);
        $options->set('fontCache', $fontDir);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new \Dompdf\Dompdf($options);
        $metrics = $dompdf->getFontMetrics();
        
        // Register family name as 'nikosh'
        $metrics->registerFont(
            ['family' => 'nikosh', 'style' => 'normal', 'weight' => 'normal'],
            $localFontFile
        );

        // 3. Look for the metrics file
        $files = scandir($fontDir);
        $foundUfm = null;
        foreach ($files as $file) {
            if (strpos($file, 'nikosh_normal_') === 0 && strpos($file, '.ufm') !== false) {
                $foundUfm = $fontDir . '/' . str_replace('.ufm', '', $file);
                break;
            }
        }

        if (!$foundUfm) {
            return "Failed to generate metrics files. Files: <pre>" . print_r($files, true) . "</pre>";
        }

        // 4. Update cache with Nikosh as the master font
        $fontData = [
            'nikosh' => [
                'normal' => $foundUfm,
                'bold' => $foundUfm,
                'italic' => $foundUfm,
                'bold_italic' => $foundUfm,
            ],
            'solaimanlipi' => [
                'normal' => $foundUfm,
                'bold' => $foundUfm,
                'italic' => $foundUfm,
                'bold_italic' => $foundUfm,
            ],
            'sans-serif' => [
                'normal' => $foundUfm,
                'bold' => $foundUfm,
                'italic' => $foundUfm,
                'bold_italic' => $foundUfm,
            ],
            'serif' => [
                'normal' => $foundUfm,
                'bold' => $foundUfm,
                'italic' => $foundUfm,
                'bold_italic' => $foundUfm,
            ]
        ];

        file_put_contents(
            $fontDir . '/dompdf_font_family_cache.php', 
            "<?php return " . var_export($fontData, true) . ";"
        );

        return "Success! Nikosh font registered and set as default. <br>Path: $foundUfm";

    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/inspect-fonts', function() {
    $fontDir = storage_path('fonts');
    $cacheFile = $fontDir . '/dompdf_font_family_cache.php';
    if (file_exists($cacheFile)) {
        return "<h3>Cache Content:</h3><pre>" . htmlspecialchars(file_get_contents($cacheFile)) . "</pre>";
    }
    return "Cache file not found at $cacheFile";
});

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
