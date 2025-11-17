<?php
session_start();

// Get key
$key = $_GET['key'] ?? null;

if ($key && isset($_SESSION['cart'][$key])) {
    unset($_SESSION['cart'][$key]);
}


header("Location: cart.php");
exit();
