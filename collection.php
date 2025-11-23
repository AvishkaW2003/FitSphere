<?php
// Note: We assume that the 'includes/header.php' file handles 'session_start()' 
// and defines the $baseUrl variable, as well as the database connection class.
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Define $baseUrl if it's not guaranteed to be set by the included files (good practice)
$baseUrl = "/FitSphere"; 

// Include the header which outputs the navigation and the start of the HTML document
include 'includes/header.php'; 

use FitSphere\Database\Database;

// --- 1. Database Setup and Data Fetching ---

try {
    $database = new Database();
    $conn = $database->connect();
    
    // Fetch all product styles
    $sql = "SELECT style_id, title, category, price_per_day, image FROM product_styles ORDER BY category, title";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $allStyles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group styles by category for display
    $stylesByCategory = [];
    foreach ($allStyles as $style) {
        $stylesByCategory[$style['category']][] = $style;
    }

} catch (PDOException $e) {
    // Display a user-friendly error if the database connection fails
    die("Database Error: Could not load products. Please check your database connection. Error: " . $e->getMessage());
}

$categoryMaps = [
    // The key is the DB category column value. The 'link_param' is the value passed in the URL.
    'Business' => ['title' => 'Business Suits', 'link_param' => 'Business'],
    'Dinner'   => ['title' => 'Dinner Suits',   'link_param' => 'Dinner'],
    'Wedding'  => ['title' => 'Wedding Suits',  'link_param' => 'Wedding'],
    'Nilame'   => ['title' => 'Nilame Suits',   'link_param' => 'Nilame'],
    'Indian'   => ['title' => 'Indian Suits',   'link_param' => 'Indian'],
    'Blazer'   => ['title' => 'Blazers',        'link_param' => 'Blazer'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection | FitSphere</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl) ?>/assets/css/collection.css?v=<?= time() ?>">
</head>
<body>

<?php foreach ($stylesByCategory as $categoryName => $styles): 
    // 👇 FIX: The fallback array now includes 'link_param' to prevent PHP Notice
    $map = $categoryMaps[$categoryName] ?? ['title' => $categoryName, 'link_param' => $categoryName]; 
    $anchorId = str_replace(' ', '_', $map['title']);
?>

<div class="collection" id="<?= htmlspecialchars($anchorId) ?>">
    <h2><?= htmlspecialchars($map['title']) ?></h2>
    <div class="collection01">
        <?php 
        // Only show up to 4 items in the main collection page for a preview
        $count = 0;
        foreach ($styles as $style): 
            if ($count >= 4) break; 
            $count++;
        ?>
            <div class="col">
                <img src="<?= htmlspecialchars($baseUrl) ?>/assets/images/suits/<?= htmlspecialchars($style['image']) ?>" 
                     alt="<?= htmlspecialchars($style['title']) ?>">
                
                <h3><?= htmlspecialchars($style['title']) ?></h3>
                <p>Rs<?= number_format($style['price_per_day'], 2) ?></p>
                
                <a href="<?= htmlspecialchars($baseUrl) ?>/rentNow.php?style_id=<?= $style['style_id'] ?>">Rent Now</a>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="explore">
        <a href="<?= htmlspecialchars($baseUrl) ?>/seeMore.php?category=<?= htmlspecialchars($map['link_param']) ?>">
            See more ➜
        </a>
    </div>
</div>

<?php endforeach; ?>

<?php include 'includes/footer.php'; ?>
</body>
</html>