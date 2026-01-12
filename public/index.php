<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Auto-create storage symlink for shared hosting (no command line access)
$storageLink = __DIR__.'/storage';
$storagePath = __DIR__.'/../storage/app/public';
if (!file_exists($storageLink) && file_exists($storagePath)) {
    @symlink($storagePath, $storageLink);
}

$app->handleRequest(Request::capture());

