<?php
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';

require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

// Create database object and connect
$database = new Database();
$conn = $database->connect();

// Base query joins styles and inventory
$query = "
    SELECT 
        ps.title, ps.category, ps.price_per_day, ps.image, ps.created_at,  
        pi.product_id, pi.size, pi.stock, pi.status
    FROM product_inventory pi
    JOIN product_styles ps ON pi.style_id = ps.style_id
    WHERE 1
";

// Filtering
if (!empty($_GET['category'])) {
    $query .= " AND ps.category = :category";
}

$query .= " ORDER BY ps.title ASC, FIELD(pi.size, 'S', 'M', 'L', 'XL', 'XXL')";

// Line 37 is where execute() runs:
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
  <title>Manage Products</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/css/adminManagement.css">
</head>
<body>

<div class="admin-container">
 <h2 class="text-center my-4">Manage Products (Inventory Items)</h2>

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
    <option value="Blazer" <?= ($_GET['category'] ?? '') == "Blazer" ? 'selected' : '' ?>>Blazer</option>
    <option value="Classic" <?= ($_GET['category'] ?? '') == "Classic" ? 'selected' : '' ?>>Classic</option>
 </select>

 <button class="btn btn-warning fw-semibold">Filter</button>
 </form>
</div>

<table>
 <thead style="background-color: #d4af37ff;">
 <tr>
  <th>Inventory ID</th>
  <th>Title (Style)</th>
  <th>Category</th>
  <th>Size</th>
  <th>Price/Day</th>
  <th>Stock</th>
  <th>Image</th>
  <th>Status</th>
  <th>Added at</th>
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
   <td><?= htmlspecialchars($row['price_per_day']); ?></td>
   <td><?= htmlspecialchars($row['stock']); ?></td>

   <td>
    <img src="<?= $baseUrl ?>/assets/images/suits/<?= $row['image']; ?>" 
    alt="Product Image" 
    style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
   </td>
   <td><?= htmlspecialchars($row['status']); ?></td>
   <td><?= htmlspecialchars($row['created_at']); ?></td>
   <td>
    <a href="edit_product.php?product_id=<?= $row['product_id']; ?>" class="action-btn edit-btn">Edit</a> |
    <a href="delete_product.php?product_id=<?= $row['product_id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this specific size/inventory item?')">Delete</a>
   </td>
  </tr>
  <?php endforeach; ?>
 <?php else: ?>
 <tr> 
  <td colspan="11">No products found.</td>
 </tr>
 <?php endif; ?>
 </tbody>

</table>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include '../../../includes/footerAdmin.php'; ?>