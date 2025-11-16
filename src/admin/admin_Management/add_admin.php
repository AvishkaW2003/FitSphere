<?php
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';

require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$database = new Database();
$conn = $database->connect();

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO admins (name, email, password, role, created_at) VALUES (:name, :email, :password, :role, NOW())");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
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
    <h2>Manage Admin</h2>
    <div class="form-card">
    <h4>Add New Admin</h4>

    <?php if (!empty($error)): ?>
      <p class="text-danger"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
      <label>Full Name</label>
      <input type="text" name="name" required >

      <label>Email</label>
      <input type="email" name="email"  required>

      <label>Password</label>
      <input type="password" name="password"  required>

      <label>Role</label>
      <div class="form-input">
        <select name="role" required>
        <option value="Sub Admin">Sub Admin</option>
        <option value="Main Admin">Main Admin</option>
      </select>

      </div>
      
      <button type="submit" class="submit-btn">Create an Admin</button>
      <button type="button" class="cancel-btn" onclick="window.location.href='manage_admin.php'">Cancel</button>

    </form>
  </div>
  </div>
  

  
</body>
</html>
