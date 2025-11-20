<?php
include(__DIR__ . '/../../../includes/headerAdmin.php');
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$db = new Database();
$conn = $db->connect();

// Fetch settings (only one row)
$stmt = $conn->query("SELECT * FROM settings LIMIT 1");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$settings) {
    die("Settings not found.");
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
    <h2 class="title">Settings</h2>

    <form action="update_settings.php" method="POST" enctype="multipart/form-data" class="settings-card">

        <div class="row">
            <label>Site Name</label>
            <input type="text" name="site_name" 
                   value="<?= htmlspecialchars($settings['site_name']) ?>" required>
        </div>

        <div class="row">
            <label>Deposit Percentage</label>
            <input type="number" name="deposit_percentage" min="0" max="100"
                   value="<?= htmlspecialchars($settings['deposit_percentage']) ?>" required>
        </div>

        <div class="row">
            <label>Late Fee Percentage</label>
            <input type="number" name="late_fee_percentage" min="0" max="100"
                   value="<?= htmlspecialchars($settings['late_fee_percentage']) ?>" required>
        </div>

        <div class="row">
            <label>Contact Email</label>
            <input type="email" name="contact_email"
                   value="<?= htmlspecialchars($settings['contact_email']) ?>" required>
        </div>

        <div class="row">
            <label>Upload Logo</label>
            <input type="file" name="logo">
        </div>

        <button class="btn-save">Save Changes</button>
    </form>
</div>

</body>
</html>
<?php include '../../../includes/footerAdmin.php'; ?>
