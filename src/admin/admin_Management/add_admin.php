<?php
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../includes/middleware/AuthMiddleware.php';
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
  <link rel="stylesheet" href="../../../assets/css/admin.css">
</head>
<body>
  <h2>Manage Admin</h2>

  <div class="form-card">
    <h4>Add New Admin</h4>

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

      <label>Role</label>
      <select name="role" required>
        <option value="Sub Admin">Sub Admin</option>
        <option value="Main Admin">Main Admin</option>
      </select>

      <button type="submit" class="btn-create-admin">Create an Admin</button>
    </form>
  </div>

  <style>
    body {
      background: #f9f9f9;
      font-family: 'Poppins', sans-serif;
      text-align: center;
    }
    .form-card {
      max-width: 500px;
      margin: 40px auto;
      background: #fff;
      padding: 25px 40px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      text-align: left;
    }
    h4 {
      margin-bottom: 20px;
    }
    label {
      display: block;
      font-weight: 600;
      margin-top: 10px;
    }
    input, select {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      margin-top: 5px;
    }
    .btn-create-admin {
      width: 100%;
      background: #D4AF37;
      border: none;
      color: black;
      font-weight: 600;
      margin-top: 20px;
      padding: 10px;
      border-radius: 4px;
      transition: background 0.3s;
    }
    .btn-create-admin:hover {
      background: #b7950b;
      color: white;
    }
  </style>
</body>
</html>
