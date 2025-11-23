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
  $query = "SELECT user_id, name, email, role, phone_no, join_date FROM users WHERE role='admin' ORDER BY user_id DESC";
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $baseUrl ?>assets/css/adminManagement.css">
</head>
<body>

  <div class="admin-container">
    <h2 class="text-center my-4">Manage Admin</h2>

  <div class="d-flex justify-content-between mb-3">
    <div class="">
      <a href="add_admin.php" class="btn btn-warning fw-semibold">Add New Admin ➕</a>
    </div>
    

    <div class="total-box border p-2 rounded text-center ">
      <p class="fw-bold mb-0">Total Admins</p>
      <p><?= count($admins) ?></p>
    </div>

  </div>

  <table>
    <thead style="background-color: #d4af37ff;">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone Number</th>
        <!-- <th>Role</th> -->
        <th>Date</th>
        <th>Action</th>
      </tr>
    </thead>

    <tbody>
      <?php if (count($admins) > 0): ?>
        <?php foreach ($admins as $row): ?>
          <tr>
              <td><?= htmlspecialchars($row['user_id']); ?></td>
              <td><?= htmlspecialchars($row['name']); ?></td>
              <td><?= htmlspecialchars($row['email']); ?></td>
              <td><?= htmlspecialchars($row['phone_no']); ?></td>
              <!-- <td><?= htmlspecialchars($row['role']); ?></td> -->
              <td><?= htmlspecialchars($row['join_date']); ?></td>
              <td>
                <a href="edit_admin.php?user_id=<?= $row['user_id']; ?>" class="action-btn edit-btn">Edit</a> |
                <a href="delete_admin.php?user_id=<?= $row['user_id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
              </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
      <tr> 
        <td colspan="6">No admins found.</td>
      </tr>
    <?php endif; ?>
    </tbody>

  </table>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include '../../../includes/footerAdmin.php'; ?>