<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Dynamically detect the project base directory
$rootPath = $_SERVER['DOCUMENT_ROOT'] . "/FitSphere/";
$baseUrl = "/FitSphere/";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FitSphere</title>

  <link rel="stylesheet" href="<?= $baseUrl ?>assets/css/style.css?v=<?=time()?>">
</head>

<body>
  
  <header>
    
    <a href="<?= $baseUrl ?>src/admin/dashboard.php" class="logopng">
      <img src="<?= $baseUrl ?>assets/images/White Logo.png" alt="FitSphere Logo" class="logo-image" style="height: 60px;">
    </a>

    <a href="#" class="logo" style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;">FitSphere</a>

    <nav id="mainNav">
      <a href="<?= $baseUrl ?>src/admin/dashboard.php" class="navText">Dashboard</a>
      <a href="<?= $baseUrl ?>src/admin/product_Management/manage_products.php" class="navText">Products</a>
      <a href="<?= $baseUrl ?>src/admin/bookings_Management/manage_bookings.php" class="navText">Bookings</a>
      <a href="<?= $baseUrl ?>src/admin/user_Management/manage_users.php" class="navText">Users</a>
      <a href="<?= $baseUrl ?>src/admin/settings/settings.php" class="navText">Settings</a>
      <a href="<?= $baseUrl ?>src/admin/logout.php" class="navText" onclick="return confirm('Are you sure you want to Logot?');">Logout</a>
    </nav>

    <div class="d-flex align-items-center gap-3" style="margin-left: 4rem;">
        <a href="<?= $baseUrl ?>src/admin/admin_Management/manage_admin.php" class="btn-add-admin btn-sm fw-semibold">
            + Admin
        </a>
    </div>
    
    <div class="d-flex align-items-center gap-3" style="margin-left: 2rem;">
          <a href="<?= $baseUrl ?>src/admin/admin_profile/profile.php" class="profilepng">
          <img src="<?= $baseUrl ?>assets/images/account.png" alt="Profile Icon" class="logo-image" style="height: 40px; margin-left: 5px;">
        </a>
    </div>
  </header>
  
  <main>

