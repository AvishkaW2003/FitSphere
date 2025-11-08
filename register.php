<?php
require_once __DIR__ . '/../includes/functions.php'; // Load DB + Auth + Session

use FitSphere\Core\Session;
use FitSphere\Database\Database;

// Start session
Session::start();

// Create DB connection
$db = new Database();
$conn = $db->connect();

// If form is submitted
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'user';

    // Validate input
    if (empty($email) || empty($password)) {
        $message = 'Please fill all fields!';
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $message = 'Email already registered!';
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (:email, :password, :role)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $role);

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | FitSphere</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            width: 320px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            background: #D4AF37;
            border: none;
            padding: 10px;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background: #b9962e;
        }
        p {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <h2 style="text-align:center;">Register</h2>
        <?php if (!empty($message)) echo "<p>$message</p>"; ?>
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
            <option value="guest">Guest</option>
        </select>
        <button type="submit">Register</button>
        <p style="color: #333;">Already have an account? <a href="login.php">Login</a></p>
    </form>
</body>
</html>
