<?php
require_once __DIR__ . '/../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('user');

$conn = new mysqli('localhost', 'root', '', 'fitsphere');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $CustomerID = $_SESSION['user_id'] ?? NULL;
    $ProductID  = $_POST['product_id'] ?? NULL;
    $StartDate = $_POST['start_date'] ?? '';
    $EndDate = $_POST['end_date'] ?? '';
    $TotalPrice = $_POST['total_price'] ?? '';
    $Deposit = $_POST['deposit'] ?? '';
    $Status  = $_POST['status'] ?? 'pending';
    $CreatedAt = date('Y-m-d H:i:s'); // Fixed: was using total_price instead of current timestamp

    // Validate required fields
    if (empty($CustomerID) || empty($ProductID) || empty($StartDate) || empty($EndDate) || empty($Deposit)) {
        $error = "Please fill in all required fields.";
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO bookings (customer_id, product_id, start_date, end_date, total_price, deposit, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissddss", $CustomerID, $ProductID, $StartDate, $EndDate, $TotalPrice, $Deposit, $Status, $CreatedAt);
        
        if ($stmt->execute()) {
            $message = "Booking created successfully!";
        } else {
            $error = "Error creating booking: " . $stmt->error;
        }
        
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f3f2f2ff;
            margin: 0;
            padding: 20px;
        }

        .form-group {
            width: 100%;
            max-width: 600px;
            display: flex;
            justify-content: center;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 10px 8px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="form-group">
    <form class="row g-3 w-100" action="booking_form.php" method="POST">
        <h2>Booking Details</h2>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($_GET['product_id'] ?? ''); ?>">

        <div class="col-md-12">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" id="start_date" required>
        </div>

        <div class="col-md-12">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" id="end_date" required>
        </div>

        <div class="col-md-12">
            <label for="total_price" class="form-label">Total Price</label>
            <input type="number" step="0.01" name="total_price" class="form-control" id="total_price">
        </div>

        <div class="col-12">
            <label for="deposit" class="form-label">Deposit</label>
            <input type="number" step="0.01" name="deposit" class="form-control" id="deposit" required>
        </div>

        <div class="col-12">
            <label for="status" class="form-label">Status</label>
            <input type="text" name="status" class="form-control" id="status" value="pending" readonly>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Process Booking</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </form>
</div>

</body>
</html>