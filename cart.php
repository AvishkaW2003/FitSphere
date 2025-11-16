<?php
include 'includes/header.php';

$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart | FitSphere</title>
    <link rel="stylesheet" href="assets/css/cart.css">
</head>
<body>

<div class="cart-container">
    <h2>Your Cart</h2>

    <?php if (empty($cart)): ?>
        <div class="empty-cart">
            <p>Your cart is empty.</p>
            <a href="collection.php" class="btn">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="cart-items">
            <?php $subtotal = 0; ?>
            <?php foreach ($cart as $key => $item): ?>
                <div class="cart-item">
                    <img src="assets/images/suits/<?php echo $item['image']; ?>" alt="">

                    <div class="item-info">
                        <h3><?php echo $item['name']; ?> (<?php echo $item['size']; ?>)</h3>
                        <p>Price: Rs <?php echo $item['price']; ?></p>
                        <p>Dates: <?php echo $item['start_date']; ?> to <?php echo $item['end_date']; ?></p>

                        <div class="qty-box">
                            <a href="update_qty.php?key=<?php echo $key; ?>&action=minus" class="qty-btn">−</a>
                            <span class="qty"><?php echo $item['qty']; ?></span>
                            <a href="update_qty.php?key=<?php echo $key; ?>&action=plus" class="qty-btn">+</a>
                        </div>
                    </div>

                    <div class="item-total">
                        <p>Rs <?php echo $item['price'] * $item['qty']; ?></p>
                        <a href="remove_item.php?key=<?php echo $key; ?>" class="remove">Remove</a>
                    </div>
                </div>
                <?php $subtotal += $item['price'] * $item['qty']; ?>
            <?php endforeach; ?>
        </div>

        <div class="cart-summary">
            <h3>Order Summary</h3>
            <p>Subtotal: Rs <?php echo $subtotal; ?></p>
            <p>Delivery: Rs 500</p>
            <h2>Total: Rs <?php echo $subtotal + 500; ?></h2>

            <a href="checkout.php" class="btn checkout-btn">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
