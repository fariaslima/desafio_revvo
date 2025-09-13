<?php

spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/app/';
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
        return;
    }
    // Search recursively in subdirs
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base_dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            if ($filename === $class) {
                require $file->getPathname();
                return;
            }
        }
    }
});

require_once __DIR__ . '/config/database.php';

session_start();
