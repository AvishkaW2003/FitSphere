<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';
require_once __DIR__ . '/../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$db = new Database();
$conn = $db->connect();


// Total Rentals = all bookings
$totalRentals = $conn->query("SELECT COUNT(*) FROM bookings")->fetchColumn();

// Active Rentals
$activeRentals = $conn->query("SELECT COUNT(*) FROM bookings WHERE status = 'Active'")->fetchColumn();

// Pending Pickup
$pendingPickup = $conn->query("SELECT COUNT(*) FROM bookings WHERE status = 'Upcoming'")->fetchColumn();

// Total Users
$totalUsers = $conn->query("SELECT COUNT(*) FROM customers")->fetchColumn();

// Monthly Revenue
$revenueData = $conn->query("
    SELECT 
        MONTH(created_at) AS month,
        SUM(rent_fee + late_fee) AS total
    FROM payments
    GROUP BY MONTH(created_at)
")->fetchAll(PDO::FETCH_ASSOC);

$months = array_fill(1, 12, 0);
foreach ($revenueData as $row) {
    $months[(int)$row['month']] = (float)$row['total'];
}

// Most Sold Categories
$categoryData = $conn->query("
    SELECT p.category, COUNT(*) AS count
    FROM bookings b
    JOIN products p ON b.product_id = p.product_id
    GROUP BY p.category
")->fetchAll(PDO::FETCH_ASSOC);

// Resent Bookings
$recentBookings = $conn->query("
    SELECT 
        b.booking_id,
        p.title,
        b.start_date,
        b.end_date,
        b.status
    FROM bookings b
    JOIN products p ON b.product_id = p.product_id
    ORDER BY b.booking_id DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>

<body>
  


<!-- Dashboard Hero Section -->
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

<!-- Monthly Revenue Chart -->
<div class="container my-4 p-4 border border-primary rounded bg-white shadow-sm">
    <div class="d-flex justify-content-between">
        <h5 class="fw-semibold">Monthly Revenue</h5>
        <span class="fw-bold"><?= date("Y") ?></span>
    </div>
    <canvas id="myChart1" height="75"></canvas>
</div>

<!-- Bottom Section -->
<div class="container my-4">
    <div class="row g-4">

        <!-- Pie chart -->
        <div class="col-md-6">
            <div class="p-4 bg-white shadow-sm rounded border border-light d-flex justify-content-between align-items-center chart-section">

                <div class="legend-box">
                    <h4 class="fw-semibold mb-3">Most Sold Categories</h4>

                    <?php foreach ($categoryData as $item): ?>
                        <div class="category-box">
                            <div class="color-dot"></div>
                            <span><?= htmlspecialchars($item['category']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="chart-wrapper">
                    <canvas id="myChart2"></canvas>
                </div>

            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="col-md-6">
            <div class="p-4 bg-white shadow-sm rounded border border-light">
                <h6 class="fw-semibold mb-3">Recent Bookings</h6>

                <table class="table table-sm align-middle text-center">
                    <thead class="table-warning">
                        <tr>
                            <th>Booking ID</th>
                            <th>Suit</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($recentBookings as $b): ?>
                        <tr>
                            <td><?= $b['booking_id'] ?></td>
                            <td><?= htmlspecialchars($b['title']) ?></td>
                            <td><?= $b['start_date'] ?></td>
                            <td><?= $b['end_date'] ?></td>
                            <td><?= $b['status'] ?></td>
                        </tr>
                        <?php endforeach; ?>
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


<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="../../assets/js/main.js?v=<?time()  ?>"></script>

</body>
</html>

<?php include '../../includes/footerAdmin.php'; ?>
