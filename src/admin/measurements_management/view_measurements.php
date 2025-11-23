<?php
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$database = new Database();
$conn = $database->connect();

// FIX: Accept both user_id or customer_id
if (isset($_GET['user_id'])) {
    $customerId = $_GET['user_id'];
} elseif (isset($_GET['customer_id'])) {
    $customerId = $_GET['customer_id'];
} else {
    die("Missing User ID");
}

// Get user details
$userStmt = $conn->prepare("SELECT * FROM users WHERE user_id = :id");
$userStmt->execute([':id' => $customerId]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

if (!$user) die("User not found");

// Get measurement details
$measureStmt = $conn->prepare("
    SELECT * FROM measurements 
    WHERE customer_id = :cid
");
$measureStmt->execute([':cid' => $customerId]);
$measurement = $measureStmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Measurements</title>
    <link rel="stylesheet" href="../../../assets/css/adminManagement.css">
    <style>
        .back-button-container {
            /* Position the button correctly, perhaps centered below the table */
            margin-top: 20px; 
            text-align: center;
        }
        /* Assuming 'btn' and 'cancel-btn' exist in adminManagement.css, 
           or use inline styles if necessary for appearance like the image */
        .btn {
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
        .cancel-btn {
            background-color: #333; /* Dark background like in the image */
            color: white; /* White text */
            border: none;
            /* Match the button size and appearance from the image more closely */
            font-size: 16px; 
        }
    </style>
</head>
<body>

<div class="admin-container" sty>
    <h2>Measurements for <?= htmlspecialchars($user['name']); ?></h2>

    <?php if (!$measurement): ?>
        <p>No measurements found for this user.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <tr><th>Size</th><td><?= $measurement['size'] ?></td></tr>
            <tr><th>Neck</th><td><?= $measurement['neck'] ?> cm</td></tr>
            <tr><th>Chest</th><td><?= $measurement['chest'] ?> cm</td></tr>
            <tr><th>Waist</th><td><?= $measurement['waist'] ?> cm</td></tr>
            <tr><th>Hips</th><td><?= $measurement['hips'] ?> cm</td></tr>
            <tr><th>Sleeve</th><td><?= $measurement['sleeve'] ?> cm</td></tr>
            <tr><th>Thigh</th><td><?= $measurement['thigh'] ?> cm</td></tr>
            <tr><th>Inseam</th><td><?= $measurement['inseam'] ?> cm</td></tr>
            <tr><th>Jacket Length</th><td><?= $measurement['jacket_length'] ?> cm</td></tr>
            <tr><th>Pant Length</th><td><?= $measurement['pant_length'] ?> cm</td></tr>
            <tr><th>Updated At</th><td><?= $measurement['updated_at'] ?></td></tr>
        </table>
    <?php endif; ?>

    <div class="back-button-container">
        <a href="javascript:history.back()" class="btn cancel-btn">Back</a>
    </div>
</div>
    

</body>
</html>

<?php include '../../../includes/footerAdmin.php'; ?>