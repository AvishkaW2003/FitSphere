<?php 
  include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';


require_once '../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

// Create database object and connect
$database = new Database();
$conn = $database->connect();

// Now you can use $conn to query the DB
$query = "SELECT id, name, email, role, created_at FROM admins";
$stmt = $conn->prepare($query);
$stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admin</title>
</head>
<body>

<h2 class="text-center my-4">Manage Admin</h2>

<div class="d-flex justify-content-between align-items-center mb-3">
  <a href="add_admin.php" class="btn btn-warning fw-semibold">Add New Admin ➕</a>
  <div class="border p-2 rounded text-center">
    <p class="fw-bold mb-0">Total Admins</p>
    <p><?= count($admins) ?></p>
  </div>
</div>

<table class="table table-bordered table-hover text-center align-middle">
  <thead class="table-warning">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Role</th>
      <th>Date</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php if (count($admins) > 0): ?>
      <?php foreach ($admins as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['id']); ?></td>
          <td><?= htmlspecialchars($row['name']); ?></td>
          <td><?= htmlspecialchars($row['email']); ?></td>
          <td><?= htmlspecialchars($row['role']); ?></td>
          <td><?= htmlspecialchars($row['created_at']); ?></td>
          <td>
            <a href="edit_admin.php?id=<?= $row['id']; ?>">Edit</a> |
            <a href="delete_admin.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
    <tr><td colspan="6">No admins found.</td></tr>
  <?php endif; ?>
  </tbody>
</table>

</body>
</html>