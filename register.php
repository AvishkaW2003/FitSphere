<?php
include 'includes/header.php';

require_once __DIR__ . '/includes/functions.php';
use FitSphere\Core\Session;
use FitSphere\Database\Database;

Session::start();

$db = new Database();
$conn = $db->connect();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        $message = "All fields are required!";
    } else {

        // Check duplicate email
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $message = "Email already registered!";
        } else {

            // Insert new user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $today = date('Y-m-d');

            $stmt = $conn->prepare("
                INSERT INTO users (name, email, phone_no, password, role, join_date, status)
                VALUES (:name, :email, :phone, :password, 'user', :join_date, 'Active')
            ");

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":password", $hashedPassword);
            $stmt->bindParam(":join_date", $today);

            if ($stmt->execute()) {
                $message = "✅ Registration successful! You can now log in.";
            } else {
                $message = "❌ Registration failed!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | FitSphere</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>

<body>

<div class="bg-wrap"></div>
<div class="bg-overlay"><div class="liquid-gradient"></div></div>

<div class="center-wrap">

    <div class="hero-card d-none d-md-flex">
        <div class="brand">
            <img src="/FitSphere/assets/images/White Logo.png" alt="">
            <h1>FitSphere</h1>
        </div>

        <p class="lead"><b>Timeless Style. Effortless Renting.</b></p>
        <p class="lead small">Register now and start your rental journey.</p>
    </div>

    <div class="glass">
        <h2>Register</h2>

        <?php if (!empty($message)): ?>
            <div class="error-box"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="input-group">
                <input type="text" name="name" placeholder="Full Name" required>
            </div>

            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="input-group">
                <input type="text" name="phone" placeholder="Phone Number" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit" class="btn-primary">Create Account</button>

            <p class="bottom-text">
                Already have an account? <a href="login.php">Login</a>
            </p>

        </form>

    </div>

</div>

</body>
</html>
