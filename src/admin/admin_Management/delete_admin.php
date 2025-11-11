<?php
require_once __DIR__ . '/../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

include '../includes/db_connect.php';

$id = $_GET['id'];
$conn->query("DELETE FROM admins WHERE id=$id");
header("Location: manage_admin.php");
exit();
?>
