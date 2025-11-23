<?php
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';

AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$db = new Database();
$conn = $db->connect();

// Fetch all users who have measurements
$stmt = $conn->prepare("
    SELECT DISTINCT u.user_id, u.name, u.email 
    FROM users u 
    INNER JOIN measurements m ON u.user_id = m.customer_id
    ORDER BY u.name ASC
");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View User Measurements</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>assets/css/adminManagement.css">
</head>
<body>

<div class="admin-container">
    <h2>User Measurements</h2>

    <!-- THIS FORM SENDS user_id TO view_measurements.php -->
    <form method="GET" action="view_measurements.php" class="form-card">

        <label>Select User:</label>
        <select name="customer_id" required>
            <option value="">-- Choose User --</option>

            <?php foreach ($users as $u): ?>
                <option value="<?= $u['user_id'] ?>">
                    <?= htmlspecialchars($u['name']) ?> (<?= htmlspecialchars($u['email']) ?>)
                </option>
            <?php endforeach; ?>

        </select>

        <button type="submit" class="btn submit-btn " style="margin-top: 10px;">
            View Measurements
        </button>

    </form>

</div>

</body>
</html>

<?php include '../../../includes/footerAdmin.php'; ?>
