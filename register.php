<?php
include 'includes/header.php';
require_once __DIR__ . '/includes/functions.php';

use FitSphere\Core\Session;
use FitSphere\Database\Database;

Session::start();

$db = new Database();
$conn = $db->connect();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = 'Please fill all fields!';
    } else {

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $message = 'Email already registered!';
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Role fixed as 'user'
            $stmt = $conn->prepare("
                INSERT INTO users (email, password, role)
                VALUES (:email, :password, 'user')
            ");

            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);

            if ($stmt->execute()) {
                $message = '✅ Registration successful! You can now log in.';
            } else {
                $message = '❌ Failed to register.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - FitSphere</title>

    <link rel="stylesheet" href="assets/css/auth.css">
</head>

<body>

<!-- Background Layers -->
<div class="bg-wrap"></div>
<div class="bg-overlay"><div class="liquid-gradient"></div></div>

<div class="center-wrap">

    <!-- Left Info Section (same as login) -->
    <div class="hero-card d-none d-md-flex">
        <div class="brand">
            <img src="/FitSphere/assets/images/White Logo.png" alt="">
            <h1>FitSphere</h1>
        </div>

        <p class="lead">
           <b>Timeless Style. Effortless Renting.</b> 
        </p>

        <p class="lead small">
            Start your style journey with us. Register now to explore our suit collection, manage your bookings, and enjoy a seamless rental experience.
        </p>
    </div>

    <!-- Glass Register Form -->
    <div class="glass">

        <h2>Register</h2>

        <?php if (!empty($message)): ?>
            <div class="error-box"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="input-group">
                <input type="text" class="form-control" name="name" placeholder="Full Name" required>
            </div>

            <div class="input-group">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>

            <div class="input-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>

            <button class="btn-primary" type="submit">Create Account</button>

            <div class="meta-row">
                <p class="bottom-text">
                    <span>Already have an account? <a href="login.php">Login</a></span>
                </p>
                
            </div>

        </form>

    </div>

</div>

<script src="assets/js/auth.js"></script>
</body>
</html>

