<?php
require_once __DIR__ . '/../../../includes/db.php';
use FitSphere\Database\Database;

$db = new Database();
$conn = $db->connect();

// Validate request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request");
}

$booking_id = intval($_POST['booking_id']);
$returned_date = !empty($_POST['returned_date']) ? $_POST['returned_date'] : null;

// Fetch booking
$stmt = $conn->prepare("SELECT * FROM bookings WHERE booking_id = :id");
$stmt->execute([':id' => $booking_id]);
$b = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$b) {
    die("Booking not found");
}

// LATE FEE Logic
$LATE_FEE_PER_DAY = 500;

// Compute late days
$late_days = 0;
if ($returned_date) {
    $endTs = strtotime($b['end_date']);
    $retTs = strtotime($returned_date);
    $late_days = max(0, ceil(($retTs - $endTs) / 86400));
}

// Late fee = late_days * 500
$late_fee = $late_days * $LATE_FEE_PER_DAY;

// Refund = deposit - late fee (never negative)
$refund = max(0, floatval($b['deposit']) - $late_fee);

// Determine STATUS
if ($returned_date) {
    $final_status = "Completed";
} else {
    $today = strtotime(date("Y-m-d"));
    $end_date_ts = strtotime($b['end_date']);

    if ($today > $end_date_ts) {
        $final_status = "Overdue";
    } else {
        $final_status = "Active";
    }
}

// Update Booking
$update = $conn->prepare("
    UPDATE bookings SET 
        returned_date = :returned_date,
        late_days = :late_days,
        late_fee = :late_fee,
        refund = :refund,
        status = :status
    WHERE booking_id = :booking_id
");

$update->execute([
    ':returned_date' => $returned_date,
    ':late_days'     => $late_days,
    ':late_fee'      => $late_fee,
    ':refund'        => $refund,
    ':status'        => $final_status,
    ':booking_id'    => $booking_id
]);

header("Location: manage_bookings.php?updated=1");
exit;
