<?php
include(__DIR__ . '/../../../includes/headerAdmin.php');
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Core\Session;
use FitSphere\Database\Database;

Session::start();
$user = Session::get("user");

if (!$user || $user["role"] !== "admin") {
    die("No logged-in admin found.");
}

$userId = $user["user_id"];


$db = new Database();
$conn = $db->connect();

$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :id AND role = 'admin'");
$stmt->execute(['id' => $userId]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    echo "Admin account not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="../../../assets/css/profile.css">
</head>
<body>

<div class="profile-container">
    <h2 class="title">Admin Profile</h2>

    <div class="profile-card">
        <img src="/FitSphere/assets/images/account.png" class="admin-avatar" alt="Admin">

        <div class="profile-info">
            <p><strong>Name:</strong> <?= htmlspecialchars($admin['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($admin['email']) ?></p>
            <p><strong>Phone Number:</strong> <?= htmlspecialchars($admin['phone_no']) ?></p>
            <p><strong>Joined:</strong> <?= htmlspecialchars(date("Y-m-d", strtotime($admin['join_date']))) ?></p>
        </div>

        <div class="profile-actions">
            <a href="edit_profile.php" class="btn-edit">Edit Profile</a>
            <a href="change_password.php" class="btn-password">Change Password</a>
        </div>
    </div>
</div>

</body>
</html>
<?php include '../../../includes/footerAdmin.php'; ?>