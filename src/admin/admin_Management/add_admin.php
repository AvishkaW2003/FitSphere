<?php
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';

require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';

AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$database = new Database();
$conn = $database->connect();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role']; 
    $joinDate = date("Y-m-d");

    // Insert into USERS table
    $stmt = $conn->prepare("
        INSERT INTO users (name, email, password, role, phone_no, join_date, status)
        VALUES (:name, :email, :password, :role, :phone_no, :join_date, 'Active')
    ");

    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':password' => $password,
        ':role' => $role,
        ':phone_no' => $phone,
        ':join_date' => $joinDate
    ]);

    header("Location: manage_admin.php?success=1");
    exit;
}
?>
