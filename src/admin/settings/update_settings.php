<?php
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';

AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: settings.php");
    exit;
}

// Get setting row id
$stmt = $conn->query("SELECT setting_id FROM settings LIMIT 1");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$settingId = $row['setting_id'];

$siteName = trim($_POST['site_name']);
$deposit = trim($_POST['deposit_percentage']);
$lateFee = trim($_POST['late_fee_percentage']);
$email = trim($_POST['contact_email']);

$logoPath = null;

// Handle Logo Upload
if (!empty($_FILES['logo']['name']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {

    $uploadDir = __DIR__ . "/../../../uploads/site_logo/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
    $fileName = "site_logo_" . time() . "." . $ext;
    $target = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['logo']['tmp_name'], $target)) {
        $logoPath = "uploads/site_logo/" . $fileName;
    }
}

if ($logoPath) {
    // Update with logo
    $query = "
        UPDATE settings SET 
            site_name = :n,
            deposit_percentage = :d,
            late_fee_percentage = :l,
            contact_email = :e,
            logo_path = :logo,
            updated_at = NOW()
        WHERE setting_id = :id
    ";

    $conn->prepare($query)->execute([
        ':n' => $siteName,
        ':d' => $deposit,
        ':l' => $lateFee,
        ':e' => $email,
        ':logo' => $logoPath,
        ':id' => $settingId
    ]);

} else {
    // Update without changing logo
    $query = "
        UPDATE settings SET 
            site_name = :n,
            deposit_percentage = :d,
            late_fee_percentage = :l,
            contact_email = :e,
            updated_at = NOW()
        WHERE setting_id = :id
    ";

    $conn->prepare($query)->execute([
        ':n' => $siteName,
        ':d' => $deposit,
        ':l' => $lateFee,
        ':e' => $email,
        ':id' => $settingId
    ]);
}

header("Location: settings.php?success=1");
exit;
