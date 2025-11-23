<?php
include(__DIR__ . '/../../../includes/headerAdmin.php');
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$db = new Database();
$conn = $db->connect();

$message = "";

// Check for success message from process_settings.php
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Settings updated successfully! ✔";
}

// Fetch settings
$stmt = $conn->prepare("SELECT * FROM settings LIMIT 1");
$stmt->execute();
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$settings) {
    echo "<p style='text-align:center;color:red;'>⚠ Settings not found in database.</p>";
    exit;
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

    <!-- ✅ FIX: Added enctype for file uploads and set action to the processing file -->
    <form method="POST" action="update_settings.php" class="settings-card" id="settingsUpdateForm" enctype="multipart/form-data">

        <div class="row">
            <label>Site Name</label>
            <input type="text" name="site_name" 
                    value="<?= htmlspecialchars($settings['site_name']) ?>" required>
        </div>

        <div class="row">
            <label>Deposit Percentage (%)</label>
            <input type="number" name="deposit_percentage" 
                    value="<?= htmlspecialchars($settings['deposit_percentage']) ?>" required>
        </div>

        <div class="row">
            <label>Late Fee Percentage (%)</label>
            <input type="number" name="late_fee_percentage" 
                    value="<?= htmlspecialchars($settings['late_fee_percentage']) ?>" required>
        </div>

        <div class="row">
            <label>Contact Email</label>
            <input type="email" name="contact_email" 
                    value="<?= htmlspecialchars($settings['contact_email']) ?>" required>
        </div>

        <!-- New section for Logo Upload -->
        <div class="row logo-upload-row">
            <label>Site Logo</label>
            <input type="file" name="logo" accept="image/*">
            <?php if (!empty($settings['logo_path'])): ?>
                <div class="current-logo">
                    <!-- Note: The $baseUrl is assumed to be defined by headerAdmin.php -->
                    <p>Current Logo:</p>
                    <img src="<?= $baseUrl . htmlspecialchars($settings['logo_path']) ?>" alt="Site Logo" style="max-width: 150px; max-height: 50px; margin-top: 10px; border: 1px solid #ccc; padding: 5px; border-radius: 4px;">
                </div>
            <?php endif; ?>
        </div>
        <!-- End Logo Upload -->

        <button class="save-btn">Save Changes</button>
        
    </form>

</div>

<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <h4>Confirm Update</h4>
        <p>Are you sure you want to update the settings?</p>

        <button id="confirmButton" class="confirm-btn">Yes, Update</button>
        <button id="cancelButton" class="cancel-btn">Cancel</button>
    </div>
</div>

<!-- MODAL STYLE -->
<style>
.modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
}
.modal-content {
    background: #5656568c;
    padding: 20px;
    margin: 15% auto;
    width: 380px;
    text-align: center;
    border-radius: 10px;
}
.confirm-btn, .cancel-btn {
    padding: 10px 20px;
    margin: 10px;
    border-radius: 10px;
}
.confirm-btn {
    background-color: #D4AF37;
    color: white;
    border: none;
}

</style> 

<script>
    document.addEventListener('DOMContentLoaded', function() {

    const form = document.getElementById('settingsUpdateForm');
    const modal = document.getElementById('confirmationModal');
    const confirmButton = document.getElementById('confirmButton');
    const cancelButton = document.getElementById('cancelButton');
    let isConfirmed = false; // Flag to track confirmation state

    if (form) {
        
        // 1. Intercept form submission
        form.addEventListener('submit', function(e) {
            // Prevent default submission if we haven't confirmed yet
            if (!isConfirmed) {
                e.preventDefault();
                modal.style.display = 'block';
            }
            // If isConfirmed is true, the form will submit normally.
        });

        // 2. Handle Confirm button click
        confirmButton.addEventListener('click', function() {
            isConfirmed = true;
            modal.style.display = 'none';
            // Programmatically submit the form once confirmed
            form.submit();
        });

        // 3. Handle Cancel button click
        cancelButton.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Close modal if user clicks outside of it
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });
    }

});
</script>

</body>
</html>
<?php include '../../../includes/footerAdmin.php'; ?>