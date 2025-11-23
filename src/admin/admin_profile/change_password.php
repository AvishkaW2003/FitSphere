<?php
include(__DIR__ . '/../../../includes/headerAdmin.php');
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';

use FitSphere\Core\Session;
use FitSphere\Database\Database;

AuthMiddleware::requireRole('admin');

Session::start();
$user = Session::get("user");

if (!$user || $user["role"] !== "admin") {
    die("No logged-in admin found.");
}

$userId = $user["user_id"];


$db = new Database();
$conn = $db->connect();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $old = $_POST['old_password'];
    $new = $_POST['new_password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = :id");
    $stmt->execute(['id' => $userId]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        $message = "Admin not found!";
    } elseif (!password_verify($old, $admin['password'])) {
        $message = "Old password is incorrect!";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password = :p WHERE user_id = :id");
        $update->execute(['p' => $hashed, 'id' => $userId]);
        $message = "Password updated successfully!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="../../../assets/css/profile.css">
</head>

<body>
<div class="profile-container">
    <h2>Change Password</h2>

    <?php if ($message): ?>
        <p class="success"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" class="form-card">

        <label>Old Password</label>
        <input type="password" name="old_password" required>

        <label>New Password</label>
        <input type="password" name="new_password" required>

        <button class="btn-save">Update Password</button>
        <a href="profile.php" class="btn-cancel">Cancel</a>
    </form>
</div>
</body>
</html>
<?php include '../../../includes/footerAdmin.php'; ?>