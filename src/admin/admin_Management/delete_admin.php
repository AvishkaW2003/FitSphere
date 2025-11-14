<?php
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../includes/db.php';

use FitSphere\Database\Database;

AuthMiddleware::requireRole('admin');

// Create a new database connection
$database = new Database();
$conn = $database->connect();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM admins WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: manage_admin.php?deleted=1");
        exit();
    } else {
        echo "Error deleting admin.";
    }
} else {
    header("Location: manage_admin.php");
    exit();
}
?>
