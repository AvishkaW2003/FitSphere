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
  <title>FitSphere</title>

  <?php
    // Automatically detect if we're in admin folder or main site
    $basePath = (strpos($_SERVER['SCRIPT_NAME'], '/admin') !== false) ? '../' : '';
  ?>
  <link rel="stylesheet" href="<?= $basePath ?>../assets/css/style.css">
</head>

<body>
  <header>
    <a href="<?= $basePath ?>index.php" class="logopng">
      <img src="<?= $basePath ?>../assets/images/White Logo.png" alt="FitSphere Logo" class="logo-image" style="height: 60px;">
    </a>

    <a href="#" class="logo" style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;">FitSphere</a>

    <nav>
      <a href="#" class="navText">Dashboard</a>
      <a href="#" class="navText">Products</a>
      <a href="#" class="navText">Bookings</a>
      <a href="#" class="navText">Users</a>
      <a href="#" class="navText">Settings</a>
    </nav>

    <!--  Profile & Add Admin -->
    <div class="d-flex align-items-center gap-3" style="margin-left: 4rem;">
      <!-- Add New Admin Button -->
        <a href="<?= $basePath ?>add_admin.php" class="btn-add-admin btn-sm fw-semibold" >
            + Admin
        </a>

        <a href="<?= $basePath ?>index.php" class="profilepng">
        <img src="<?= $basePath ?>../assets/images/account.png" alt=" Profile Icon" class="logo-image" style="height: 40px; margin-left: 5px;">
        </a>
    </div>
  </header>

  <!-- Open the main container -->
  <main>
