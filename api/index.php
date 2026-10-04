<?php

// Vercel Serverless environment has a read-only filesystem except /tmp
// Initialize writable storage directory structure in /tmp
$storagePath = '/tmp/storage';
$subDirectories = [
    $storagePath . '/app',
    $storagePath . '/app/public',
    $storagePath . '/framework',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
];

foreach ($subDirectories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Set storage path for Laravel
putenv('APP_STORAGE=' . $storagePath);
$_ENV['APP_STORAGE'] = $storagePath;
$_SERVER['APP_STORAGE'] = $storagePath;

// Forward execution to standard public/index.php
require __DIR__ . '/../public/index.php';
