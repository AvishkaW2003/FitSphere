<?php
session_start();
// Auto-detect base URL
$baseUrl = "/FitSphere";

// Get logged-in user's data (if exists)
$user = $_SESSION['user'] ?? null;

// 1. **CRITICAL CHANGE:** Use 'id' to check for login status
$isLoggedIn = $user && isset($user['id']);

// Determine the home URL based on login status
$homeUrl = $isLoggedIn ? $baseUrl . "/src/user/dashboard.php" : $baseUrl . "/index.php";

// Initialize name and email variables
$name = null;
$email = $user['email'] ?? null;

if ($isLoggedIn && $email) {
    // 2. **CRITICAL CHANGE:** Extract display name from email
    // Example: "john.doe@example.com" becomes "John.doe"
    $emailPrefix = strtok($email, '@');
    $name = ucfirst(strtolower($emailPrefix));
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>FitSphere</title>
  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/udith.css?v=<?= time() ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<header>
    <div class="logo_main">
        <a href="<?= $homeUrl ?>" class="logopng1"> 
            <img src="<?= $baseUrl ?>/assets/images/White Logo.png" class="logo-image1" alt="FitSphere logo">
        </a>
    </div>
    <a href="<?= $homeUrl ?>" class="logo">FitSphere</a>

    <input type="checkbox" id="menu-toggle" />
    <label for="menu-toggle" class="menu-icon">
        <i class="fa fa-bars"></i>
    </label>

    <nav>
        <?php if ($isLoggedIn): // Logged-in Customer Navigation ?>
            
            <a href="<?= $baseUrl ?>/mybookings.php">My Bookings</a>
            <a href="<?= $baseUrl ?>/measurements.php">Measurements</a>
            <a href="<?= $baseUrl ?>/collection.php">Collections</a>
            <a href="<?= $baseUrl ?>/about.php">About</a>
        <?php else: // Guest Navigation ?>
            
            <a href="<?= $baseUrl ?>/HowItWorks.php">How It Works</a>
            <a href="<?= $baseUrl ?>/collection.php">View Clothing</a>
            <a href="<?= $baseUrl ?>/offers.php">Offers</a>
            <a href="<?= $baseUrl ?>/about.php">About</a>
        <?php endif; ?>
    </nav>

    <div class="user-auth">
        <?php if ($isLoggedIn): ?>
            <a href="<?= $baseUrl ?>/cart.php" class="cart-icon-link">
                <i class="fa-solid fa-shopping-cart cart-icon"></i>
            </a>

            <div class="profile-box">
                <i class="fa-solid fa-user profile-icon" title="<?= $name ?>"></i>

                <div class="dropdown">
                    <div class="dropdown-header">
                        <div class="user-info">
                            <span class="dropdown-username">Hello, <?= $name ?> </span>
                            <span class="dropdown-email"><?= $email ?></span>
                        </div>
                    </div>
                    <a href="<?= $baseUrl ?>/src/user/profile/profile.php">My Account</a>
                    
                    <form action="<?= $baseUrl ?>/logout.php" method="POST" class="logout-form">
                        <button type="submit" class="logout-btn">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <button class="Login-btn-model">
                <a href="<?= $baseUrl ?>/login.php" style="color: inherit; text-decoration:none;">Login</a>
            </button>
        <?php endif; ?>
    </div>
</header>
<main>

<style>
/* Existing styles (moved from inline to a block for clarity) */

/* Style the navigation links for the logged-in user (My Bookings, etc.) */
header nav a {
    color: white; 
    text-decoration: none;
    padding: 0 15px;
    font-size: 16px;
}

/* Aligning the cart and profile elements (User Auth) */
.user-auth {
    display: flex;
    align-items: center;
    gap: 20px; 
}

/* Cart Icon Styling */
.cart-icon-link {
    color: white;
    font-size: 24px; 
}
.cart-icon-link:hover {
    color: #ffd700; 
}

/* Profile Icon Styling (replaces avatar circle) */
.profile-icon {
    font-size: 24px;
    color: white;
    cursor: pointer;
    line-height: 1;
}

/* --- DROPDOWN FIX (CRITICAL) --- */

.profile-box {
    position: relative;
    display: inline-block; 
}

.dropdown {
    display: none;
    position: absolute;
    top: 100%;
    right: 0; 
    
    background-color: #333; 
    min-width: 200px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.5);
    z-index: 10; 
    border-radius: 5px;
    padding: 10px 0;
    background-color: rgba(51, 51, 51, 0.8); /* Make existing background slightly transparent */
    backdrop-filter: blur(8px); /* The actual glass effect */
    -webkit-backdrop-filter: blur(8px); /* Safari support */
}

/* Show the dropdown when hovering over the profile box */
.profile-box:hover .dropdown {
    display: block;
}

/* Styling the links inside the dropdown */
.dropdown a {
    color: black;
    padding: 10px 15px;
    text-decoration: none;
    display: block; 
    font-size: 14px;
    padding: 10px 15px; 
}

.dropdown a:hover {
    background-color: #555; 
}

/* Styling for the user name/email inside the dropdown */
.dropdown-header {
    border-bottom: 1px solid #555;
    padding: 0 15px 10px;
    margin-bottom: 10px;
}

.user-info {
    display: flex;
    flex-direction: column;
}

.dropdown-username {
    font-weight: bold;
    color: white;
    font-size: 16px;
}

.dropdown-email {
    font-size: 12px;
    color: #ccc;
}

/* --- LOGOUT BUTTON IMPROVEMENT (New/Adjusted CSS) --- */

.logout-form {
    /* Ensure the form doesn't disrupt the dropdown flow */
    margin: 0; 
    padding: 0;
}

.logout-btn {
    /* Style the button to look exactly like the other dropdown links */
    background: none; 
    border: none; 
    color: white; /* Match link color */
    padding: 10px 15px; 
    text-decoration: none; 
    display: block; 
    width: 100%; 
    text-align: left;
    cursor: pointer;
    font-size: 14px; /* Match link font size */
}

.logout-btn:hover {
    background-color: #555; /* Match link hover color */
}
</style>