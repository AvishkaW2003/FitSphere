<?php
// Include necessary files and set up environment
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Define $baseUrl if not set in includes (Ensure this is correct for your environment)
$baseUrl = "/FitSphere"; 

use FitSphere\Database\Database;

// --- 1. Get Style ID and Fetch Product Data ---

$styleId = $_GET['style_id'] ?? null;

if (!$styleId || !is_numeric($styleId)) {
    die("Error: Invalid product style selected. Please return to the collection page.");
}

$product = null;
$inventory = [];

try {
    $database = new Database();
    $conn = $database->connect();
    
    // 1a. Fetch Product Style Details
    $sqlStyle = "SELECT style_id, title, price_per_day, image, description FROM product_styles WHERE style_id = :style_id";
    $stmtStyle = $conn->prepare($sqlStyle);
    $stmtStyle->bindParam(':style_id', $styleId, PDO::PARAM_INT);
    $stmtStyle->execute();
    $product = $stmtStyle->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Error: Product not found.");
    }

    // 1b. Fetch Available Inventory (Sizes with Stock > 0)
    // 🔍 FIX: Changed 'inventory_id' to 'product_id' in the SELECT statement
    $sqlInventory = "SELECT product_id, size, stock FROM product_inventory WHERE style_id = :style_id AND stock > 0 ORDER BY size";
    $stmtInventory = $conn->prepare($sqlInventory);
    $stmtInventory->bindParam(':style_id', $styleId, PDO::PARAM_INT);
    $stmtInventory->execute();
    $inventory = $stmtInventory->fetchAll(PDO::FETCH_ASSOC);

    // If no stock is found for any size, the product is currently unavailable
    if (empty($inventory)) {
        die("Error: This product is currently out of stock in all sizes.");
    }

} catch (PDOException $e) {
    // Note: The original error message is now likely fixed with the above change.
    die("Database Error: Could not load product details. " . $e->getMessage());
}

// Include the header *after* fetching data
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent | <?= htmlspecialchars($product['title']) ?> | FitSphere</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl) ?>/assets/css/RentNow.css">
</head>
<body>
    
<div class="rent-page">
    <div class="rent-container">
        
        <div class="rent-image">
            <img src="<?= htmlspecialchars($baseUrl) ?>/assets/images/suits/<?= htmlspecialchars($product['image']) ?>" 
                 alt="<?= htmlspecialchars($product['title']) ?>">
        </div>

        <div class="rent-details">
            <h2><?= htmlspecialchars($product['title']) ?></h2>

            <p class="price">Rs. <?= number_format($product['price_per_day'], 2) ?> / Day</p>
            
            <p class="description"><?= nl2br(htmlspecialchars($product['description'] ?? 'No description provided.')) ?></p>

            <button class="measurement-btn" onclick="openMeasurement()">Size Guide</button>
            
            <form action="<?= htmlspecialchars($baseUrl) ?>/add_to_cart.php" method="POST">
                
                <input type="hidden" name="style_id" value="<?= $product['style_id'] ?>">
                <input type="hidden" name="price_per_day" value="<?= $product['price_per_day'] ?>">
                
                <div class="option">
                    <label>Quantity:</label>
                    <input type="number" name="qty" value="1" min="1" max="1" required> 
                </div>

                <div class="option">
                    <label>Size:</label>
                    <select name="product_id" required>
                        <option value="">Select a Size</option>
                        
                        <?php foreach ($inventory as $item): ?>
                            <option value="<?= $item['product_id'] ?>">
                                <?= htmlspecialchars($item['size']) ?> (Stock: <?= $item['stock'] ?>)
                            </option>
                        <?php endforeach; ?>
                        </select>
                    </div>

                <div class="option">
                    <label>Start Date:</label>
                    <input type="date" name="start_date" min="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="option">
                    <label>End Date:</label>
                    <input type="date" name="end_date" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                </div>

                <button type="submit" class="add-cart">Add to Cart</button>
                <a href="<?= htmlspecialchars($baseUrl) ?>/collection.php" class="cancel">Cancel</a>
            </form>
        </div>
    </div>

    <div class="reviews-box">
        <h3>Customer Reviews</h3>
        <div class="review">
            <p style="color:#777; font-style:italic;">No reviews yet for this product.</p>
        </div>
    </div>
</div>


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