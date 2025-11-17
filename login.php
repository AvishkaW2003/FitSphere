<?php
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
                header("Location: /FitSphere/src/user/dashboard.php");
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
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        form { width: 300px; margin: 100px auto; text-align: center; }
        input { width: 90%; margin: 5px 0; padding: 8px; }
        button { background: #D4AF37; border: none; padding: 10px; width: 100%; cursor: pointer; }
        button:hover { background: #b9982c; }
        .error { color: red; }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Login</h2>
        <?php if (!empty($error) && $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
        <a href="register.php">Register</a>
    </form>
</body>
</html>