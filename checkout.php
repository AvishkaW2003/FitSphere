<?php
session_start();
include 'db.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Your cart is empty.");
}

$cart = $_SESSION['cart'];

// Replace with logged-in user
$customer_id = 1;

$deposit = 2000; // or calculate from settings
$status = 'Active';

// Loop through cart items and create booking for each
foreach ($cart as $item) {
    $product_id = $item['id'];
    $qty = $item['qty'];
    $price_total = $item['price'] * $qty; // <-- multiply by quantity
    $start_date = $item['start_date'];
    $end_date = $item['end_date'];

    $conn->query("
        INSERT INTO bookings (customer_id, product_id, start_date, end_date, total_price, deposit, status, created_at)
        VALUES ('$customer_id', '$product_id', '$start_date', '$end_date', '$price_total', '$deposit', '$status', NOW())
    ");

    $booking_id = $conn->insert_id;

    // Insert payment
    $conn->query("
        INSERT INTO payments (booking_id, rent_fee, deposit, late_fee, refund_amount, status, processed_by, created_at)
        VALUES ('$booking_id', '$price_total', '$deposit', 0, 0, 'Pending', '$customer_id', NOW())
    ");
}

// Clear session cart
unset($_SESSION['cart']);

echo "Checkout Completed Successfully!";
?>
