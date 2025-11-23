<?php
require_once __DIR__ . '/../../session.php';
require_once __DIR__ . '/../../includes/functions.php';

use FitSphere\Core\Session;
Session::start();

if (!Auth::check()) {
    header("Location: /FitSphere/login.php");
    exit;
}

$user = Auth::user();

// Ensure role from DB = admin
if ($user['role'] !== 'admin') {
    header("Location: /FitSphere/login.php");
    exit;
}
