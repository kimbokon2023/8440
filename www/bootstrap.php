<?php
// Global bootstrap for local/server unified behavior

// Composer autoload for vendor packages
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/common/functions.php';

// Session and globals
require_once includePath('session.php');

// Database (make $pdo available to included scripts)
require_once includePath('lib/mydb.php');
if (!isset($pdo) || !$pdo) {
    try {
        $pdo = db_connect();
    } catch (Exception $e) {
        // Defer to pages; prevent fatal at bootstrap
        $pdo = null;
    }
}

// Error reporting per environment
if (function_exists('setupErrorReporting')) {
    setupErrorReporting();
}

$checkMobile = false;
if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if (
        strpos($agent, 'iphone') !== false ||
        strpos($agent, 'ipad') !== false ||
        strpos($agent, 'android') !== false ||
        strpos($agent, 'mobile') !== false ||
        strpos($agent, 'blackberry') !== false ||
        strpos($agent, 'opera mini') !== false ||
        strpos($agent, 'windows phone') !== false
    ) {
        $checkMobile = true;
    }
}

$chkMobile = $checkMobile;
