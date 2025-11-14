<?php
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';

require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$database = new Database();
$conn = $database->connect();

// === FETCH PRODUCT ===
if (!isset($_GET['product_id'])) {
    die("No product ID provided!");
}

$product_id = $_GET['product_id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = :product_id");
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found!");
}

// === UPDATE PRODUCT ===
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST['title'];
    $category = $_POST['category'];
    $sizes = $_POST['sizes'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $status = $_POST['status'];

    // Handle image upload
    $newImage = $product['image']; // keep old image if no new upload

    if (!empty($_FILES["image"]["name"])) {
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/FitSphere/uploads/products/" . $imageName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
            $newImage = $imageName;
        }
    }

    // Update query
    $update = $conn->prepare("
        UPDATE products 
        SET title = :title, category = :category, size = :size, price = :price, stock = :stock, 
            image = :image, status = :status 
        WHERE product_id = :product_id
    ");

    $update->bindParam(":title", $title);
    $update->bindParam(":category", $category);
    $update->bindParam(":size", $sizes);
    $update->bindParam(":price", $price);
    $update->bindParam(":stock", $stock);
    $update->bindParam(":image", $newImage);
    $update->bindParam(":status", $status);
    $update->bindParam(":product_id", $product_id);

    if ($update->execute()) {
        header("Location: manage_products.php?updated=1");
        exit;
    } else {
        $error = "Error updating product!";
    }
}

$currentSizes = explode(",", $product['size']);
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
    <h2>Edit Product</h2>

    <div class="form-card">

        <?php if (!empty($error)): ?>
            <p class="text-danger"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <label>Title</label>
            <input type="text" name="title" value="<?= $product['title'] ?>" required>

            <label>Category</label>
            <input type="text" name="category" value="<?= $product['category'] ?>" required>

            <label>Sizes</label>
            <div class="size-button">
                <?php 
                $sizeOptions = ['S','M','L','XL','XXL'];
                foreach ($sizeOptions as $s): ?>
                    <label style="display:inline-block; margin-right:10px; font-weight:500;">
                        <input type="radio" name="sizes" value="<?= $s ?>" 
                            <?= $product['size'] == $s ? "checked" : "" ?>>
                        <?= $s ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <label>Price / Day</label>
            <input type="number" name="price" value="<?= $product['price'] ?>" required>

            <label>Stock</label>
            <input type="number" name="stock" value="<?= $product['stock'] ?>" required>

            <label>Current Image</label><br>
            <img src="/FitSphere/assets/images/uploads<?= $product['image'] ?>" 
                 style="width:100px; margin-bottom:10px; border-radius:5px;">

            <label>Upload New Image</label>
            <input type="file" name="image">

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
