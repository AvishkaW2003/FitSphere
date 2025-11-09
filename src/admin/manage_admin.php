<?php 
  //session_start(); 
  //$name = $_SESSION['username'] ?? ''; // or however you store the logged-in user’s name
  //include '../../includes/headerAdmin.php'; // adjust path to your structure
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admin</title>
</head>
<body>
<?php
include '../includes/db_connect.php'; // your DB connection

$result = $conn->query("SELECT * FROM admins");
?>

<h2 class="text-center my-4">Manage Admin</h2>

<div class="d-flex justify-content-between align-items-center mb-3">
  <a href="add_admin.php" class="btn btn-warning fw-semibold">Add New Admin ➕</a>
  <div class="border p-2 rounded text-center">
    <p class="fw-bold mb-0">Total Admins</p>
    <p><?= $result->num_rows ?></p>
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
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['role'] ?></td>
        <td><?= $row['created_at'] ?></td>
        <td>
          <a href="edit_admin.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Edit</a>
          <a href="delete_admin.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
             onclick="return confirm('Are you sure you want to delete this admin?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

</body>
</html>