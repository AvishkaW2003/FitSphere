<?php
include_once 'config.php';
include_once 'Booking.php';

$database = new Database();
$db = $database->getConnection();
$booking = new Booking($db);
$stmt = $booking->read();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitSphere - All Bookings</title>
    <style>
        /* Add your existing CSS styles here */
        .bookings-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .bookings-table th,
        .bookings-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .bookings-table th {
            background-color: #1a1a1a;
            color: white;
            font-weight: 600;
        }
        
        .bookings-table tr:hover {
            background-color: #f9f9f9;
        }
        
        .status-completed { color: #28a745; font-weight: 600; }
        .status-processing { color: #ffc107; font-weight: 600; }
        .status-returned { color: #17a2b8; font-weight: 600; }
        .status-cancelled { color: #dc3545; font-weight: 600; }
        
        .view-all-btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: #1a1a1a;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo-section">
            <div class="logo">FS</div>
            <div class="brand-name">FitSphere</div>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="bookings.php" style="color: #d4af37;">All Bookings</a></li>
            <li><a href="#">Customers</a></li>
            <li><a href="#">Reports</a></li>
        </ul>
        <div class="user-icon"></div>
    </nav>

    <div class="container">
        <h1>All Bookings</h1>
        <a href="index.php" class="view-all-btn">← Add New Booking</a>
        
        <table class="bookings-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Suit</th>
                    <th>Period</th>
                    <th>Total</th>
                    <th>Deposit</th>
                    <th>Refund</th>
                    <th>Status</th>
                    <th>Return Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['suit']); ?></td>
                    <td><?php echo htmlspecialchars($row['period']); ?></td>
                    <td>$<?php echo number_format($row['total'], 2); ?></td>
                    <td>$<?php echo number_format($row['deposite'], 2); ?></td>
                    <td>$<?php echo number_format($row['refund_amount'], 2); ?></td>
                    <td class="status-<?php echo strtolower($row['status']); ?>">
                        <?php echo $row['status']; ?>
                    </td>
                    <td><?php echo $row['returned_date']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>