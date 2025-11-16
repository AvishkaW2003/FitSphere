<?php
session_start();

// Get values safely from RentNow.php
$id = $_GET['id'] ?? null;
$name = $_GET['name'] ?? null;
$price = $_GET['price'] ?? 0;
$qty = $_GET['qty'] ?? 1;
$image = $_GET['image'] ?? 'default.jpg';
$size = $_GET['size'] ?? 'M';
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;

// Validate required fields
if (!$id || !$name || !$start_date || !$end_date || $qty < 1) {
    die("Invalid input.");
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Use a unique key per product + size
$key = $id . '-' . $size;

// If product exists, increase quantity
if (isset($_SESSION['cart'][$key])) {
    $_SESSION['cart'][$key]['qty'] += $qty;
} else {
    $_SESSION['cart'][$key] = [
        'id' => $id,
        'name' => $name,
        'price' => $price,
        'qty' => $qty,
        'image' => $image,
        'size' => $size,
        'start_date' => $start_date,
        'end_date' => $end_date
    ];
}

// Redirect to cart
header("Location: cart.php");
exit();
