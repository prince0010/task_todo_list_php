<?php
// Session settings should be set before the session starts
ini_set('session.cookie_lifetime', 0);
ini_set('session.gc_maxlifetime', 1440);
ini_set('session.entropy_length', 32);
ini_set('session.hash_function', 'sha256');
ini_set('session.save_path', '/path/to/secure/directory');

// Error Handling Setup
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');
error_reporting(E_ALL);

// Start the session with security settings
session_start([
    'cookie_secure' => isset($_SERVER['HTTPS']),
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict',
    'name' => 'custom_session_name',
]);

// Custom Error Handling Functions
function handleError($errno, $errstr, $errfile, $errline) {
    $error = "Error: [$errno] $errstr in $errfile on line $errline";
    error_log($error);
    header('Location: /todo_list/public/error'); // Redirect to a custom error page
    exit();
}

function handleException($exception) {
    $error = "Uncaught Exception: " . $exception->getMessage() . " in " .
             $exception->getFile() . " on line " . $exception->getLine();
    error_log($error);
    header('Location: /todo_list/public/error');
    exit();
}

function handleShutdownError() {
    $error = error_get_last();
    if ($error && ($error['type'] === E_ERROR || $error['type'] === E_PARSE)) {
        error_log("Fatal Error: {$error['message']} in {$error['file']} on line {$error['line']}");
        header('Location: /todo_list/public/error');
        exit();
    }
}

set_error_handler('handleError');
set_exception_handler('handleException');
register_shutdown_function('handleShutdownError');

// Session Security Checks
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    session_unset();
    session_destroy();
    header('Location: /todo_list/public/login');
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time();

if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    header('Location: /todo_list/public/login');
    exit();
}

if (isset($_SESSION['ip_address']) && $_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
    session_unset();
    session_destroy();
    header('Location: /todo_list/public/login');
    exit();
}

require_once '../core/App.php';
require_once '../core/Model.php';
require_once '../core/Controller.php';

$app = new App();
