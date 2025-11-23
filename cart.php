<?php
// Include necessary files and set up environment
require_once __DIR__ . '/includes/db.php'; 
require_once __DIR__ . '/includes/functions.php';

// Define $baseUrl (Ensure this is correct for your environment)
$baseUrl = "/FitSphere"; 

include 'includes/header.php';

// Assuming session has been started in header.php
$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart | FitSphere</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl) ?>/assets/css/cart.css">
</head>
<body>

<div class="cart-container">
    <h2>Your Cart</h2>

    <?php if (empty($cart)): ?>
        <div class="empty-cart">
            <p>Your cart is empty.</p>
            <a href="<?= htmlspecialchars($baseUrl) ?>/collection.php" class="btn">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="cart-items">
            <?php $subtotal = 0; ?>
            
            <?php foreach ($cart as $key => $item): 
                // --- CALCULATE RENTAL DAYS AND TOTAL PRICE ---
                try {
                    $start_date_obj = new DateTime($item['start_date']);
                    $end_date_obj = new DateTime($item['end_date']);

                    // Calculate inclusive days (Dec 1 to Dec 1 is 1 day). 
                    // Set rental days to 0 if dates are invalid (e.g., end < start)
                    if ($start_date_obj <= $end_date_obj) {
                        $interval = $start_date_obj->diff($end_date_obj);
                        $rental_days = $interval->days + 1; 
                    } else {
                        $rental_days = 0; // Invalid date range
                    }

                } catch (Exception $e) {
                    // Fallback if date strings are invalid
                    $rental_days = 0; 
                }

                $price_per_day = $item['price'] ?? 0; // Assuming $item['price'] is now PRICE PER DAY
                $qty = $item['qty'] ?? 1;

                // Item Total = Price per Day * Rental Days * Quantity
                $item_total = $price_per_day * $rental_days * $qty;
                $subtotal += $item_total;
            ?>
                <div class="cart-item">
                    <img src="<?= htmlspecialchars($baseUrl) ?>/assets/images/suits/<?= htmlspecialchars($item['image']); ?>" 
                         alt="<?= htmlspecialchars($item['name']); ?>">

                    <div class="item-info">
                        <h3><?= htmlspecialchars($item['name']); ?> (<?= htmlspecialchars($item['size']); ?>)</h3>
                        
                        <p>Price: Rs <?= number_format($price_per_day, 2); ?> per day</p>
                        <p>Duration: <?= $rental_days ?> Day(s)</p>
                        <p>Dates: <?= htmlspecialchars($item['start_date']); ?> to <?= htmlspecialchars($item['end_date']); ?></p>

                        <div class="qty-box">
                            <a href="<?= htmlspecialchars($baseUrl) ?>/update_qty.php?key=<?php echo $key; ?>&action=minus" class="qty-btn">−</a>
                            <span class="qty"><?php echo htmlspecialchars($item['qty']); ?></span>
                            <a href="<?= htmlspecialchars($baseUrl) ?>/update_qty.php?key=<?php echo $key; ?>&action=plus" class="qty-btn">+</a>
                        </div>
                    </div>

                    <div class="item-total">
                        <p>Rs <?= number_format($item_total, 2); ?></p>
                        <a href="<?= htmlspecialchars($baseUrl) ?>/remove_item.php?key=<?php echo $key; ?>" class="remove">Remove</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-summary">
            <h3>Order Summary</h3>
            <p>Subtotal: Rs <?= number_format($subtotal, 2); ?></p>
            <p>Delivery: Rs 500.00</p>
            <h2>Total: Rs <?= number_format($subtotal + 500, 2); ?></h2>

            <a href="<?= htmlspecialchars($baseUrl) ?>/checkout.php" class="btn checkout-btn">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>