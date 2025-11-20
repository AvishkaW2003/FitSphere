<?php
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$db = new Database();
$conn = $db->connect();

// Fetch settings row ID
$stmt = $conn->query("SELECT setting_id FROM settings LIMIT 1");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$settingId = $row['setting_id'];

$siteName = $_POST['site_name'];
$deposit = $_POST['deposit_percentage'];
$lateFee = $_POST['late_fee_percentage'];
$email = $_POST['contact_email'];

$logoPath = null;

// If a new logo is uploaded
if (!empty($_FILES['logo']['name'])) {

    $uploadDir = __DIR__ . "/../../../uploads/site_logo/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = "site_logo_" . time() . ".png";
    $target = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['logo']['tmp_name'], $target)) {
        $logoPath = "uploads/site_logo/" . $fileName;
    }
}

if ($logoPath) {
    $update = $conn->prepare("
        UPDATE settings SET 
            site_name = :n,
            deposit_percentage = :d,
            late_fee_percentage = :l,
            contact_email = :e,
            logo_path = :logo,
            updated_at = NOW()
        WHERE setting_id = :id
    ");

    $update->execute([
        "n" => $siteName,
        "d" => $deposit,
        "l" => $lateFee,
        "e" => $email,
        "logo" => $logoPath,
        "id" => $settingId
    ]);
} else {
    $update = $conn->prepare("
        UPDATE settings SET 
            site_name = :n,
            deposit_percentage = :d,
            late_fee_percentage = :l,
            contact_email = :e,
            updated_at = NOW()
        WHERE setting_id = :id
    ");

    $update->execute([
        "n" => $siteName,
        "d" => $deposit,
        "l" => $lateFee,
        "e" => $email,
        "id" => $settingId
    ]);
}

header("Location: settings.php?success=1");
exit;
?>
