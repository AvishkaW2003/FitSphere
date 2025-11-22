<?php
// CRITICAL FIX: Include the database class file
require_once '../../includes/db.php';
require_once __DIR__ . '/../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('user');

// Use the namespace defined in db.php for the Database class
use FitSphere\Database\Database;

// --- START: Data Fetching Logic (Updated for PDO) ---

// 1. Instantiate the database object and get the PDO connection
$database = new Database();
// $conn will now be the PDO object
$conn = $database->connect();

// Retrieve the logged-in user's ID
// Assuming AuthMiddleware sets $_SESSION['user']['id']
$customer_id = $_SESSION['user']['user_id'];
$bookings = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel_booking') {

    $booking_id = $_POST['booking_id'];
    $new_status = 'Cancelled';

    // Use PDO syntax since your previous code showed PDO
    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE booking_id = ?");
    $result = $stmt->execute([$new_status, $booking_id]);

    // Clear buffer just in case
    ob_clean();

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Update failed']);
    }

    exit; // <--- CRITICAL: This stops the Header from loading below
}

// 3. NOW LOAD THE HEADER (Only happens if we didn't exit above)
include '../../includes/header.php';

// SQL Query to fetch all necessary booking and product details
$sql = "
    SELECT 
        b.booking_id, 
        b.start_date, 
        b.end_date, 
        b.total_price, 
        b.deposit, 
        b.status, 
        b.late_fee, 
        b.refund,
        ps.title AS product_name,
        ps.image AS product_image,
        pi.size
    FROM 
        bookings b
    JOIN 
        product_inventory pi ON b.product_id = pi.product_id
    JOIN
        product_styles ps ON pi.style_id = ps.style_id
    WHERE 
        b.customer_id = ?
    ORDER BY 
        b.created_at DESC
";

// 2. PDO Preparation and Execution
$stmt = $conn->prepare($sql);

// Bind the customer_id (PDO uses 1-based index or named parameters)
$stmt->bindParam(1, $customer_id, PDO::PARAM_INT);

$stmt->execute();

// Fetch all results (PDO method - replaces get_result() and fetch_assoc() loop)
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close statement (optional, but good practice for PDO)
$stmt = null;

// --- END: Data Fetching Logic ---
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | FitSphere</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <style>
        /* Your existing CSS styles */
        .card {
            margin: auto;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            transition: 0.3s ease;
            margin-top: 30px;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
        }

        h1 {
            text-align: center;
            margin-top: 50px;
            margin-bottom: 50px;
        }


        .titleAndDate {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .titleAndDate>p {
            opacity: 60%;
        }

        .btn {
            float: right;
            position: absolute;
            bottom: 15%;
            right: 5%;
        }

        .imageAndDetails {
            display: inline-flex;

        }

        #clothImage {
            width: 80px;
            height: fit-content;
        }

        li {
            list-style-type: none;
        }

        .list-group {
            border: none;
        }

        .list-group-horizontal {
            border: none;
        }

        .list-group-item {
            border: none;
            padding-top: 4px;
            padding-bottom: 4px;
            padding-left: 10px;
        }

        .list-group li:first-child {
            padding-top: 0px;
        }

        .list-group li:nth-child(2) {
            padding-top: 0px;
        }

        .nav-link {
            color: #6e6e59;
            text-decoration: none;
            font-size: 18px;
            font-weight: 500;
        }

        .nav-link:hover {
            color: #FFC107;
        }

        #card2 {
            background-color: rgb(232, 251, 236);
        }

        .color1 {
            background-color: rgb(232, 251, 236);
        }

        #card3 {
            background-color: rgb(250, 226, 226);
        }

        .color2 {
            background-color: rgb(250, 226, 226);
        }


        @media (max-width: 770px) {

            .card {
                width: 70% !important;
            }

            .imageAndDetails {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }

            #clothImage {
                width: 120px;
            }

            .details {
                width: 100%;
                text-align: center;
            }

            .btn {
                position: static;
                float: none;
                display: block;
                margin: 20px auto 0 auto;
            }

            .titleAndDate {
                flex-direction: column;
                text-align: center;
                gap: 5px;
            }
        }


        @media (max-width: 576px) {

            .nav-link {
                font-size: 16px;
            }

            .card-body {
                padding: 15px;
            }

            #clothImage {
                width: 100px;
            }

            .titleAndDate h5 {
                font-size: 16px;
            }

            .titleAndDate p {
                font-size: 13px;
            }

            .list-group-item {
                font-size: 14px;
                padding: 2px 5px;
            }

            .details {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body style="margin-top: 8rem;">

    <h1>My Bookings</h1>



    <div>
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class="nav-link " href="?status=All">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="?status=Active">Active</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="?status=Returned">Returned</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="?status=Cancelled">Cancelled</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="?status=Overdue">Overdue</a>
            </li>
        </ul>
    </div>

    <?php if (empty($bookings)): ?>
        <p class="text-center mt-5">You have no current or past bookings.</p>
    <?php else: ?>
        <?php foreach ($bookings as $booking):
            $card_id = '';
            $item_class = '';
            $button_html = '';
            $extra_detail_html = '';
            $bookingId = $booking['booking_id'];

            // Calculate Rent Fee (Total Price in DB is often Rent + Deposit)
            $rent_fee = $booking['total_price'] - $booking['deposit'];

            // Determine styling and actions based on status
            switch ($booking['status']) {
                case 'Returned':
                    $card_id = 'id="card2"';
                    $item_class = 'color1';
                    $extra_detail_html = '
                        <ul class="list-group list-group-horizontal">
              <li class="list-group-item ' . $item_class . '">Deposit Refunded :</li>
              <li class="list-group-item ' . $item_class . '">Rs.' . number_format($booking['deposit'], 2) . '</li>
            </ul>';
                    break;
                case 'Overdue':
                case 'Cancelled':
                    $card_id = 'id="card3"';
                    $item_class = 'color2';
                    if ($booking['late_fee'] > 0) {
                        $extra_detail_html = '
                            <ul class="list-group list-group-horizontal">
                  <li class="list-group-item ' . $item_class . '">Late Fee :</li>
                  <li class="list-group-item ' . $item_class . '">Rs.' . number_format($booking['late_fee'], 2) . '</li>
                </ul>';
                    }
                    break;
                case 'Active':
                case 'Upcoming':
                default:
                    // Default style (no special background color)
                    $button_html = '<button type="button" class="btn btn-danger" onclick="cancelBooking(' . $bookingId . ')">Cancel</button>';
                    $item_class = '';
                    break;
            }
        ?>
            <div class="card w-75 mb-3" <?= $card_id ?>>
                <div class="card-body">
                    <div class="titleAndDate">
                        <h5 class="card-title"><?= htmlspecialchars($booking['product_name']) ?> (Size: <?= htmlspecialchars($booking['size']) ?>)</h5>
                        <p><?= htmlspecialchars($booking['start_date']) ?> - <?= htmlspecialchars($booking['end_date']) ?></p>
                    </div>

                    <div class="imageAndDetails">

                        <img src="../../assets/images/suits/<?= htmlspecialchars($booking['product_image']) ?>" class="rounded float-start" id="clothImage" alt="<?= htmlspecialchars($booking['product_name']) ?>">

                        <div class="details">
                            <ul class="list-group list-group-horizontal">
                                <li class="list-group-item <?= $item_class ?>">Rent Fee :</li>
                                <li class="list-group-item <?= $item_class ?>">Rs.<?= number_format($rent_fee, 2) ?></li>
                            </ul>
                            <ul class="list-group list-group-horizontal">
                                <li class="list-group-item <?= $item_class ?>">Deposit :</li>
                                <li class="list-group-item <?= $item_class ?>">Rs.<?= number_format($booking['deposit'], 2) ?></li>
                            </ul>
                            <ul class="list-group list-group-horizontal">
                                <li class="list-group-item <?= $item_class ?>">Total Price :</li>
                                <li class="list-group-item <?= $item_class ?>">Rs.<?= number_format($booking['total_price'], 2) ?></li>
                            </ul>

                            <?= $extra_detail_html ?>

                            <ul class="list-group list-group-horizontal">
                                <li class="list-group-item <?= $item_class ?>">Status :</li>
                                <li class="list-group-item <?= $item_class ?>"><?= htmlspecialchars($booking['status'] ?: 'UNKNOWN') ?></li>
                            </ul>
                        </div>
                    </div>
                    <?= $button_html ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>


</body>
<script>
    function cancelBooking(id) {
        if (confirm("Are you sure you want to cancel Booking #" + id + "?")) {

            let formData = new FormData();
            formData.append('action', 'cancel_booking'); // Matches the PHP check
            formData.append('booking_id', id);

            // fetch(window.location.href) posts to the CURRENT page
            fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert("Booking Cancelled!");
                        location.reload(); // Refresh to see changes
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // If you get this error, it usually means the PHP 'exit' was forgotten
                    // and the response contains HTML instead of JSON.
                    alert("Failed to process request.");
                });
        }
    }
</script>

</html>