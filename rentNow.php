<?php
include 'includes/header.php';

// Get product data from GET
$id = $_GET['id'] ?? null;
$image = $_GET['image'] ?? 'default.jpg';
$name = $_GET['name'] ?? 'Product Name';
$price = $_GET['price'] ?? 0;

if (!$id || !$name) {
    die("Invalid product selected.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent | FitSphere</title>
    <link rel="stylesheet" href="assets/css/RentNow.css">

</head>
<body>
    
<div class="rent-page">
    <div class="rent-container">
        
        <div class="rent-image">
            <img src="assets/images/suits/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($name); ?>">
        </div>

        <div class="rent-details">
            <h2><?php echo htmlspecialchars($name); ?></h2>

            <p class="price">Rs. <?php echo number_format($price); ?></p>

            <button class="measurement-btn" onclick="openMeasurement()">Size Guide</button>

            <form action="add_to_cart.php" method="GET">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <input type="hidden" name="price" value="<?php echo $price; ?>">
                <input type="hidden" name="image" value="<?php echo htmlspecialchars($image); ?>">

                <div class="option">
                    <label>Quantity:</label>
                    <input type="number" name="qty" value="1" min="1" required>
                </div>

                <div class="option">
                    <label>Size:</label>
                    <select name="size" required>
                        <option>S</option>
                        <option>M</option>
                        <option>L</option>
                        <option>XL</option>
                        <option>XXL</option>
                    </select>
                </div>

                <div class="option">
                    <label>Start Date:</label>
                    <input type="date" name="start_date" required>
                </div>

                <div class="option">
                    <label>End Date:</label>
                    <input type="date" name="end_date" required>
                </div>

                <button type="submit" class="add-cart">Add to Cart</button>
                <a href="collection.php" class="cancel">Cancel</a>
            </form>

            
        </div>
    </div>

    
    <!-- ------------------ Reviews Section ------------------- -->
    <div class="reviews-box">
        <h3>Customer Reviews</h3>

        <div class="review">
            <p style="color:#777; font-style:italic;">No reviews yet for this product.</p>
        </div>
    </div>

</div>

<!-- ------------------ Measurement Modal ------------------- -->
<div class="measurement-modal" id="measureModal">
    <div class="measurement-content">
        <span class="close-modal" onclick="closeMeasurement()">&times;</span>
        <h3>Measurement Guide</h3>

        <table class="measure-table">
            <tr>
                <th>Size</th>
                <th>Chest</th>
                <th>Waist</th>
                <th>Height</th>
            </tr>
            <tr>
                <td>S</td>
                <td>34–36"</td>
                <td>28–30"</td>
                <td>5'4 – 5'6"</td>
            </tr>
            <tr>
                <td>M</td>
                <td>37–39"</td>
                <td>31–33"</td>
                <td>5'6 – 5'8"</td>
            </tr>
            <tr>
                <td>L</td>
                <td>40–42"</td>
                <td>34–36"</td>
                <td>5'8 – 6'0"</td>
            </tr>
            <tr>
                <td>XL</td>
                <td>43–45"</td>
                <td>37–39"</td>
                <td>6'0 – 6'2"</td>
            </tr>
            <tr>
                <td>XXL</td>
                <td>46–48"</td>
                <td>40–42"</td>
                <td>6'2 – 6'4"</td>
            </tr>
        </table>
    </div>
</div>

<script>
function openMeasurement() {
    document.getElementById("measureModal").style.display = "flex";
}
function closeMeasurement() {
    document.getElementById("measureModal").style.display = "none";
}
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
