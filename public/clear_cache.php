<?php
/**
 * Laravel Cache Clear Script (No CLI Required)
 * This script clears all Laravel caches via web browser
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "<!DOCTYPE html>";
echo "<html><head><title>Laravel Cache Clear</title>";
echo "<style>
    body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
    h1 { color: #5a5af3; }
    .success { color: green; padding: 10px; background: #e8f5e9; border-left: 4px solid green; margin: 10px 0; }
    .error { color: red; padding: 10px; background: #ffebee; border-left: 4px solid red; margin: 10px 0; }
    .info { color: #0066cc; padding: 10px; background: #e3f2fd; border-left: 4px solid #0066cc; margin: 10px 0; }
    .command { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .command h3 { margin-top: 0; color: #333; }
    hr { margin: 30px 0; border: none; border-top: 2px solid #eee; }
</style>";
echo "</head><body>";

echo "<h1>🧹 Laravel Cache Clear</h1>";
echo "<p>Clearing all caches for: <strong>" . env('APP_URL') . "</strong></p>";
echo "<hr>";

$commands = [
    'config:clear' => 'Clear Configuration Cache',
    'cache:clear' => 'Clear Application Cache',
    'route:clear' => 'Clear Route Cache',
    'view:clear' => 'Clear Compiled Views',
    'event:clear' => 'Clear Event Cache',
];

$results = [];

foreach ($commands as $command => $description) {
    echo "<div class='command'>";
    echo "<h3>📦 {$description}</h3>";
    echo "<p>Running: <code>php artisan {$command}</code></p>";
    
    try {
        $exitCode = Artisan::call($command);
        $output = Artisan::output();
        
        if ($exitCode === 0) {
            echo "<div class='success'>✅ Success!</div>";
            if (trim($output)) {
                echo "<pre style='background: #f9f9f9; padding: 10px; border-radius: 3px;'>" . htmlspecialchars($output) . "</pre>";
            }
            $results[$command] = 'success';
        } else {
            echo "<div class='error'>❌ Failed with exit code: {$exitCode}</div>";
            if (trim($output)) {
                echo "<pre style='background: #fff3cd; padding: 10px; border-radius: 3px;'>" . htmlspecialchars($output) . "</pre>";
            }
            $results[$command] = 'failed';
        }
    } catch (Exception $e) {
        echo "<div class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        $results[$command] = 'error';
    }
    
    echo "</div>";
}

echo "<hr>";

// Additional manual cache clearing
echo "<div class='command'>";
echo "<h3>🗑️ Manual Cache Directory Cleanup</h3>";

$cacheDirs = [
    'bootstrap/cache' => storage_path('../bootstrap/cache'),
    'storage/framework/cache' => storage_path('framework/cache/data'),
    'storage/framework/views' => storage_path('framework/views'),
];

foreach ($cacheDirs as $name => $path) {
    if (is_dir($path)) {
        $files = glob($path . '/*');
        $deleted = 0;
        
        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== '.gitignore') {
                if (@unlink($file)) {
                    $deleted++;
                }
            }
        }
        
        echo "<p>📁 <strong>{$name}</strong>: Deleted {$deleted} cache files</p>";
    }
}

echo "</div>";

echo "<hr>";

// Summary
$successCount = count(array_filter($results, fn($r) => $r === 'success'));
$totalCount = count($results);

echo "<div class='info'>";
echo "<h2>📊 Summary</h2>";
echo "<p><strong>{$successCount}/{$totalCount}</strong> cache clear commands completed successfully.</p>";
echo "</div>";

echo "<hr>";

echo "<div class='success' style='font-size: 18px; text-align: center;'>";
echo "<strong>🎉 All Done!</strong><br>";
echo "Your Laravel application cache has been cleared.";
echo "</div>";

echo "<p style='text-align: center; margin-top: 30px;'>";
echo "<a href='/' style='display: inline-block; padding: 10px 20px; background: #5a5af3; color: white; text-decoration: none; border-radius: 5px;'>← Back to Home</a> ";
echo "<a href='clear_cache.php' style='display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;'>🔄 Run Again</a>";
echo "</p>";

echo "<hr>";
echo "<p style='text-align: center; color: #999; font-size: 12px;'>";
echo "⚠️ <strong>Security Note:</strong> Delete this file after use or restrict access to prevent unauthorized cache clearing.";
echo "</p>";

echo "</body></html>";
