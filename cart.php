<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cart | FitSphere</title>

    <link rel="stylesheet" href="assets/css/cart.css">
</head>
<body>
    <?php
include 'includes/header.php';

// Example: assuming your cart data is stored in $_SESSION
$cart = $_SESSION['cart'] ?? [];

?>

<div class="cart-container">
    <h2>Your Cart</h2>

    <?php if(empty($cart)): ?>
        <div class="empty-cart">
            <p>Your cart is empty.</p>
            <a href="collection.php" class="btn">Continue Shopping</a>
        </div>

    <?php else: ?>
        <div class="cart-items">

            <?php foreach($cart as $id => $item): ?>
                <div class="cart-item">
                    <img src="assets/images/<?php echo $item['image']; ?>" alt="">

                    <div class="item-info">
                        <h3><?php echo $item['name']; ?></h3>
                        <p>Price: Rs <?php echo $item['price']; ?></p>

                        <div class="qty-box">
                            <button class="qty-btn minus" data-id="<?php echo $id; ?>">−</button>
                            <span class="qty"><?php echo $item['qty']; ?></span>
                            <button class="qty-btn plus" data-id="<?php echo $id; ?>">+</button>
                        </div>
                    </div>

                    <div class="item-total">
                        <p>Rs <?php echo $item['price'] * $item['qty']; ?></p>
                        <a href="remove_item.php?id=<?php echo $id; ?>" class="remove">Remove</a>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <div class="cart-summary">
            <h3>Order Summary</h3>

            <?php
            $subtotal = 0;
            foreach($cart as $item){
                $subtotal += $item['price'] * $item['qty'];
            }
            ?>

            <p>Subtotal: Rs <?php echo $subtotal; ?></p>
            <p>Delivery: Rs 500</p>

            <h2>Total: Rs <?php echo $subtotal + 500; ?></h2>

            <a href="checkout.php" class="btn checkout-btn">Proceed to Checkout</a>
        </div>

    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>

<script>
document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        let id = this.dataset.id;
        let action = this.classList.contains('plus') ? 'plus' : 'minus';
        window.location.href = `update_qty.php?id=${id}&action=${action}`;
    });
});
</script>


</body>
</html>