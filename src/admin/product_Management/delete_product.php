<?php
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../includes/db.php';

use FitSphere\Database\Database;

AuthMiddleware::requireRole('admin');

// Create a new database connection
$database = new Database();
$conn = $database->connect();

if (isset($_GET['product_id'])) {
    $id = intval($_GET['product_id']);

    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = :product_id");
    $stmt->bindParam(':product_id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: manage_products.php?deleted=1");
        exit();
    } else {
        echo "Error deleting product.";
    }
} else {
    header("Location: manage_products.php");
    exit();
}
?>
