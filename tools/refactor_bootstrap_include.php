<?php
// Insert require for common/functions.php at top of PHP files that use includePath() but don't include it yet.
// Usage:
//   php tools/refactor_bootstrap_include.php --dry-run
//   php tools/refactor_bootstrap_include.php

declare(strict_types=1);

$wwwRoot = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'www';
$dryRun = in_array('--dry-run', $argv, true);

function pathFromWww(string $absPath, string $wwwRoot): string {
    return 'www' . DIRECTORY_SEPARATOR . ltrim(substr($absPath, strlen($wwwRoot) + 1), DIRECTORY_SEPARATOR);
}

function needsBootstrap(string $contents): bool {
    if (stripos($contents, 'includePath(') === false) return false; // Not using includePath
    // Already includes common/functions.php?
    if (preg_match('#require_once\s*\(\s*__DIR__\s*\.(.*?)common/functions\.php\s*\)#', $contents)) return false;
    if (strpos($contents, "common/functions.php") !== false) return false;
    return true;
}

function computeIncludeLine(string $absPath, string $wwwRoot): string {
    // Determine relative depth from www
    $relative = substr(dirname($absPath), strlen($wwwRoot)); // like '' or '\\QC' or '\\QC\\sub'
    $relative = trim($relative, DIRECTORY_SEPARATOR);
    $depth = $relative === '' ? 0 : substr_count($relative, DIRECTORY_SEPARATOR) + 1; // number of segments below www
    if ($depth === 0) {
        $rel = "common/functions.php";
    } else {
        $rel = str_repeat('../', $depth) . 'common/functions.php';
    }
    return "require_once __DIR__ . '/$rel';";
}

$processed = 0; $changed = 0; $changes = [];
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($wwwRoot));
foreach ($rii as $file) {
    if ($file->isDir()) continue;
    if (strtolower(pathinfo($file->getPathname(), PATHINFO_EXTENSION)) !== 'php') continue;

    $contents = file_get_contents($file->getPathname());
    if ($contents === false) continue;
    if (!needsBootstrap($contents)) { $processed++; continue; }

    // Insert after opening <?php tag if present at start; otherwise prepend a PHP open block.
    $includeLine = computeIncludeLine($file->getPathname(), $wwwRoot) . "\n";
    $updated = $contents;
    if (preg_match('/^<\?php\s*/', $contents, $m)) {
        $updated = preg_replace('/^<\?php\s*/', '<?php\n' . $includeLine, $contents, 1);
    } else {
        $updated = "<?php\n$includeLine?>\n" . $contents;
    }

    $changed++;
    $changes[] = [pathFromWww($file->getPathname(), $wwwRoot), 1];
    if (!$dryRun) file_put_contents($file->getPathname(), $updated);
    $processed++;
}

echo "Processed files: {$processed}\n";
echo ($dryRun ? "[DRY-RUN] " : "") . "Files with inserted bootstrap: {$changed}\n";
foreach ($changes as [$p, $c]) echo " - {$p}\n";
if ($dryRun) echo "\nNo files were modified (dry-run). Run without --dry-run to apply.\n";

?>


