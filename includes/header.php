<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header>
        <a href="#" class="logopng">
            <img src="../assets/images/FitSphere.png" alt="FitSphere Logo" class="logo-image">
        </a>

        <a href="#" class="logo">FitSphere</a>

        <nav>
            <a href="#">How It Works</a>
            <a href="#">View Clothing</a>
            <a href="#">Offers</a>
            <a href="#">About</a>
            <a href="#">Cart</a>
          
        </nav>
        
        <div class="user-auth">
            <?php if (!empty($name)): ?>
            <div class="profile-box">
                <div class="avatar-circle"><?= strtoupper($name[0]); ?></div>
                <div class="dropdown">
                    <a href="#">My Account</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
            <?php else: ?>
            
            <button type="button" class="Login-btn-model" onclick="window.location.href='FitSphere/login.php'">Login</button>
            <?php endif; ?>
        </div>

    </header>
  <main>
