<?php
session_start();
include 'db.php';

// Check if user is logged in (Assuming user ID is stored in $_SESSION['user']['id'])
if (!isset($_SESSION['user']['id'])) {

    die("You must be logged in to checkout.");
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Your cart is empty.");
}

$cart = $_SESSION['cart'];
$customer_id = $_SESSION['user']['id']; // 🔥 Use the actual logged-in user ID
$deposit = 2000.00; // Use float/decimal format
$status = 'Confirmed'; // A more appropriate initial status

// Loop through cart items and create booking for each
foreach ($cart as $item) {

    $product_id = (int)$item['id'];
    $qty = (int)$item['qty'];
    $price_per_day = (float)$item['price'];


$price_total = $price_per_day * $qty; // Using qty here, adjust if days are involved

$start_date = $item['start_date'];
$end_date = $item['end_date'];


$zero_float = 0.00;


$stmt_booking = $conn->prepare("
INSERT INTO bookings (customer_id, product_id, start_date, end_date, total_price, deposit, status, price_per_day, late_fee, refund, created_at)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
");


$stmt_booking->bind_param("iisssssddi", 
    $customer_id, 
    $product_id, 
    $start_date, 
    $end_date, 
    $price_total, 
    $deposit, 
    $status,
    $price_per_day, // New column added
    $zero_float, // late_fee default
    $zero_float 
);


if (!$stmt_booking->execute()) {

error_log("Booking INSERT failed: " . $stmt_booking->error);
die("Database error during booking: " . $stmt_booking->error);
}

$booking_id = $conn->insert_id;
$stmt_booking->close();



$stmt_pay = $conn->prepare("
INSERT INTO payments (booking_id, rent_fee, deposit, late_fee, refund_amount, status, processed_by, created_at)
 VALUES (?, ?, ?, 0, 0, 'Pending', ?, NOW())
");

$stmt_pay->bind_param("iddi", 
$booking_id, 
$price_total, // rent_fee (d)
$deposit,     // deposit (d)
$customer_id  // processed_by (i)
);

$stmt_pay->execute();
$stmt_pay->close();
}

// Clear session cart
unset($_SESSION['cart']);

// Use ob_start() and ob_end_clean() if needed to prevent headers already sent warning
// ob_end_clean(); // Only if ob_start() is at the top of the entry file
header("Location: success.php");
exit();
?>