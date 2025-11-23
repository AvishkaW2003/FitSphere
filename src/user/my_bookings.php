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
    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE booking_id = ? AND customer_id = ?");
    // CRITICAL: Ensure the user owns the booking they are canceling
    $result = $stmt->execute([$new_status, $booking_id, $customer_id]);

    // Clear buffer just in case
    ob_clean();

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Booking cancelled successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Update failed or booking not found.']);
    }

    exit; // <--- CRITICAL: This stops the Header from loading below
}

// 3. NOW LOAD THE HEADER (Only happens if we didn't exit above)
include '../../includes/header.php';

// Filter logic based on status query parameter
$statusFilter = $_GET['status'] ?? 'All';

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
";

$params = [$customer_id];

if ($statusFilter !== 'All') {
    // Basic sanitization on status filter
    $allowedStatuses = ['Active', 'Returned', 'Cancelled', 'Overdue', 'Pending', 'Upcoming'];
    if (in_array($statusFilter, $allowedStatuses)) {
        $sql .= " AND b.status = ?";
        $params[] = $statusFilter;
    }
}

$sql .= " ORDER BY b.created_at DESC";


// 2. PDO Preparation and Execution
$stmt = $conn->prepare($sql);
$stmt->execute($params);

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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <style>
        /* CSS for Layout Fix */
        #main-content-wrapper {
            /* This assumes the header is around 4rem (64px) tall and fixed, 
               so we push the content down by about 100px. */
            padding-top: 6rem; 
            min-height: 100vh;
        }
        
        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(9, 9, 9, 0.5);
            display: none; /* Hidden by default */
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(4, 4, 4, 0.3);
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        /* Existing Styles */
        .card {
            margin: auto;
            box-shadow: 0 4px 15px rgba(5, 5, 5, 0.15);
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
            /* Highlight active filter */
        }
        
        .nav-link.active-filter {
            color: #FFC107;
            border-bottom: 2px solid #FFC107;
        }

        .nav-link:hover {
            color: #FFC107;
        }

        #card2 {
            background-color: rgba(228, 249, 233, 1);
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
                width: 95% !important;
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

<body>
    <!-- Custom Modal Structure (Replaces alert/confirm) -->
    <div id="custom-modal" class="modal-overlay" onclick="closeModal(event)">
        <div class="modal-content">
            <h5 id="modal-title" class="mb-3">Confirmation</h5>
            <p id="modal-message">Are you sure?</p>
            <div id="modal-actions">
                <!-- Buttons injected by JS based on type -->
            </div>
        </div>
    </div>


    <div id="main-content-wrapper" class="container">
        
        <h1 class="mb-5">My Bookings</h1>

        <div>
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link <?= $statusFilter === 'All' ? 'active-filter' : '' ?>" href="?status=All">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $statusFilter === 'Active' ? 'active-filter' : '' ?>" href="?status=Active">Active</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $statusFilter === 'Returned' ? 'active-filter' : '' ?>" href="?status=Returned">Returned</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $statusFilter === 'Cancelled' ? 'active-filter' : '' ?>" href="?status=Cancelled">Cancelled</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $statusFilter === 'Overdue' ? 'active-filter' : '' ?>" href="?status=Overdue">Overdue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $statusFilter === 'Upcoming' ? 'active-filter' : '' ?>" href="?status=Upcoming">Upcoming</a>
                </li>
            </ul>
        </div>

        <?php if (empty($bookings)): ?>
            <p class="text-center mt-5">You have no current or past bookings that match the filter (<?= htmlspecialchars($statusFilter) ?>).</p>
        <?php else: ?>
            <?php foreach ($bookings as $booking):
                $card_id = '';
                $item_class = '';
                $button_html = '';
                $extra_detail_html = '';
                $bookingId = $booking['booking_id'];

                // Calculate Rent Fee (Total Price in DB is often Rent + Deposit)
                // Assuming 'total_price' is the full amount paid (Rent + Deposit)
                $rent_fee = $booking['total_price'] - $booking['deposit'];

                // Determine styling and actions based on status
                switch ($booking['status']) {
                    case 'Returned':
                        $card_id = 'id="card2"';
                        $item_class = 'color1';
                        // Display refund info if available
                        $refund_amount = $booking['refund'] !== null ? $booking['refund'] : $booking['deposit'];
                        $extra_detail_html = '
                            <ul class="list-group list-group-horizontal">
                                <li class="list-group-item ' . $item_class . '">Refund Amount :</li>
                                <li class="list-group-item ' . $item_class . '">Rs.' . number_format($refund_amount, 2) . '</li>
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
                    case 'Pending':
                    default:
                        // Default style (no special background color)
                        $button_html = '<button type="button" class="btn btn-danger" onclick="showConfirmModal(' . $bookingId . ')">Cancel Booking</button>';
                        $item_class = '';
                        break;
                }
            ?>
                <div class="card w-75 mb-3" <?= $card_id ?>>
                    <div class="card-body">
                        <div class="titleAndDate">
                            <h5 class="card-title"><?= htmlspecialchars($booking['product_name']) ?> (Size: <?= htmlspecialchars($booking['size']) ?>)</h5>
                            <p>Booking ID: #<?= $bookingId ?></p>
                        </div>
                        <p class="text-muted text-end"><?= htmlspecialchars($booking['start_date']) ?> to <?= htmlspecialchars($booking['end_date']) ?></p>

                        <div class="imageAndDetails">

                            <img src="../../assets/images/suits/<?= htmlspecialchars($booking['product_image']) ?>" class="rounded float-start" id="clothImage" alt="<?= htmlspecialchars($booking['product_name']) ?>" onerror="this.onerror=null;this.src='https://placehold.co/80x100/CCCCCC/333333?text=No+Image';">

                            <div class="details">
                                <ul class="list-group list-group-horizontal">
                                    <li class="list-group-item <?= $item_class ?>">Rental Fee :</li>
                                    <li class="list-group-item <?= $item_class ?>">Rs.<?= number_format($rent_fee, 2) ?></li>
                                </ul>
                                <ul class="list-group list-group-horizontal">
                                    <li class="list-group-item <?= $item_class ?>">Deposit Paid :</li>
                                    <li class="list-group-item <?= $item_class ?>">Rs.<?= number_format($booking['deposit'], 2) ?></li>
                                </ul>
                                <ul class="list-group list-group-horizontal">
                                    <li class="list-group-item <?= $item_class ?>">Total Paid :</li>
                                    <li class="list-group-item <?= $item_class ?>">Rs.<?= number_format($booking['total_price'], 2) ?></li>
                                </ul>

                                <?= $extra_detail_html ?>

                                <ul class="list-group list-group-horizontal">
                                    <li class="list-group-item <?= $item_class ?>">Status :</li>
                                    <li class="list-group-item <?= $item_class ?>"><strong><?= htmlspecialchars($booking['status'] ?: 'UNKNOWN') ?></strong></li>
                                </ul>
                            </div>
                        </div>
                        <?= $button_html ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div> <!-- End of main-content-wrapper -->

</body>
<script>
    // --- CUSTOM MODAL IMPLEMENTATION (Replaces alert/confirm) ---

    const modal = document.getElementById('custom-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const modalActions = document.getElementById('modal-actions');

    function showModal(title, message, actionsHtml) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modalActions.innerHTML = actionsHtml;
        modal.style.display = 'flex';
    }

    function closeModal(event) {
        // Only close if clicking the background, not the modal content itself
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }

    function closeMessageModal() {
        modal.style.display = 'none';
    }

    // Function to replace JS confirm()
    function showConfirmModal(bookingId) {
        const title = "Confirm Cancellation";
        const message = "Are you sure you want to cancel Booking #" + bookingId + "? This action cannot be undone.";
        const actionsHtml = `
            <button class="btn btn-secondary me-2" onclick="closeMessageModal()">No, Keep it</button>
            <button class="btn btn-danger" onclick="processCancellation(${bookingId})">Yes, Cancel</button>
        `;
        showModal(title, message, actionsHtml);
    }

    // Function to replace JS alert()
    function showMessageModal(title, message, reload = false) {
        const actionsHtml = `
            <button class="btn btn-primary" onclick="${reload ? 'location.reload()' : 'closeMessageModal()'}">OK</button>
        `;
        showModal(title, message, actionsHtml);
    }
    
    function processCancellation(id) {
        // Close the confirmation modal first
        modal.style.display = 'none';

        let formData = new FormData();
        formData.append('action', 'cancel_booking');
        formData.append('booking_id', id);

        fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Use custom message box for success
                    showMessageModal("Success", data.message || "Booking successfully cancelled.", true);
                } else {
                    // Use custom message box for error
                    showMessageModal("Error", data.message || "An unknown error occurred during cancellation.", false);
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                showMessageModal("Fatal Error", "Failed to connect to the server or process request.", false);
            });
    }
</script>

</html>