<?php
// CRITICAL FIX: Ensure session_start() is called first.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// NOTE: Assuming db.php provides a $conn object using mysqli
include 'db.php';

// --- 1. Validation and Setup ---

// FIX: Checking for 'user_id' (for consistency with dashboard)
if (!isset($_SESSION['user']['user_id']) && !isset($_SESSION['user']['id'])) {
    die("You must be logged in to checkout.");
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Your cart is empty.");
}

$cart = $_SESSION['cart'];
// FIX: Safely retrieve customer ID using either key
$customer_id = $_SESSION['user']['user_id'] ?? $_SESSION['user']['id'];

$deposit_fixed = 500.00; // Fixed deposit 
$status = 'Active';
$zero_float = 0.00;

// --- START MySQLi TRANSACTION ---
$conn->begin_transaction();
$success = true; // Flag to track success of all operations

// Loop through cart items and create booking for each
foreach ($cart as $item) {

    // ... (Rental days calculation, variables setup) ...
    try {
        $start_date_str = $item['start_date'];
        $end_date_str = $item['end_date'];

        $start_date_obj = new DateTime($start_date_str);
        $end_date_obj = new DateTime($end_date_str);
        $interval = $start_date_obj->diff($end_date_obj);
        $rental_days = $interval->days + 1;
    } catch (Exception $e) {
        error_log("Date calculation error: " . $e->getMessage());
        $success = false;
        break;
    }

    $product_id = (int)($item['product_id'] ?? $item['id']);
    $qty = (int)$item['qty'];
    $price_per_day = (float)$item['price'];
    $total_rent_fee = $price_per_day * $rental_days * $qty;
    $total_price = $total_rent_fee + $deposit_fixed;

    // --- 2. BOOKINGS INSERT: MySQLi VERSION ---

    $stmt_booking = $conn->prepare("
        INSERT INTO bookings (
            customer_id, product_id, start_date, end_date, total_price, 
            deposit, status, price_per_day, late_fee, refund, created_at
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()) 
    ");

    $stmt_booking->bind_param(
        "iissddsddd",
        $customer_id,
        $product_id,
        $start_date_str,
        $end_date_str,
        $total_price,
        $deposit_fixed,
        $status,         
        $price_per_day,
        $zero_float,
        $zero_float
    );

    if (!$stmt_booking->execute()) {
        // CRITICAL: Fail immediately and show error.
        $success = false;
        error_log("Booking INSERT failed: " . $stmt_booking->error);
        die("FATAL DB ERROR (Booking): " . $stmt_booking->error);
    }

    $booking_id = $conn->insert_id;
    $stmt_booking->close();

    // --- 3. PAYMENTS INSERT ---
    // ... (Your payments insert code here, ensure it uses $conn->prepare and $customer_id) ...

    // --- 4. INVENTORY UPDATE ---

    $stmt_update_stock = $conn->prepare("
        UPDATE product_inventory 
        SET stock = stock - ? 
        WHERE product_id = ? AND stock >= ?
    ");
    $stmt_update_stock->bind_param("iii", $qty, $product_id, $qty);

    if (!$stmt_update_stock->execute()) {
        $success = false;
        error_log("Stock UPDATE failed: " . $stmt_update_stock->error);
        die("FATAL DB ERROR (Stock): " . $stmt_update_stock->error);
    }
    $stmt_update_stock->close();
} // end foreach loop

// --- END TRANSACTION ---
if ($success) {
    $conn->commit();
    unset($_SESSION['cart']);
    header("Location: success.php");
    exit();
} else {
    $conn->rollback();
    die("Checkout transaction failed and was rolled back.");
}
