<?php
session_start();

// Get values from RentNow.php form
$id = $_GET['id'] ?? null;
$name = $_GET['name'] ?? null;
$price = $_GET['price'] ?? 0;
$qty = $_GET['qty'] ?? 1;
$image = $_GET['image'] ?? 'default.jpg';
$size = $_GET['size'] ?? 'M';
$start_date = $_GET['start_date'] ?? date('Y-m-d');
$end_date = $_GET['end_date'] ?? date('Y-m-d');

// Validate required fields
if (!$id || !$name) {
    die("Invalid product.");
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if item already exists in cart (same ID and size)
$key = $id . '-' . $size; // unique key by product ID + size

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

// Redirect to cart page
header("Location: cart.php");
exit();
