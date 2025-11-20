<?php
include(__DIR__ . '/../../../includes/headerAdmin.php');
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$db = new Database();
$conn = $db->connect();

$message = "";

// Fetch settings
$stmt = $conn->prepare("SELECT * FROM settings LIMIT 1");
$stmt->execute();
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$settings) {
    echo "<p style='text-align:center;color:red;'>⚠ Settings not found in database.</p>";
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['site_name']);
    $deposit = trim($_POST['deposit_percentage']);
    $late = trim($_POST['late_fee_percentage']);
    $email = trim($_POST['contact_email']);

    // Update settings
    $update = $conn->prepare("
        UPDATE settings 
        SET site_name = :name,
            deposit_percentage = :deposit,
            late_fee_percentage = :late,
            contact_email = :email,
            updated_at = NOW()
        WHERE setting_id = :id
    ");

    $update->execute([
        ':name' => $name,
        ':deposit' => $deposit,
        ':late' => $late,
        ':email' => $email,
        ':id' => $settings['setting_id']
    ]);

    $message = "Settings updated successfully! ✔";

    // Refresh data
    $stmt->execute();
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings</title>
    <link rel="stylesheet" href="../../../assets/css/settings.css">
</head>

<body>

<div class="settings-container">
    <h2 class="settings-title">Settings</h2>

    <?php if (!empty($message)): ?>
        <p class="success-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" class="settings-card">

        <div class="row">
            <label>Site Name</label>
            <input type="text" name="site_name" 
                   value="<?= htmlspecialchars($settings['site_name']) ?>" required>
        </div>

        <div class="row">
            <label>Deposit Percentage</label>
            <input type="number" name="deposit_percentage" 
                   value="<?= htmlspecialchars($settings['deposit_percentage']) ?>" required>
        </div>

        <div class="row">
            <label>Late Fee Percentage</label>
            <input type="number" name="late_fee_percentage" 
                   value="<?= htmlspecialchars($settings['late_fee_percentage']) ?>" required>
        </div>

        <div class="row">
            <label>Contact Email</label>
            <input type="email" name="contact_email" 
                   value="<?= htmlspecialchars($settings['contact_email']) ?>" required>
        </div>

        <button class="save-btn">Save Changes</button>
        
    </form>

</div>

</body>
</html>
<?php include '../../../includes/footerAdmin.php'; ?>
