<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitSphere - Manage Bookings</title>
    <style>
        /* KEEP ALL YOUR EXISTING CSS STYLES - THEY REMAIN EXACTLY THE SAME */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Futura', 'Century Gothic', Arial, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
        }

        nav {
            background-color: #1a1a1a;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 3rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a1a1a;
        }

        .brand-name {
            color: white;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .nav-links {
            display: flex;
            gap: 3rem;
            list-style: none;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #d4af37;
        }

        .user-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #d4af37 0%, #c5a028 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .user-icon:hover {
            transform: scale(1.1);
        }

        .user-icon::before {
            content: '👤';
            font-size: 1.5rem;
            color: white;
        }

        .container {
            max-width: 900px;
            margin: 3rem auto;
            padding: 0 2rem;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            font-weight: 700;
        }

        .booking-card {
            background: white;
            border-radius: 16px;
            padding: 3rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .card-header {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 2.5rem;
        }

        .form-group {
            display: grid;
            grid-template-columns: 200px 1fr;
            align-items: center;
            gap: 2rem;
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            font-size: 1.05rem;
            text-align: left;
        }

        .form-group input,
        .form-group select {
            padding: 0.8rem 1.2rem;
            border: 2px solid #d0d0d0;
            border-radius: 8px;
            font-size: 1rem;
            background: #f9f9f9;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #d4af37;
            background: white;
        }

        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 3rem;
            padding-top: 2rem;
        }

        .cancel-link {
            color: #333;
            text-decoration: underline;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: color 0.3s;
        }

        .cancel-link:hover {
            color: #d4af37;
        }

        .process-btn {
            padding: 1rem 3rem;
            background: linear-gradient(135deg, #d4af37 0%, #c5a028 100%);
            color: #1a1a1a;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(212, 175, 55, 0.3);
            text-transform: uppercase;
        }

        .process-btn:hover {
            background: linear-gradient(135deg, #c5a028 0%, #b89120 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(212, 175, 55, 0.4);
        }

        /* ADD THESE NEW STYLES FOR MESSAGES */
        .message {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            nav {
                padding: 1rem;
                flex-wrap: wrap;
            }

            .nav-links {
                gap: 1.5rem;
                font-size: 0.9rem;
            }

            .container {
                margin: 2rem auto;
                padding: 0 1rem;
            }

            h1 {
                font-size: 2rem;
            }

            .booking-card {
                padding: 2rem 1.5rem;
            }

            .form-group {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .form-group label {
                text-align: left;
            }

            .action-buttons {
                flex-direction: column;
                gap: 1.5rem;
            }

            .process-btn {
                width: 100%;
            }
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
            <li><a href="index.php" style="color: #d4af37;">Dashboard</a></li>
            <li><a href="bookings.php">All Bookings</a></li>
            <li><a href="#">Customers</a></li>
            <li><a href="#">Reports</a></li>
        </ul>
        <div class="user-icon"></div>
    </nav>

    <div class="container">
        <h1>Manage Bookings</h1>

        <div class="booking-card">
            <h2 class="card-header">Booking Details</h2>

            <?php
            // PHP CODE TO PROCESS FORM SUBMISSION
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                include_once 'config.php';
                include_once 'Booking.php';
                
                $database = new Database();
                $db = $database->getConnection();
                $booking = new Booking($db);
                
                // Get form data
                $booking->customer_name = $_POST['customerName'];
                $booking->suit = $_POST['suit'];
                $booking->period = $_POST['period'];
                $booking->total = $_POST['total'];
                $booking->deposite = $_POST['deposite'];
                $booking->late_days = $_POST['lateDays'];
                $booking->late_fees = $_POST['lateFees'];
                $booking->refund_amount = $_POST['refundAmount'];
                $booking->status = $_POST['status'];
                $booking->returned_date = $_POST['returnedData'];
                $booking->manual_late_days = $_POST['manualLateDays'];
                $booking->manual_late_fee = $_POST['manualLateFee'];
                
                // Save to database
                if($booking->create()) {
                    echo '<div class="message success">Booking processed successfully! Booking ID: ' . $db->lastInsertId() . '</div>';
                } else {
                    echo '<div class="message error">Unable to process booking. Please try again.</div>';
                }
            }
            ?>

            <!-- CHANGE: Added method="POST" action="" to form -->
            <form id="bookingForm" method="POST" action="">
                <div class="form-group">
                    <label for="customerName">Customer Name</label>
                    <input type="text" id="customerName" name="customerName" required>
                </div>

                <div class="form-group">
                    <label for="suit">Suit</label>
                    <input type="text" id="suit" name="suit" required>
                </div>

                <div class="form-group">
                    <label for="period">Period</label>
                    <input type="text" id="period" name="period" placeholder="e.g., 7 days" required>
                </div>

                <div class="form-group">
                    <label for="total">Total</label>
                    <input type="number" id="total" name="total" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="deposite">Deposit</label>
                    <input type="number" id="deposite" name="deposite" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="lateDays">Late Days</label>
                    <input type="number" id="lateDays" name="lateDays" min="0" value="0">
                </div>

                <div class="form-group">
                    <label for="lateFees">Late Fees</label>
                    <input type="number" id="lateFees" name="lateFees" step="0.01" value="0">
                </div>

                <div class="form-group">
                    <label for="refundAmount">Refund Amount</label>
                    <input type="number" id="refundAmount" name="refundAmount" step="0.01" required>
                </div>

                <!-- CHANGE: Changed status from text input to select -->
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="">Select Status</option>
                        <option value="Processing">Processing</option>
                        <option value="Completed">Completed</option>
                        <option value="Returned">Returned</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="returnedData">Returned Date</label>
                    <input type="date" id="returnedData" name="returnedData">
                </div>

                <div class="form-group">
                    <label for="manualLateDays">Manual Late Days</label>
                    <input type="number" id="manualLateDays" name="manualLateDays" min="0" value="0">
                </div>

                <div class="form-group">
                    <label for="manualLateFee">Manual Late Fee</label>
                    <select id="manualLateFee" name="manualLateFee">
                        <option value="">Select option</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>

                <div class="action-buttons">
                    <span class="cancel-link" onclick="cancelBooking()">Cancel</span>
                    <button type="submit" class="process-btn">PROCESS RETURN</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // KEEP ALL YOUR EXISTING JAVASCRIPT CODE
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            // Form will be submitted to PHP backend
            console.log('Form submitted to PHP backend');
        });

        function cancelBooking() {
            if (confirm('Are you sure you want to cancel this booking?')) {
                document.getElementById('bookingForm').reset();
            }
        }

        document.getElementById('lateDays').addEventListener('input', function() {
            const lateDays = parseInt(this.value) || 0;
            const feePerDay = 10; 
            const lateFees = lateDays * feePerDay;
            document.getElementById('lateFees').value = lateFees.toFixed(2);
            updateRefundAmount();
        });

        function updateRefundAmount() {
            const deposite = parseFloat(document.getElementById('deposite').value) || 0;
            const lateFees = parseFloat(document.getElementById('lateFees').value) || 0;
            const refundAmount = deposite - lateFees;
            document.getElementById('refundAmount').value = refundAmount > 0 ? refundAmount.toFixed(2) : 0;
        }

        document.getElementById('deposite').addEventListener('input', updateRefundAmount);
        document.getElementById('lateFees').addEventListener('input', updateRefundAmount);

        // Set today's date as default for returned date
        document.getElementById('returnedData').valueAsDate = new Date();

        // Navigation active link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.nav-links a').forEach(l => l.style.color = 'white');
                this.style.color = '#d4af37';
            });
        });
    </script>
</body>
</html>