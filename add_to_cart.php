<?php
session_start();
// Include necessary files and set up environment
require_once __DIR__ . '/includes/db.php'; 
require_once __DIR__ . '/includes/functions.php';

// Define $baseUrl (Ensure this matches the definition in your header/config)
$baseUrl = "/FitSphere"; 

use FitSphere\Database\Database;

// --- 1. Retrieve Data from the POST request (from rentNow.php) ---

// The unique identifier for a specific size/stock item
$productId = $_POST['product_id'] ?? null;
$styleId = $_POST['style_id'] ?? null; // Used for redirecting back if there's an error
$qty = $_POST['qty'] ?? 1;
$startDate = $_POST['start_date'] ?? null;
$endDate = $_POST['end_date'] ?? null;

// --- 2. Input Validation ---

if (!$productId || !is_numeric($productId) || !$startDate || !$endDate || $qty < 1) {
    $errorRedirect = $styleId 
        ? $baseUrl . "/rentNow.php?style_id=" . htmlspecialchars($styleId) . "&error=missing_selection"
        : $baseUrl . "/collection.php?error=invalid_cart_input";
        
    header("Location: " . $errorRedirect);
    exit();
}

// Basic date validation 
if (strtotime($endDate) < strtotime($startDate)) {
    header("Location: " . $baseUrl . "/rentNow.php?style_id=" . htmlspecialchars($styleId) . "&error=invalid_dates");
    exit();
}


// --- 3. FIX: Fetch Product Details using JOIN (product_styles and product_inventory) ---

try {
    $database = new Database();
    $conn = $database->connect();
    
    // This query joins the product styles (for title, price, image) with the inventory (for size, stock)
    $sql = "
        SELECT
            pi.product_id,
            ps.title,
            ps.price_per_day AS price, 
            ps.image,
            pi.size,
            pi.stock
        FROM
            product_inventory pi
        JOIN
            product_styles ps ON pi.style_id = ps.style_id
        WHERE
            pi.product_id = :product_id
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
    $stmt->execute();
    $dbProduct = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dbProduct) {
        header("Location: " . $baseUrl . "/collection.php?error=product_not_found");
        exit();
    }
    
    // Check stock availability
    if ($dbProduct['stock'] < $qty) {
         header("Location: " . $baseUrl . "/rentNow.php?style_id=" . htmlspecialchars($styleId) . "&error=out_of_stock");
         exit();
    }

} catch (PDOException $e) {
    // Log the actual error for debugging and die gracefully
    error_log("Database Error in add_to_cart: " . $e->getMessage());
    die("A database error occurred while adding to cart. Please try again.");
}

// --- 4. Store Item in Session Cart ---

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Use the unique product_id as the key for the cart item
$key = $productId; 

if (isset($_SESSION['cart'][$key])) {
    // Update quantity and rental period
    $_SESSION['cart'][$key]['qty'] += $qty;
    $_SESSION['cart'][$key]['start_date'] = $startDate;
    $_SESSION['cart'][$key]['end_date'] = $endDate;
} else {
    // Add new item to the cart
    $_SESSION['cart'][$key] = [
        'product_id' => $dbProduct['product_id'],
        'name' => $dbProduct['title'],
        // 'price' now holds the price_per_day from the DB
        'price' => $dbProduct['price'], 
        'qty' => $qty,
        'image' => $dbProduct['image'],
        'size' => $dbProduct['size'],
        'start_date' => $startDate,
        'end_date' => $endDate
    ];
}

// --- 5. Redirect to Cart Page ---
header("Location: " . $baseUrl . "/cart.php");
exit();