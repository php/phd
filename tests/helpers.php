<?php
namespace phpdotnet\phd;

function ensureOutputFolder(): void
{
    if (!\file_exists(\dirname(__DIR__) . "/output/")) {
        \mkdir(\dirname(__DIR__) . "/output/", 0777, true);
    }
}

function removeOutputFolder(): void
{
    $folder = \dirname(__DIR__) . "/output/";
    if (!\file_exists($folder)) {
        return;
    }

    $iterator = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($folder, \FilesystemIterator::SKIP_DOTS),
        \RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($iterator as $file) {
        $file->isDir() ? \rmdir($file->getPathname()) : \unlink($file->getPathname());
    }
    \rmdir($folder);
}
