<?php
session_start();

// Get key and action
$key = $_GET['key'] ?? null;
$action = $_GET['action'] ?? null;

if ($key && isset($_SESSION['cart'][$key])) {
    if ($action === 'plus') {
        $_SESSION['cart'][$key]['qty']++;
    } elseif ($action === 'minus') {
        if ($_SESSION['cart'][$key]['qty'] > 1) {
            $_SESSION['cart'][$key]['qty']--;
        }
    }
}

// Redirect back to cart
header("Location: cart.php");
exit();
