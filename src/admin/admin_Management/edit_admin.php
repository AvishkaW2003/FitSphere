<?php
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';

require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$database = new Database();
$conn = $database->connect();


// Fetch admin by ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        die("Admin not found!");
    }
} else {
    die("No admin ID provided!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Admin</title>

  <link rel="stylesheet" href="../../../assets/css/adminManagement.css">
</head>
<body>
  <div class="admin-container">
    <h2 class="text-center my-4">Edit Admin</h2>
    
    <div class="form-card">
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" value="<?= $admin['name'] ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?= $admin['email'] ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Role</label>
          <select name="role" class="form-select">
            <option value="Main Admin" <?= $admin['role'] == 'Main Admin' ? 'selected' : '' ?>>Main Admin</option>
            <option value="Sub Admin" <?= $admin['role'] == 'Sub Admin' ? 'selected' : '' ?>>Sub Admin</option>
          </select>
        </div>

        <button type="submit" class="submit-btn">Save Changes</button>
        <button type="button" class="cancel-btn" onclick="window.location.href='manage_admin.php'">Cancel</button>

      </form>
    </div>
  </div>
  
</body>
</html>

<?php include '../../../includes/footerAdmin.php'; ?>

