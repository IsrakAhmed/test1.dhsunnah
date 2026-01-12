<?php
echo "GD: " . (extension_loaded('gd') ? 'Yes' : 'No') . "\n";
echo "Storage Link: " . (file_exists('public/storage') ? 'Yes' : 'No') . "\n";
