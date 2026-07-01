<?php

$vendorPath = __DIR__ . '/../vendor';

if (! is_dir($vendorPath)) {
    return;
}

$removeDirectory = static function (string $directory) use (&$removeDirectory): void {
    foreach (scandir($directory) as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        $path = $directory . DIRECTORY_SEPARATOR . $item;

        if (is_dir($path)) {
            $removeDirectory($path);
        } else {
            unlink($path);
        }
    }

    rmdir($directory);
};

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($vendorPath, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::CHILD_FIRST,
);

foreach ($iterator as $file) {
    if ($file->getBasename() === '.git' && $file->isDir()) {
        $removeDirectory($file->getPathname());
    }
}
