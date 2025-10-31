<?php
session_start();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>My E-Shop</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <header>
    <h1><a href="index.php">My E-Shop</a></h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="cart.php">Cart</a>
      <?php if(isset($_SESSION['user_id'])): ?>
        <span>Hi, <?=htmlspecialchars($_SESSION['user_name'])?></span>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
      <?php endif; ?>
    </nav>
  </header>
  <main>
