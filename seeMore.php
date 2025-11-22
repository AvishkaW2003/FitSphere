<?php
// Include necessary files and set up environment
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Define $baseUrl if not set in includes
$baseUrl = "/FitSphere"; 

// Use the database class
use FitSphere\Database\Database;

// --- 1. Get Category from URL ---
// Sanitize and validate the category parameter
$category = $_GET['category'] ?? null;

if (!$category) {
    // Redirect or display an error if no category is specified
    die("Error: No product category selected.");
}

// Map the DB category name to a user-friendly display title
$displayTitle = [
    'Business' => 'BUSINESS SUITS',
    'Dinner'   => 'DINNER SUITS',
    'Wedding'  => 'WEDDING SUITS',
    'Nilame'   => 'NILAME SUITS',
    'Indian'   => 'INDIAN SUITS',
    'Blazer'   => 'BLAZERS',
][$category] ?? strtoupper($category); // Fallback to uppercased category

// --- 2. Database Fetching ---
$products = [];
try {
    $database = new Database();
    $conn = $database->connect();
    
    // Select ALL styles for the requested category
    // Use prepared statements for security to prevent SQL injection
    $sql = "SELECT style_id, title, category, price_per_day, image FROM product_styles WHERE category = :category ORDER BY title";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':category', $category);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database Error: Could not load products for category: " . htmlspecialchars($category));
}

// Include header *after* fetching data so the title can be dynamic if needed
include 'includes/header.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($displayTitle) ?> | FitSphere</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl) ?>/assets/css/seeMore.css">
</head>
<body>

    <div id="heading">
        <h1><?= htmlspecialchars($displayTitle) ?></h1>
    </div>
    
    <?php if (empty($products)): ?>
        <p class="text-center mt-5">No products found in the <?= htmlspecialchars($category) ?> category.</p>
    <?php else: ?>

        <div class="row row-cols-1 row-cols-md-3 g-4" id="container">

        <?php foreach ($products as $product): ?>
            <div class="card" style="width: 18rem;">
                <img src="<?= htmlspecialchars($baseUrl) ?>/assets/images/suits/<?= htmlspecialchars($product['image']) ?>" 
                    class="card-img-top" id="cardImage" alt="<?= htmlspecialchars($product['title']) ?>">
                
                <div class="card-body">
                    <h3 class="card-title"><?= htmlspecialchars($product['title']) ?></h3>
                    <p class="card-text">Rs<?= number_format($product['price_per_day'], 2) ?></p>
                    
                    <a class="btn" href="<?= htmlspecialchars($baseUrl) ?>/rentNow.php?style_id=<?= $product['style_id'] ?>">Rent Now</a>
                </div>
            </div>
        <?php endforeach; ?>

        </div>
    <?php endif; ?>
 
<?php include 'includes/footer.php'; ?>
</body>
</html>