<?php
include 'includes/header.php';

require_once __DIR__ . '/includes/functions.php';
use FitSphere\Core\Session;
Session::start();

$auth = new Auth();
$error = "";


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($auth->login($email, $password)) {
        $user = Session::get('user');

        // Redirect based on role
        switch ($user['role']) {
            case 'admin':
                header("Location: /FitSphere/src/admin/dashboard.php");
                exit;
            case 'user':
                header("Location: /FitSphere/dashboard.php");
                exit;
            default:
                header("Location: /FitSphere/index.php");
                exit;
        }
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | FitSphere</title>

   
    <link rel="stylesheet" href="assets/css/auth.css" />
</head>
<body>

    
    <div class="bg-image"></div>

    <!-- Gradient liquid overlay -->
    <div class="liquid-layer"></div>

   
    <div class="login-wrapper">

        <form method="POST" class="glass-card">

            <h2 class="title">Login</h2>

            <?php if (!empty($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit" class="btn-login">Login</button>

            <p class="bottom-text">
                Don't have an account?
                <a href="register.php">Register</a>
            </p>

        </form>

    </div>

    <script src="assets/js/auth.js"></script>
</body>
</html>



