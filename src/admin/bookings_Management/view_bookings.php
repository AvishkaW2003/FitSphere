<?php
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../includes/db.php';

AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$db = new Database();
$conn = $db->connect();

if (!isset($_GET['booking_id'])) {
    header("Location: manage_bookings.php");
    exit;
}

$booking_id = intval($_GET['booking_id']);

// Fetch booking with customer + product join
$stmt = $conn->prepare("
    SELECT b.*, u.name AS customer_name, p.title AS product_title
    FROM bookings b
    LEFT JOIN customers u ON b.customer_id = u.customer_id
    LEFT JOIN products p ON b.product_id = p.product_id
    WHERE b.booking_id = :id
");
$stmt->bindParam(':id', $booking_id);
$stmt->execute();
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) die("Booking not found");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Booking</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>assets/css/adminManagement.css">
    <script src="<?= $baseUrl ?>assets/js/booking.js" defer></script>
</head>

<body>

<div class="admin-container">
    <h2>Manage Bookings</h2>

    <div class="form-card">
        <h4>Booking Details</h4>

        <form action="process_return.php" method="POST" id="bookingReturnForm">

            <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
            <input type="hidden" id="end_date" value="<?= $booking['end_date'] ?>">
            <input type="hidden" id="deposit" value="<?= $booking['deposit'] ?>">

            <label>Customer Name</label>
            <input readonly value="<?= htmlspecialchars($booking['customer_name']) ?>">

            <label>Suit</label>
            <input readonly value="<?= htmlspecialchars($booking['product_title']) ?>">

            <label>Period</label>
            <input readonly value="<?= $booking['start_date'] . ' → ' . $booking['end_date'] ?>">

            <label>Total</label>
            <input readonly value="Rs. <?= number_format($booking['total_price']) ?>">

            <label>Deposit</label>
            <input readonly value="Rs. <?= number_format($booking['deposit']) ?>">

            <label>Returned Date</label>
            <input type="date" id="returned_date" name="returned_date" value="<?= $booking['returned_date'] ?>">

            <label>Late Days</label>
            <input readonly name="late_days" id="late_days" value="<?= $booking['late_days'] ?>">

            <label>Late Fee</label>
            <input readonly name="late_fee" id="late_fee" value="<?= $booking['late_fee'] ?>">

            <label>Refund Amount</label>
            <input readonly name="refund" id="refund" value="<?= $booking['refund'] ?>">

            <label>Status</label>
            <select name="status" id="status">
                <option value="Active"     <?= $booking['status']=="Active"?"selected":"" ?>>Active</option>
                <option value="Overdue"    <?= $booking['status']=="Overdue"?"selected":"" ?>>Overdue</option>
                <option value="Completed"  <?= $booking['status']=="Completed"?"selected":"" ?>>Completed</option>
                <option value="Cancelled"  <?= $booking['status']=="Cancelled"?"selected":"" ?>>Cancelled</option>
            </select>

            <button type="submit" class="submit-btn">PROCESS RETURN</button>
        </form>
    </div>
</div>

</body>
</html>
