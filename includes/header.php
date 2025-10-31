<?php
session_start();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>FitSphere</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <header>
        <a href="#" class="logo">RentFit</a>

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
            <button type="button" class="Login-btn-model">Login</button>
            <?php endif; ?>
        </div>

    </header>
  <main>
