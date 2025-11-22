<?php
require_once __DIR__ . '/../../includes/functions.php';

use FitSphere\Core\Session;
Session::start();

if (!Auth::check()) {
    header("Location: /FitSphere/login.php");
    exit;
}

$user = Auth::user();

if ($user['role'] !== 'guest') {
    header("Location: /FitSphere/login.php");
    exit;
}
