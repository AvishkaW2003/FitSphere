<?php
// logout.php
session_start();

// recommend POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /FitSphere/index.php');
    exit;
}

// optionally verify CSRF token here if you have one

// destroy session safely
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// redirect to login or home
header('Location: /FitSphere/login.php');
exit;


