<?php
session_start();

// Get product info from GET request
$id = $_GET['id'] ?? null;
$name = $_GET['name'] ?? '';
$price = $_GET['price'] ?? 0;
$qty = $_GET['qty'] ?? 1;
$image = $_GET['image'] ?? '';

if($id) {
    // If cart is empty, initialize
    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If item already exists, increase quantity
    if(isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] += $qty;
    } else {
        $_SESSION['cart'][$id] = [
            'name' => $name,
            'price' => $price,
            'qty' => $qty,
            'image' => basename($image) // save only filename
        ];
    }
}

// Redirect to cart page
header('Location: cart.php');
exit;
