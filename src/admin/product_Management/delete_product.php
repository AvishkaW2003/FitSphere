<?php
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../includes/db.php';

use FitSphere\Database\Database;

AuthMiddleware::requireRole('admin');

// Create a new database connection
$database = new Database();
$conn = $database->connect();

if (isset($_GET['product_id'])) {
  $inventory_id = intval($_GET['product_id']);

  try {
    // --- DELETE from product_inventory ---
    $stmt = $conn->prepare("DELETE FROM product_inventory WHERE product_id = :inventory_id");
    $stmt->bindParam(':inventory_id', $inventory_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
      // Optional: Check if any inventory items remain for the style. If not, delete the style too.
      // This logic is complex, so we omit it here, keeping the style record for now.
      header("Location: manage_products.php?deleted=1");
      exit();
    } else {
      // This error will likely be caught by the PDOException, but kept as a fallback
      echo "Error deleting product inventory item.";
    }
  } catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
  }
} else {
  header("Location: manage_products.php");
  exit();
}
// Footer include is not necessary here as it only handles redirection/output
?>