<?php
session_start();

if(empty($_SESSION['cart'])){
    echo "<h2>Your cart is empty!</h2>";
    echo '<a href="collection.php">Go back shopping</a>';
    exit;
}

// Calculate total
$total = 0;
foreach($_SESSION['cart'] as $item){
    $total += $item['price'] * $item['qty'];
}
$total += 500; // delivery

// Clear the cart
$_SESSION['cart'] = [];

echo "<h2>Thank you for your purchase!</h2>";
echo "<p>Total paid: Rs $total</p>";
echo '<a href="collection.php">Continue Shopping</a>';
