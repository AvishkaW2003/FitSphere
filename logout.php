<?php
require_once __DIR__ . '/../includes/functions.php';
Auth::logout();
header("Location: login.php");
exit;
