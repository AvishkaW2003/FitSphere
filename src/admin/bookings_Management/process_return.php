<?php
// process_return.php
require_once __DIR__ . '/../../../includes/db.php';
use FitSphere\Database\Database;

// Connect to DB
$db = new Database();
$conn = $db->connect();

// Validate request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: manage_bookings.php");
  exit;
}

$booking_id = intval($_POST['booking_id']);
$returned_date = !empty($_POST['returned_date']) ? $_POST['returned_date'] : null;

// Fetch booking data needed for calculation
$stmt = $conn->prepare("SELECT end_date, deposit FROM bookings WHERE booking_id = :id");
$stmt->execute([':id' => $booking_id]);
$b = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$b) {
  die("Booking not found");
}

// LATE FEE Logic
$LATE_FEE_PER_DAY = 500.00; // Use float for currency calculations

// Compute late days
$late_days = 0;
if ($returned_date) {
  $endTs = strtotime($b['end_date']);
  $retTs = strtotime($returned_date);
    // Calculate difference in seconds, convert to days, and round up to the nearest whole day.
  $late_days = max(0, ceil(($retTs - $endTs) / 86400)); 
}

// Late fee = late_days * LATE_FEE_PER_DAY
$late_fee = $late_days * $LATE_FEE_PER_DAY;

// Refund = deposit - late fee (never negative)
$refund = max(0, floatval($b['deposit']) - $late_fee);

// Determine FINAL STATUS
$final_status = "Completed"; // If returned_date is set, it's completed
if (!$returned_date) {
    // If somehow a POST occurred without a date, we revert the status logic.
    // For a return process, setting the returned_date should be mandatory.
    // However, the original code had fallback logic. We will simplify: If date is set, it's completed.
    $final_status = $b['status']; // Keep the old status if no return date is provided
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
  ':late_days'   => $late_days,
  ':late_fee'   => $late_fee,
  ':refund'    => $refund,
  ':status'    => $final_status,
  ':booking_id'  => $booking_id
]);

header("Location: manage_bookings.php?updated=1");
exit;
?>