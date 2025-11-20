<?php
use FitSphere\Core\Session;

require_once __DIR__ . '/../../includes/auth/auth_admin.php';
require_once __DIR__ . '/../../includes/db.php';

Session::start();
Session::destroy(); // clear session

header("Location: /FitSphere/login.php");
exit;
