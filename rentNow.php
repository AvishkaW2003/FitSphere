<?php
include 'includes/header.php';
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
            <img src="assets/images/suits/b01.webp" alt="Classic Charcoal Executive Suit">
        </div>

        <div class="rent-details">
            <h2>Classic Charcoal Executive Suit</h2>
            <p class="description">
                Step into sophistication with this timeless charcoal three-piece suit. 
                Tailored for comfort and elegance, perfect for corporate events, weddings, and formal gatherings.
            </p>

            <form action="add_to_cart.php" method="GET">
                <input type="hidden" name="id" value="101">
                <input type="hidden" name="name" value="Classic Charcoal Executive Suit">
                <input type="hidden" name="price" value="4500">
                <input type="hidden" name="image" value="b01.webp">

                <div class="option">
                    <label>Quantity:</label>
                    <input type="number" name="qty" value="1" min="1">
                </div>

                <div class="option">
                    <label>Size:</label>
                    <select name="size">
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
            </form>

            <a href="collection.php" class="cancel">Cancel</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
