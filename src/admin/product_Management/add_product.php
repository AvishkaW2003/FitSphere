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
    $price       = $_POST['price'];
    $stock       = $_POST['stock'];
    $status      = $_POST['status'];
    $description = $_POST['description'];

    // Image upload (optional)
    $image = null;

        if (!empty($_FILES['image']['name'])) {
    $imageName = time() . "_" . basename($_FILES['image']['name']);
    $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/FitSphere/assets/images/uploads" . $imageName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        $image = $imageName;   // THIS is what we save in the database
    }
    }

    $stmt = $conn->prepare("INSERT INTO products 
        (title, category, size, price, stock, image, description, status, created_at)
        VALUES (:title, :category, :size, :price, :stock, :image, :description, :status, NOW())");

    $stmt->bindParam(':title',       $title);
    $stmt->bindParam(':category',    $category);
    $stmt->bindParam(':size',        $size);
    $stmt->bindParam(':price',       $price);
    $stmt->bindParam(':stock',       $stock);
    $stmt->bindParam(':image',       $image);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':status',      $status);

    if ($stmt->execute()) {
        header("Location: manage_products.php?success=1");
        exit;
    } else {
        $error = "Failed to add product!";
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

        <h4>Add New Product</h4>

        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="grid-2">
                <div>
                    <label>Title</label>
                    <input type="text" name="title" required>
                </div>

                <div>
                    <label>Category</label>
                    <select name="category" required>
                        <option value="" disabled selected>Select Category</option>
                        <option>Business</option>
                        <option>Dinner</option>
                        <option>Wedding</option>
                        <option>Indian</option>
                        <option>Classic</option>
                    </select>
                </div>

               <div class="size-container">
                    <label class="size-title">Size</label>

                    <div class="size-buttons">
                        <input type="radio" name="size" id="size-s" value="S">
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
                    <label>Price</label>
                    <input type="number" name="price" required>
                </div>

                <div>
                    <label>Stock</label>
                    <input type="number" name="stock" required>
                </div>

                <div>
                    <label>Image</label>
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
            <button type="submit" class="submit-btn">ADD PRODUCT</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='../manage_products.php'">Cancel</button>
            

        </form>

    </div>
</div>

</body>
</html>
<?php include '../../../includes/footerAdmin.php'; ?>