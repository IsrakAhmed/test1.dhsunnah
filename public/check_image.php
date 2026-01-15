<?php
// Quick diagnostic script to check student image paths
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Find the student with the problematic image
$student = DB::table('students')
    ->where('image', 'like', '%cc8d6e95%')
    ->orWhere('image', 'like', '%cc8d6e95-e257-4002-b665-88e7cd85c748%')
    ->first();

if ($student) {
    echo "<h3>Student Found:</h3>";
    echo "<pre>";
    echo "ID: " . $student->id . "\n";
    echo "Name: " . $student->name . "\n";
    echo "Image Path (from DB): " . $student->image . "\n";
    echo "</pre>";
    
    echo "<h3>Expected URL:</h3>";
    echo "<pre>" . url('storage/' . $student->image) . "</pre>";
    
    echo "<h3>File Checks:</h3>";
    $path1 = public_path('storage/' . $student->image);
    $path2 = storage_path('app/public/' . $student->image);
    
    echo "<pre>";
    echo "Public path: $path1\n";
    echo "Exists: " . (file_exists($path1) ? 'YES' : 'NO') . "\n\n";
    
    echo "Storage path: $path2\n";
    echo "Exists: " . (file_exists($path2) ? 'YES' : 'NO') . "\n";
    echo "</pre>";
    
    echo "<h3>Image Preview:</h3>";
    echo "<img src='" . asset('storage/' . $student->image) . "' style='max-width: 200px; border: 2px solid red;' onerror=\"this.style.border='2px solid red'; this.alt='IMAGE FAILED TO LOAD';\">";
} else {
    echo "<h3>No student found with image containing 'cc8d6e95'</h3>";
    
    // Show recent students
    echo "<h3>Recent 5 Students:</h3>";
    $recent = DB::table('students')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get(['id', 'name', 'image', 'created_at']);
    
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Image Path</th><th>Created</th><th>Preview</th></tr>";
    foreach ($recent as $s) {
        echo "<tr>";
        echo "<td>{$s->id}</td>";
        echo "<td>{$s->name}</td>";
        echo "<td>{$s->image}</td>";
        echo "<td>{$s->created_at}</td>";
        echo "<td><img src='" . asset('storage/' . $s->image) . "' style='max-width: 100px;' onerror=\"this.alt='FAILED';\"></td>";
        echo "</tr>";
    }
    echo "</table>";
}
