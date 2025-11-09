<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
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
            <a href="collection.php">View Clothing</a>
            <a href="#">Offers</a>
            <a href="about.php">About</a>
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
