<?php
/**
 * Fix Storage Symlink for Production Server
 * This script recreates the storage symlink that connects public/storage to storage/app/public
 */

echo "<h2>Storage Symlink Fix Script</h2>";
echo "<hr>";

$target = realpath(__DIR__ . '/../storage/app/public');
$link = __DIR__ . '/storage';

echo "<h3>Step 1: Check Paths</h3>";
echo "<pre>";
echo "Target (storage/app/public): $target\n";
echo "Link (public/storage): $link\n";
echo "</pre>";

// Check if target exists
if (!file_exists($target)) {
    echo "<p style='color: red;'>❌ ERROR: Target directory does not exist!</p>";
    exit;
}
echo "<p style='color: green;'>✅ Target directory exists</p>";

echo "<h3>Step 2: Check Current Link Status</h3>";
if (file_exists($link)) {
    if (is_link($link)) {
        $currentTarget = readlink($link);
        echo "<p style='color: orange;'>⚠️ Symlink already exists</p>";
        echo "<pre>Current target: $currentTarget</pre>";
        
        if ($currentTarget === $target) {
            echo "<p style='color: green;'>✅ Symlink is correct! The issue might be elsewhere.</p>";
            
            // Test if we can access a file through the symlink
            echo "<h3>Step 3: Test Symlink Access</h3>";
            $testFiles = glob($target . '/students/*.webp');
            if (!empty($testFiles)) {
                $testFile = basename($testFiles[0]);
                $throughSymlink = $link . '/students/' . $testFile;
                echo "<pre>Test file: $testFile\n";
                echo "Accessible through symlink: " . (file_exists($throughSymlink) ? 'YES ✅' : 'NO ❌') . "</pre>";
            }
            exit;
        }
        
        echo "<p style='color: orange;'>⚠️ Symlink points to wrong location. Removing...</p>";
        if (unlink($link)) {
            echo "<p style='color: green;'>✅ Old symlink removed</p>";
        } else {
            echo "<p style='color: red;'>❌ Failed to remove old symlink. Please delete manually via FTP.</p>";
            exit;
        }
    } else {
        echo "<p style='color: red;'>❌ ERROR: 'public/storage' exists but is NOT a symlink (it's a directory or file)</p>";
        echo "<p><strong>Solution:</strong> You need to manually:</p>";
        echo "<ol>";
        echo "<li>Via FTP, rename <code>public/storage</code> to <code>public/storage_backup</code></li>";
        echo "<li>Then run this script again</li>";
        echo "</ol>";
        exit;
    }
} else {
    echo "<p style='color: orange;'>⚠️ Symlink does not exist. Will create it...</p>";
}

echo "<h3>Step 3: Create Symlink</h3>";

// Try to create symlink
if (symlink($target, $link)) {
    echo "<p style='color: green; font-size: 18px;'><strong>✅ SUCCESS! Symlink created successfully!</strong></p>";
    
    // Verify it works
    echo "<h3>Step 4: Verify Symlink</h3>";
    if (is_link($link) && readlink($link) === $target) {
        echo "<p style='color: green;'>✅ Symlink verification passed!</p>";
        
        // Test file access
        $testFiles = glob($target . '/students/*.webp');
        if (!empty($testFiles)) {
            $testFile = basename($testFiles[0]);
            $throughSymlink = $link . '/students/' . $testFile;
            echo "<pre>Test file: $testFile\n";
            echo "Accessible through symlink: " . (file_exists($throughSymlink) ? 'YES ✅' : 'NO ❌') . "</pre>";
            
            if (file_exists($throughSymlink)) {
                echo "<h3>🎉 All Done! Your images should now work!</h3>";
                echo "<p>Test URL: <a href='/storage/students/$testFile' target='_blank'>/storage/students/$testFile</a></p>";
            }
        }
    } else {
        echo "<p style='color: red;'>❌ Symlink verification failed</p>";
    }
} else {
    echo "<p style='color: red;'>❌ ERROR: Failed to create symlink</p>";
    echo "<p>This usually happens when:</p>";
    echo "<ul>";
    echo "<li>Your hosting doesn't allow symlinks (some shared hosting)</li>";
    echo "<li>PHP doesn't have permission to create symlinks</li>";
    echo "</ul>";
    
    echo "<h3>Alternative Solution:</h3>";
    echo "<p>If symlinks don't work on your server, you have two options:</p>";
    echo "<ol>";
    echo "<li><strong>Contact your hosting provider</strong> to create the symlink manually</li>";
    echo "<li><strong>Change the code</strong> to store images directly in <code>public/students/</code> instead (I can help with this)</li>";
    echo "</ol>";
}

echo "<hr>";
echo "<p><a href='check_image.php'>← Back to Image Check</a></p>";
