<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/includes/functions.php';
include 'includes/header.php';

use FitSphere\Database\Database;
use FitSphere\Core\Session;

// Session::start(); // NOTE: If session.php contains session_start(), this line might be redundant.

if (!isset($_SESSION['user'])) {
    header("Location: /FitSphere/login.php");
    exit;
}

// FIX: Safely retrieve the user ID, checking for common key discrepancies
$userId = $_SESSION['user']['user_id'] ?? $_SESSION['user']['id'] ?? null;
$display_name = $_SESSION['user']['name'] ?? 'User'; // Fallback for name

if (!$userId) {
    header("Location: /FitSphere/login.php?error=no_id");
    exit;
}

$db = new Database();
$conn = $db->connect();

// Fetch bookings for the logged-in user with product details
$stmt = $conn->prepare("
    SELECT 
        b.booking_id,
        ps.title AS suit_name,
        b.start_date,
        b.end_date,
        b.status
    FROM bookings b
    LEFT JOIN product_inventory pi ON b.product_id = pi.product_id
    LEFT JOIN product_styles ps ON pi.style_id = ps.style_id
    WHERE b.customer_id = :uid
    ORDER BY b.booking_id DESC
");
$stmt->bindParam(":uid", $userId);
$stmt->execute();

$userBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta-name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Dashboard</title>
        <link rel="stylesheet" href="assets/css/dashboard1.css">

        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>

<body>
    <main class="dashboard-content-wrapper">
        <div class="section">
            <div class="text_sec">
                <h1>Welcome<br><?= $display_name ?><br></h1>
            </div>
        </div>

        <div class="brands">
            <img src="assets/images/brands/Brioni_logo_PNG_(1).png" alt="Brioni">
            <img src="assets/images/brands/NB-Logo-Black_80-1.png" alt="nb">
            <img src="assets/images/brands/Giorgio_Armani_(2).png" alt="Giorgio">
            <img src="assets/images/brands/output-onlinepngtools.png" alt="Prada">
            <img src="assets/images/brands/Ermenegildo_Zegna_(1).png" alt="Versace">
        </div>

        <!-- 🔥 USER BOOKINGS TABLE SECTION -->
        <section class="bookings-section">
            <h2>Your Bookings</h2>

            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Suit Name</th>
                        <th>Pickup Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($userBookings)): ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">No bookings found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($userBookings as $b): ?>
                            <tr>
                                <td><?= htmlspecialchars($b['booking_id']) ?></td>
                                <td><?= htmlspecialchars($b['suit_name']) ?></td>
                                <td><?= htmlspecialchars($b['start_date']) ?></td>
                                <td><?= htmlspecialchars($b['end_date']) ?></td>
                                <td><?= htmlspecialchars($b['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>


        <div class="main04">
            <h2>Rent a Suit for Every Occasion</h2>
            <div class="cat001">
                <div class="cat01">
                    <img src="assets/images/suits/Polished Business Look.jpg" alt="Business_suit"><br>
                    <a href="collection.php#Business_suit" class="cat">Business Suits</a>
                    <p>Formal and professional suits ideal for office, meetings, and corporate events.</p>
                </div>
                <div class="cat01">
                    <img src="assets/images/suits/Black Slim-Fit Tuxedo 3-Piece.jpg" alt="Business_suit"><br>
                    <a href="collection.php#Dinner_suit" class="cat">Dinner Suits</a>
                    <p>Elegant evening wear with satin details, perfect for black-tie events and formal dinners.</p>
                </div>
                <div class="cat01">
                    <img src="assets/images/suits/Venue decorations wedding trends for a….jpg" alt="Business_suit"><br>
                    <a href="collection.php#Wedding_Suits" class="cat">Wedding Suits</a>
                    <p>Stylish and special suits designed for grooms and wedding ceremonies.</p>
                </div>
                <div class="cat01">
                    <img src="assets/images/suits/hon-1.jpg" alt="Business_suit"><br>
                    <a href="collection.php#Nilame_Suits" class="cat">Nilame Suits</a>
                    <p>Traditional Sri Lankan ceremonial attire inspired by Kandyan royalty.</p>
                </div>
                <div class="cat01">
                    <img src="assets/images/suits/Indian suit.webp" alt="Business_suit"><br>
                    <a href="collection.php#Indian_Suits" class="cat">Indian Suits</a>
                    <p>Opulent, embroidered Sherwanis and Bandhgalas for stunning wedding and celebratory looks</p>
                </div>
                <div class="cat01">
                    <img src="assets/images/suits/blazer.jpg" alt="Business_suit"><br>
                    <a href="collection.php#Blazers" class="cat">Blazers</a>
                    <p>Formal and professional Blazers ideal for office, meetings, and corporate events.</p>
                </div>
            </div>

            <a href="collection.php" class="collectionNav">EXPLORE COLLECTION</a>

            <div class="about03">
                <h2>Why you use FitSphere</h2>

                <div class="why-cont">
                    <div class="why1">
                        <img src="assets/images/uploads/why001.png" alt="Easy Booking">
                        <h3>Easy Booking</h3>
                    </div>

                    <div class="why1">
                        <img src="assets/images/uploads/why002.png" alt="Home Delivery">
                        <h3>Home Delivery</h3>
                    </div>

                    <div class="why1">
                        <img src="assets/images/uploads/why003.png" alt="Size Adjustments">
                        <h3>Size Adjustments</h3>
                    </div>

                    <div class="why1">
                        <img src="assets/images/uploads/why004.png" alt="Affordable Pricing">
                        <h3>Affordable Pricing</h3>
                    </div>
                </div>

            </div>

            <section class="contact-map-section">
  <div class="container card-section bg-cover">
    <h2 class="section-title" style="color:#f8f8f8">Grab your suits here</h2>
    <div class="card-content">
      <!-- Left: Address & Contact -->
      <div class="contact-info">
        <div class="info-item">
          <span class="icon">📍</span>
          <div>
            <strong>Address</strong>
            <p>No 132/8 Bogahawatta<br>Kirindiwela</p>
            <br>
            <br>
          </div>
        </div>

        <div class="info-item mt-3">
          <span class="icon">📞</span>
          <div>
            <strong>Contact Us:</strong>
            <p>Tel: 070 5555555<br>
            Email: fitsphere@gmail.com</p>
          </div>
        </div>
      </div>

      <!-- Right: Map -->
      <div class="map-container">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3164.123456!2d79.916!3d7.056!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae23456789abcd%3A0x123456789abcdef!2sNo+132%2F8+Bogahawatta%2C+Kirindiwela!5e0!3m2!1sen!2slk!4v1699999999999!5m2!1sen!2slk" 
          width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </div>
</section>


    </main>


    <?php include 'includes/footer.php'; ?>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        var map = L.map('officeMap').setView([51.5074, -0.1278], 15); // London
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);

        L.marker([51.5074, -0.1278]).addTo(map)
            .bindPopup("<b>London Office</b><br>5901, Down Memory Lane").openPopup();
    </script>

</body>

</html>