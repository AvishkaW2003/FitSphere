<?php 
// Includes and Middleware (Adjust paths as needed for your specific setup)
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';
require_once __DIR__ . '/../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$db = new Database();
$conn = $db->connect();

// --- 1. Fetch Dashboard Data ---
try {
    // Total Rentals (all bookings)
    $totalRentals = $conn->query("SELECT COUNT(*) FROM bookings")->fetchColumn();

    // Active Rentals
    $activeRentals = $conn->query("SELECT COUNT(*) FROM bookings WHERE status = 'Active'")->fetchColumn();

    // Pending Pickup
    $pendingPickup = $conn->query("SELECT COUNT(*) FROM bookings WHERE status = 'Upcoming'")->fetchColumn();

    // Total Users (FIXED: uses 'users' table)
    $totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();

    // Monthly Revenue (FIXED: calculates total from specific payments columns)
    $revenueData = $conn->query("
        SELECT 
            MONTH(p.created_at) AS month, 
            SUM(p.rent_fee + p.deposit - p.refund_amount) AS total
        FROM payments p
        WHERE YEAR(p.created_at) = YEAR(CURDATE()) 
        GROUP BY MONTH(p.created_at)
    ")->fetchAll(PDO::FETCH_ASSOC);

    $months = array_fill(1, 12, 0);
    foreach ($revenueData as $row) {
        $months[(int)$row['month']] = (float)$row['total'];
    }

    // Most Sold Categories (FIXED: joins to product_styles)
    $categoryData = $conn->query("
        SELECT ps.category, COUNT(b.booking_id) AS count
        FROM bookings b
        JOIN product_inventory pi ON b.product_id = pi.product_id
        JOIN product_styles ps ON pi.style_id = ps.style_id
        GROUP BY ps.category
        ORDER BY count DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    // Assign colors for the chart legend
    $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];
    $colorIndex = 0;

    // Recent Bookings (FIXED: joins to product_styles)
    $recentBookings = $conn->query("
        SELECT 
            b.booking_id,
            ps.title,
            b.start_date,
            b.end_date,
            b.status
        FROM bookings b
        JOIN product_inventory pi ON b.product_id = pi.product_id
        JOIN product_styles ps ON pi.style_id = ps.style_id
        ORDER BY b.booking_id DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Basic error handling for database failures
    error_log("Admin Dashboard DB Error: " . $e->getMessage());
    $totalRentals = $activeRentals = $pendingPickup = $totalUsers = 'N/A';
    $months = array_fill(1, 12, 0);
    $categoryData = $recentBookings = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/dashboard.css?v=<?time() ?>">
</head>

<body>
  
<div class="dashboard-container">
    <div class="overlay">
        <h1 class="welcome-text text-center text-light">
            Welcome <span class="text-warning fw-bold">Admin!</span>
        </h1>

        <div class="container mt-4">
            <div class="row justify-content-center">

                <div class="col-md-2 col-sm-4 mb-3">
                    <div class="card stat-card green text-center p-3">
                        <h5>Total Rentals</h5>
                        <h3><?= $totalRentals ?></h3>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card stat-card blue text-center p-3">
                        <h5>Active Rentals</h5>
                        <h3><?= $activeRentals ?></h3>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card stat-card red text-center p-3">
                        <h5>Pending Pickup</h5>
                        <h3><?= $pendingPickup ?></h3>
                    </div>
                </div>

                <div class="col-md-2 col-sm-4 mb-3">
                    <div class="card stat-card pink text-center p-3">
                        <h5>Total Users</h5>
                        <h3><?= $totalUsers ?></h3>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<div class="container my-5">
    <div class="content-card"> 
        <div class="d-flex justify-content-between">
            <h5 class="fw-semibold">Monthly Revenue</h5>
            <span class="fw-bold"><?= date("Y") ?></span>
        </div>
        <canvas id="myChart1" height="75"></canvas>
    </div>
</div>

<div class="container my-5">
    <div class="row g-4">

        <div class="col-md-6">
            <div class="content-card chart-section">

                <div class="legend-box">
                    <h4 class="fw-semibold mb-4">Most Sold Categories</h4>

                    <?php foreach ($categoryData as $item): ?>
                        <?php $color = $colors[$colorIndex++ % count($colors)]; ?>
                        <div class="category-box">
                            <div class="color-dot" style="background-color: <?= $color ?>;"></div> 
                            <span><?= htmlspecialchars($item['category']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="chart-wrapper">
                    <canvas id="myChart2"></canvas>
                </div>

            </div>
        </div>

        <div class="col-md-6">
            <div class="content-card">
                <h6 class="fw-semibold mb-3">Recent Bookings</h6>

                <table class="table table-sm align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Suit</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($recentBookings)): ?>
                            <tr><td colspan="5">No recent bookings found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($recentBookings as $b): ?>
                                <tr>
                                    <td><?= $b['booking_id'] ?></td>
                                    <td><?= htmlspecialchars($b['title']) ?></td>
                                    <td><?= $b['start_date'] ?></td>
                                    <td><?= $b['end_date'] ?></td>
                                    <td><?= $b['status'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <a href="/FitSphere/src/admin/bookings_Management/manage_bookings.php" class="text-end text-muted small mb-0 d-block">
                    See more →
                </a>

            </div>
        </div>

    </div>
</div>

<script>
    window.dashboardData = {
        revenue: <?= json_encode(array_values($months)) ?>,
        categories: <?= json_encode(array_column($categoryData, 'count')) ?>,
        labels: <?= json_encode(array_column($categoryData, 'category')) ?>
    };
</script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="../../assets/js/main.js?v=<?time() ?>"></script>

</body>
</html>

<?php include '../../includes/footerAdmin.php'; ?>