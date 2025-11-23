<?php
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';

AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$database = new Database();
$conn = $database->connect();

if (!isset($_GET['id'])) {
    header("Location: manage_admin.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM users WHERE user_id = :id AND role='admin'");
$stmt->execute([':id' => $id]);

header("Location: manage_admin.php?deleted=1");
exit;
?>
