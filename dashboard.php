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

    <?php include 'includes/header.php'; ?>

    <div class="section">
        <div class="text_sec">
            <h1>Welcome<br>(username)<br></h1>
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
            <tr>
                <td>001</td>
                <td>Classic Black Suit</td>
                <td>2025-01-10</td>
                <td>2025-01-12</td>
                <td>Confirmed</td>
            </tr>

            <tr>
                <td>002</td>
                <td>Royal Blue Tuxedo</td>
                <td>2025-02-03</td>
                <td>2025-02-05</td>
                <td>Pending</td>
            </tr>

            <tr>
                <td>003</td>
                <td>Wedding White Suit</td>
                <td>2025-03-18</td>
                <td>2025-03-20</td>
                <td>Returned</td>
            </tr>
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

    <!-- 🔥 LIVE LOCATION MAP SECTION -->
    <section class="map-section">
        <h2>Grab your suit here</h2>
        <div id="mapCircle"></div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        // Initialize Map
        var map = L.map('mapCircle', {
            zoomControl: false
        }).setView([0, 0], 15);

        // Add tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);

        // User marker
        var marker = L.marker([0, 0]).addTo(map);

        // GPS tracking
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(
                function (pos) {
                    let lat = pos.coords.latitude;
                    let lon = pos.coords.longitude;

                    marker.setLatLng([lat, lon]);
                    map.setView([lat, lon], 17);
                },
                function (err) {
                    alert("Location error: " + err.message);
                },
                { enableHighAccuracy: true }
            );
        }
    </script>

</body>
</html>
