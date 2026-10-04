<?php

// Centralized Database Configuration for DocOp System
if (!defined('DB_HOST')) define('DB_HOST', env('DB_HOST', '127.0.0.1'));
if (!defined('DB_USER')) define('DB_USER', env('DB_USERNAME', 'root'));
if (!defined('DB_PASS')) define('DB_PASS', env('DB_PASSWORD', ''));
if (!defined('DB_NAME')) define('DB_NAME', env('DB_DATABASE', 'myhmsdb'));

return [
    'host' => DB_HOST,
    'user' => DB_USER,
    'pass' => DB_PASS,
    'name' => DB_NAME,
];
