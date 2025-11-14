<?php
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';

require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

// Create database object and connect
$database = new Database();
$conn = $database->connect();

// Now you can use $conn to query the DB
$query = "SELECT * FROM products WHERE 1";

if (!empty($_GET['category'])) {
    $query .= " AND category = :category";
}

$stmt = $conn->prepare($query);

if (!empty($_GET['category'])) {
    $stmt->bindParam(':category', $_GET['category']);
}

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <h2 class="text-center my-4">Manage Products</h2>

  <div class="d-flex justify-content-between mb-3">
    <div class="">
      <a href="add_product.php" class="btn btn-warning fw-semibold">Add New Product ➕</a>
    </div>
    
    <form method="GET" class="d-flex gap-3 mb-3">
    <select name="category" class="form-select" style="width: 200px;">
        <option value="">All Categories</option>
        <option value="Wedding" <?= ($_GET['category'] ?? '') == "Wedding" ? 'selected' : '' ?>>Wedding</option>
        <option value="Nilame" <?= ($_GET['category'] ?? '') == "Nilame" ? 'selected' : '' ?>>Nilame</option>
        <option value="Business" <?= ($_GET['category'] ?? '') == "Business" ? 'selected' : '' ?>>Business</option>
        <option value="Indian" <?= ($_GET['category'] ?? '') == "Indian" ? 'selected' : '' ?>>Indian</option>
        <option value="Dinner" <?= ($_GET['category'] ?? '') == "Dinner" ? 'selected' : '' ?>>Dinner</option>
    </select>

    <button class="btn btn-warning fw-semibold">Filter</button>
    </form>


    <!-- <div class="total-box border p-2 rounded text-center ">
      <p class="fw-bold mb-0">Total Products</p>
      <p><?= count($products) ?></p>
    </div> -->

  </div>

  <table class="table table-bordered table-hover text-center align-middle">
    <thead class="table-warning">
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Category</th>
        <th>Size</th>
        <th>Price/Day</th>
        <th>Stock</th>
        <th>Image</th>
        <th>Status</th>
        <th>Updated at</th>
        <th>Action</th>
      </tr>
    </thead>

    <tbody>
      <?php if (count($products) > 0): ?>
        <?php foreach ($products as $row): ?>
          <tr>
              <td><?= htmlspecialchars($row['product_id']); ?></td>
              <td><?= htmlspecialchars($row['title']); ?></td>
              <td><?= htmlspecialchars($row['category']); ?></td>
              <td><?= htmlspecialchars($row['size']); ?></td>
              <td><?= htmlspecialchars($row['price']); ?></td>
              <td><?= htmlspecialchars($row['stock']); ?></td>

              <td>
                <img src="<?= $baseUrl ?>/assets/images/uploads<?= $row['image']; ?>" 
                alt="Product Image" 
                style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
              </td>
              <td><?= htmlspecialchars($row['status']); ?></td>
              <td><?= htmlspecialchars($row['created_at']); ?></td>
              <td>
                <a href="edit_product.php?product_id=<?= $row['product_id']; ?>" class="action-btn edit-btn">Edit</a> |
                <a href="delete_product.php?product_id=<?= $row['product_id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
              </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
      <tr> 
        <td colspan="6">No products found.</td>
      </tr>
    <?php endif; ?>
    </tbody>

  </table>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>