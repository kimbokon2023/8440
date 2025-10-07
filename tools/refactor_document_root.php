<?php
// Refactor $_SERVER['DOCUMENT_ROOT'] usages to helper functions.
// Usage:
//   php tools/refactor_document_root.php --dry-run
//   php tools/refactor_document_root.php

declare(strict_types=1);

$rootDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'www';
$dryRun = in_array('--dry-run', $argv, true);

$patterns = [
    // require/include with DOCUMENT_ROOT . '/path'
    [
        'regex' => '/\b(require|include)(_once)?\s*\(\s*\$_SERVER\[\'DOCUMENT_ROOT\'\]\s*\.\s*[\"\']\/?([^\"\']+)[\"\']\s*\)\s*;/',
        'repl'  => '$1$2(includePath(\'$3\'));',
        'desc'  => 'require/include with DOCUMENT_ROOT prefix',
    ],
    // Bare DOCUMENT_ROOT concatenation -> getDocumentRoot()
    [
        'regex' => '/\$_SERVER\[\'DOCUMENT_ROOT\'\]/',
        'repl'  => 'getDocumentRoot()',
        'desc'  => 'bare DOCUMENT_ROOT',
    ],
];

$processed = 0;
$changed = 0;
$changes = [];

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootDir));
foreach ($rii as $file) {
    if ($file->isDir()) continue;
    $path = $file->getPathname();
    // Only PHP files
    if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'php') continue;

    $original = file_get_contents($path);
    if ($original === false) continue;

    $updated = $original;
    $fileChanges = 0;

    foreach ($patterns as $p) {
        $updated = preg_replace($p['regex'], $p['repl'], $updated, -1, $count);
        if ($count > 0) {
            $fileChanges += $count;
        }
    }

    if ($fileChanges > 0) {
        $changed++;
        $changes[] = [$path, $fileChanges];
        if (!$dryRun) {
            file_put_contents($path, $updated);
        }
    }
    $processed++;
}

// Report
echo "Processed files: {$processed}\n";
echo ($dryRun ? "[DRY-RUN] " : "") . "Files with changes: {$changed}\n";
foreach ($changes as [$p, $c]) {
    echo sprintf(" - %s (%d changes)\n", str_replace($rootDir . DIRECTORY_SEPARATOR, 'www' . DIRECTORY_SEPARATOR, $p), $c);
}

if ($dryRun) {
    echo "\nNo files were modified (dry-run). Run without --dry-run to apply.\n";
}

?>


