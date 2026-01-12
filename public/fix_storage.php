<?php
try {
    // 1. Check if symlink function exists
    if (!function_exists('symlink')) {
        echo "Error: symlink() function is disabled or not available.<br>";
    } else {
        echo "symlink() function is available.<br>";
    }

    $target = __DIR__ . '/../storage/app/public';
    $link = __DIR__ . '/storage';

    echo "Target: $target<br>";
    echo "Link: $link<br>";

    // 2. Check if target exists
    if (!file_exists($target)) {
        echo "Error: Target directory does not exist!<br>";
    } else {
        echo "Target directory exists.<br>";
    }

    // 3. Check if link already exists
    if (file_exists($link)) {
        echo "Link path already exists. Checking type...<br>";
        if (is_link($link)) {
            echo "It is a symbolic link. Target: " . readlink($link) . "<br>";
            // Optional: delete and recreate
            // unlink($link);
        } elseif (is_dir($link)) {
            echo "Warning: It is a DIRECTORY, not a link. This is the problem.<br>";
            echo "Attempting to rename directory to storage_backup_temp...<br>";
            if (rename($link, $link . '_backup_temp_' . time())) {
                echo "Renamed successfully.<br>";
                // Now try to link
                if (symlink($target, $link)) {
                    echo "SUCCESS: Storage symlink created!<br>";
                } else {
                    echo "Error: Failed to create symlink.<br>";
                }
            } else {
                echo "Error: Failed to rename existing storage directory.<br>";
            }
        } else {
            echo "It is a file.<br>";
        }
    } else {
        echo "Link does not exist. Creating...<br>";
        if (symlink($target, $link)) {
            echo "SUCCESS: Storage symlink created!<br>";
        } else {
            echo "Error: Failed to create symlink.<br>";
        }
    }

} catch (Throwable $e) {
    echo "Exception: " . $e->getMessage();
}
