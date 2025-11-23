<?php
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';

require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$database = new Database();
$conn = $database->connect();

$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role     = "admin"; // ALWAYS admin
    $phone    = trim($_POST['phone']);

    // Insert into USERS table
    $stmt = $conn->prepare("
        INSERT INTO users (name, email, password, role, phone_no, join_date, status)
        VALUES (:name, :email, :password, :role, :phone, NOW(), 'active')
    ");

    $success = $stmt->execute([
        ':name'     => $name,
        ':email'    => $email,
        ':password' => $password,
        ':role'     => $role,
        ':phone'    => $phone
    ]);

    if ($success) {
        header("Location: manage_admin.php?success=1");
        exit;
    } else {
        $error = "Failed to add admin!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Admin</title>
    <link rel="stylesheet" href="../../../assets/css/adminManagement.css">
</head>

<body>
    <div class="admin-container">
        <h2>Add New Admin</h2>

        <div class="form-card">
            <h4>Create Admin Account</h4>

            <?php if (!empty($error)): ?>
                <p class="text-danger"><?= $error ?></p>
            <?php endif; ?>

            <form method="POST">

                <label>Full Name</label>
                <input type="text" name="name" required>

                <label>Email</label>
                <input type="email" name="email" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <label>Phone Number</label>
                <input type="text" name="phone">

                <label>Role (Fixed)</label>
                <input type="text" value="Admin" disabled class="disabled-box">

                <button type="submit" class="submit-btn">Create Admin</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='manage_admin.php'">Cancel</button>

            </form>
        </div>
    </div>
</body>

</html>
