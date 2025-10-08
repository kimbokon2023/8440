<?php
// Global bootstrap for local/server unified behavior

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


