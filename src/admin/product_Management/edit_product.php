<?php
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';

require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$database = new Database();
$conn = $database->connect();

if (!isset($_GET['product_id'])) {
 die("No inventory ID provided!");
}

$inventory_id = $_GET['product_id'];
$error = null;

// === 1. FETCH PRODUCT DATA (JOINING styles and inventory) ===
$stmt = $conn->prepare("
  SELECT 
    pi.product_id, pi.style_id, pi.size, pi.stock, pi.status, 
    ps.title, ps.category, ps.price_per_day, ps.description, ps.image  
  FROM product_inventory pi
  JOIN product_styles ps ON pi.style_id = ps.style_id
  WHERE pi.product_id = :inventory_id
");
$stmt->bindParam(':inventory_id', $inventory_id, PDO::PARAM_INT);
$stmt->execute(); // Line 31
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
 die("Product inventory item not found!");
}

// === 2. UPDATE PRODUCT ===
if ($_SERVER["REQUEST_METHOD"] === "POST") {

 $title = $_POST['title'];
 $category = $_POST['category'];
 $size = $_POST['size']; 
 $price = $_POST['price'];
 $stock = $_POST['stock'];
 $status = $_POST['status'];
 $description = $_POST['description'];

 // Handle image upload
 $newImage = $product['image']; 

 if (!empty($_FILES["image"]["name"])) {
  $imageName = time() . "_" . basename($_FILES["image"]["name"]);
  $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/FitSphere/assets/images/uploads/" . $imageName; 

  if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
   $newImage = $imageName;
  } else {
   $error = "Error uploading new image.";
  }
 }

 if (!$error) {
  try {
   $conn->beginTransaction();
   
   // A. Update product_styles (general info and price)
   // ✅ FIX 3: Add price_per_day update here, where the column actually exists.
   $updateStyle = $conn->prepare("
    UPDATE product_styles 
    SET title = :title, category = :category, description = :description, image = :image, price_per_day = :price
    WHERE style_id = :style_id
   ");
   $updateStyle->bindParam(":title", $title);
   $updateStyle->bindParam(":category", $category);
   $updateStyle->bindParam(":description", $description);
   $updateStyle->bindParam(":image", $newImage);
   $updateStyle->bindParam(":price", $price);
   $updateStyle->bindParam(":style_id", $product['style_id']);
   $updateStyle->execute();

   // B. Update product_inventory (size/stock/status info)
   // ✅ FIX 4: Removed price_per_day from this query, as it does not exist in product_inventory.
   $updateInventory = $conn->prepare("
    UPDATE product_inventory 
    SET size = :size, stock = :stock, status = :status 
    WHERE product_id = :inventory_id
   ");
   $updateInventory->bindParam(":size", $size);
   $updateInventory->bindParam(":stock", $stock);
   $updateInventory->bindParam(":status", $status);
   $updateInventory->bindParam(":inventory_id", $inventory_id);
   $updateInventory->execute();

   $conn->commit();
   header("Location: manage_products.php?updated=1");
   exit;

  } catch (PDOException $e) {
   $conn->rollBack();
   $error = "Database Error: " . $e->getMessage();
  }
 }
}

// Re-fetch product data for form display
// We execute again to ensure the latest data is displayed if the POST failed or passed.
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <title>Edit Product</title>
 <link rel="stylesheet" href="../../../assets/css/adminManagement.css?v=<?= time() ?>">
</head>

<body>

<div class="admin-container">
 <h2>Edit Product (ID: <?= $product['product_id'] ?>)</h2>

 <div class="form-card">

  <?php if (!empty($error)): ?>
   <p class="text-danger"><?= $error ?></p>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">

   <label>Title</label>
   <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>" required>
   
   <label>Category</label>
   <select name="category" required>
    <?php 
    $categories = ['Business', 'Dinner', 'Wedding', 'Indian', 'Classic', 'Blazer', 'Nilame'];
    foreach ($categories as $cat): ?>
     <option value="<?= $cat ?>" <?= $product['category'] == $cat ? 'selected' : '' ?>><?= $cat ?></option>
    <?php endforeach; ?>
   </select>

   <label>Size</label>
   <div class="size-button">
    <?php 
    $sizeOptions = ['S','M','L','XL','XXL'];
    foreach ($sizeOptions as $s): ?>
     <label style="display:inline-block; margin-right:10px; font-weight:500;">
      <input type="radio" name="size" value="<?= $s ?>" 
       <?= $product['size'] == $s ? "checked" : "" ?> required>
      <?= $s ?>
     </label>
    <?php endforeach; ?>
   </div>

   <label>Price / Day</label>
   <input type="number" name="price" value="<?= htmlspecialchars($product['price_per_day']) ?>" required>

   <label>Stock</label>
   <input type="number" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required>

   <label>Current Image</label><br>
   <img src="/FitSphere/assets/images/uploads/<?= htmlspecialchars($product['image']) ?>" 
    style="width:100px; margin-bottom:10px; border-radius:5px;">

   <label>Upload New Image (Applies to all items of this Style)</label>
   <input type="file" name="image">

   <label>Description</label>
   <textarea name="description" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>

   <label>Status</label>
   <select name="status">
    <option value="Available" <?= $product['status']=="Available" ? "selected" : "" ?>>Available</option>
    <option value="Unavailable" <?= $product['status']=="Unavailable" ? "selected" : "" ?>>Unavailable</option>
   </select>

   <button type="submit" class="submit-btn">Save Changes</button>
   <button type="button" class="cancel-btn" onclick="window.location.href='manage_products.php'">Cancel</button>

  </form>

 </div>
</div>

</body>
</html>

<?php include '../../../includes/footerAdmin.php'; ?>