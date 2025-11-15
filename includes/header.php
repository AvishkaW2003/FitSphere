<?php
session_start();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>FitSphere</title>
  <link rel="stylesheet" href="assets/css/udith.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>
  <header>
        <a href="index.php" class="logo">FitSphere</a>

        <input type="checkbox" id="menu-toggle" />
        <label for="menu-toggle" class="menu-icon"><i class="fa fa-bars"></i></label>


        <nav>
            <a href="HowItWorks.php">How It Works</a>
            <a href="collection.php">View Clothing</a>
            <a href="offers.php">Offers</a>
            <a href="about.php">About</a>
            <a href="cart.php">Cart</a>
          
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
