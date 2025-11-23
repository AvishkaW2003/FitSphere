<?php
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';

require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

// DB Connect
$database = new Database();
$conn = $database->connect();

// If form submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title       = $_POST['title'];
    $category    = $_POST['category'];
    $size        = $_POST['size'];
    
    // ✅ FIX 1: Read price using the correct key 'price' from the HTML form.
    $price       = $_POST['price']; 
    
    $stock       = $_POST['stock'];
    $status      = $_POST['status'];
    $description = $_POST['description'];
    $image       = null;
    $error       = null;

    // --- 1. Handle Image Upload ---
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        // Ensure the upload path is correct and consistent
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/FitSphere/assets/images/suits/"; 
        $targetPath = $targetDir . $imageName; 

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $image = $imageName;
        } else {
            $error = "Failed to upload image.";
        }
    }
    
    if (!$error) {
        try {
            // Start Transaction
            $conn->beginTransaction();

            // --- 2. Insert into product_styles (General Product Info) ---
            $stmtStyle = $conn->prepare("
                INSERT INTO product_styles 
                (title, category, price_per_day, description, image, created_at)
                VALUES (:title, :category, :price_per_day, :description, :image, NOW())
            ");
            $stmtStyle->bindParam(':title', $title);
            $stmtStyle->bindParam(':category', $category);
            // ✅ FIX 2: Use $stmtStyle for binding price.
            $stmtStyle->bindParam(':price_per_day', $price); 
            $stmtStyle->bindParam(':description', $description);
            $stmtStyle->bindParam(':image', $image);
            $stmtStyle->execute();

            // Get the ID of the new style
            $style_id = $conn->lastInsertId();

            // --- 3. Insert into product_inventory (Size/Stock Info) ---
            // ✅ FIX 3: Removed 'created_at' as it does not exist in the product_inventory table.
            $stmtInventory = $conn->prepare("
                INSERT INTO product_inventory 
                (style_id, size, stock, status)
                VALUES (:style_id, :size, :stock, :status)
            ");
            $stmtInventory->bindParam(':style_id', $style_id);
            $stmtInventory->bindParam(':size', $size);
            $stmtInventory->bindParam(':stock', $stock);
            $stmtInventory->bindParam(':status', $status);
            $stmtInventory->execute();

            // Commit transaction
            $conn->commit();

            header("Location: manage_products.php?success=1");
            exit;

        } catch (PDOException $e) {
            $conn->rollBack();
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Product</title>
<link rel="stylesheet" href="../../../assets/css/adminManagement.css?v=<?= time() ?>">
</head>

<body>

<div class="admin-container">

<h2 class="text-center">Manage Product</h2>

<div class="form-card-prodcut">

 <h4>Add New Product Style/Item</h4>

 <?php if (!empty($error)): ?>
 <p style="color:red;"><?= $error ?></p>
 <?php endif; ?>

 <form method="POST" enctype="multipart/form-data">

 <div class="grid-2">
  <div>
  <label>Title (Style Name)</label>
  <input type="text" name="title" required>
  </div>

  <div>
  <label>Category</label>
  <select name="category" required>
   <option value="" disabled selected>Select Category</option>
   <option value="Business">Business</option>
   <option value="Dinner">Dinner</option>
   <option value="Wedding">Wedding</option>
   <option value="Indian">Indian</option>
   <option value="Classic">Classic</option>
   <option value="Blazer">Blazer</option>
   <option value="Nilame">Nilame</option>
  </select>
  </div>

 <div class="size-container">
  <label class="size-title">Size (Initial Inventory)</label>

  <div class="size-buttons">
   <input type="radio" name="size" id="size-s" value="S" required>
   <label for="size-s" class="size-btn">S</label>

   <input type="radio" name="size" id="size-m" value="M">
   <label for="size-m" class="size-btn">M</label>

   <input type="radio" name="size" id="size-l" value="L">
   <label for="size-l" class="size-btn">L</label>

   <input type="radio" name="size" id="size-xl" value="XL">
   <label for="size-xl" class="size-btn">XL</label>

   <input type="radio" name="size" id="size-xxl" value="XXL">
   <label for="size-xxl" class="size-btn">XXL</label>
  </div>
  </div>


  <div style="margin-top: 2rem;">
  <label>Price / Day</label>
  <input type="number" name="price" required>
  </div>

  <div>
  <label>Initial Stock</label>
  <input type="number" name="stock" required>
  </div>

  <div>
  <label>Image (Main Style Image)</label>
  <input type="file" name="image" accept="image/*">
  </div>

  <div>
  <label>Status</label>
  <select name="status" required>
   <option value="Available">Available</option>
   <option value="Unavailable">Unavailable</option>
  </select>
  </div>
 </div>

 <label>Description</label>
 <textarea name="description" rows="4"></textarea>

 <br><br>
 <button type="submit" class="submit-btn">ADD PRODUCT STYLE</button>
 <button type="button" class="cancel-btn" onclick="window.location.href='manage_products.php'">Cancel</button>
 

 </form>

 </div>
</div>

</body>
</html>
<?php include '../../../includes/footerAdmin.php'; ?>