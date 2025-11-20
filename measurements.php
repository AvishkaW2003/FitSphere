<?php
include 'includes/header.php'; 
require_once __DIR__ . '/includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('user');

$customer_id = $_SESSION['user']['id'] ?? null; 
if (!$customer_id) {
    header('Location: ' . $baseUrl . '/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Measurements</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            /* 🔥 FIX 1: Adjust body styles for correct flow */
            min-height: auto; 
            padding: 20px; 
            display: block; 
        }

        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            padding: 30px;
            width: 800px;
            margin: 8rem auto; /* 🔥 FIX 2: Center the container */
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        /* ... (rest of header/section styles) ... */

        .measurement-image {
            display: block;
            margin: 0 auto 20px auto;
            /* 🔥 FIX 3: Set responsive sizing */
            min-width: 50%;
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(30, 29, 29, 0.12);
        }
        
        /* ... (rest of your existing CSS styles) ... */
        .measurements-section {
            margin-bottom: 30px;
        }

        .measurements-section h2 {
            color: #2d3436;
            font-size: 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .measurements-section h2:before {
            content: "•";
            color: #D4AF37;
            font-size: 24px;
            margin-right: 10px;
        }

        .measurements-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-row {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .form-label {
            width: 120px;
            font-weight: 500;
            color: #2d3436;
            font-size: 14px;
        }

        .input-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0 15px;
            transition: border-color 0.3s;
        }

        .input-wrapper:focus-within {
            border-color: #D4AF37;
        }

        .measurement-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 12px 0;
            font-size: 15px;
            outline: none;
            color: #2d3436;
        }

        .unit {
            color: #636e72;
            font-size: 14px;
            margin-left: 5px;
        }

        .save-btn {
            background: #D4AF37;
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
            display: block;
            margin: 0 auto;
            width: 200px;
        }

        .save-btn:hover {
            background: #D4AF37;
        }

        .message {
            padding: 12px;
            margin: 20px 0;
            border-radius: 6px;
            text-align: center;
            font-weight: 500;
            display: none;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        body {
        background-color: #f5f7fa;
        min-height: auto; 
        padding: 0; /* 🔥 FIX: Remove body padding */
        margin: 0; /* 🔥 FIX: Remove body margin */
        display: block; 
    }

    /* Target the footer specifically to reset its own spacing */
    .footer {
        /* This should be the class for the main footer element */
        width: 100%;
        margin: 0; /* 🔥 FIX: Ensure the footer itself has no margin */
        padding: 20px 0; /* Adjust vertical padding as needed, but clear horizontal padding */
    }

    .footer-container {
        /* If the container inside the footer is responsible for holding content, 
           we need to ensure it uses margins for inner spacing, not padding that 
           pushes the footer element away from the edges.
           If this container is supposed to be centered, keep its width.
        */
        padding: 0 15px; /* Add horizontal padding back to the *content* container */
        max-width: 1200px; /* Example max width */
        margin: 0 auto; /* Center the content container */
    }

    </style>
</head>
<body>
    <?php
    require_once __DIR__ . '/includes/db.php';

    use FitSphere\Database\Database;

    $database = new Database();
    $conn = $database->connect();

    $message = '';
    // Handle form submission logic (as provided in previous step)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_measurements'])) {
        $measurements = [
            'neck' => $_POST['neck'] ?? null,
            'chest' => $_POST['chest'] ?? null,
            'waist' => $_POST['waist'] ?? null,
            'hips' => $_POST['hips'] ?? null,
            'sleeve' => $_POST['sleeves'] ?? null,
            'thigh' => $_POST['thigh'] ?? null,
            'inseam' => $_POST['inseam_length'] ?? null,
            'jacket_length' => $_POST['jacket_length'] ?? null,
            'pant_length' => $_POST['pant_length'] ?? null
        ];

        // Ensure you use the correct $customer_id here (non-hardcoded)
        $sql = "INSERT INTO measurements (customer_id, size, neck, chest, waist, hips, sleeve, thigh, inseam, jacket_length, pant_length, updated_at)
                VALUES (:customer_id, 'M', :neck, :chest, :waist, :hips, :sleeve, :thigh, :inseam, :jacket_length, :pant_length, NOW())
                ON DUPLICATE KEY UPDATE
                neck = VALUES(neck), chest = VALUES(chest), waist = VALUES(waist), hips = VALUES(hips),
                sleeve = VALUES(sleeve), thigh = VALUES(thigh), inseam = VALUES(inseam),
                jacket_length = VALUES(jacket_length), pant_length = VALUES(pant_length), updated_at = NOW()";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':customer_id' => $customer_id, 
            ':neck' => $measurements['neck'],
            ':chest' => $measurements['chest'],
            ':waist' => $measurements['waist'],
            ':hips' => $measurements['hips'],
            ':sleeve' => $measurements['sleeve'],
            ':thigh' => $measurements['thigh'],
            ':inseam' => $measurements['inseam'],
            ':jacket_length' => $measurements['jacket_length'],
            ':pant_length' => $measurements['pant_length']
        ]);

        if ($stmt->rowCount() > 0) {
            $message = "Your measurements have been saved successfully!";
        } else {
            $errorInfo = $stmt->errorInfo();
            $message = "Error saving measurements: " . ($errorInfo[2] ?? "No changes detected.");
        }
    }

    // Fetch existing measurements
    $sql = "SELECT * FROM measurements WHERE customer_id = :customer_id ORDER BY updated_at DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':customer_id' => $customer_id]); 
    $existing_measurements = $stmt->fetch(PDO::FETCH_ASSOC);
    $defaults = $existing_measurements ?: [];
    ?>

    <div class="container">
        <div class="header">
            <h1>Custom Measurements</h1>
            <p>Enter your precise measurements for a perfect fit</p>
        </div>
    
       <img src="assets/images/measurements.jpg" alt="Measurements Illustration" class="measurement-image">

        <form method="POST" id="measurementForm">
            <div class="measurements-section">
                <h2>Custom Measurements</h2>
                <div class="measurements-grid">
                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Neck:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="neck" placeholder="Enter measurement" step="0.1" min="0" value="<?= $defaults['neck'] ?? '' ?>">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Chest:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="chest" placeholder="Enter measurement" step="0.1" min="0" value="<?= $defaults['chest'] ?? '' ?>">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Waist:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="waist" placeholder="Enter measurement" step="0.1" min="0" value="<?= $defaults['waist'] ?? '' ?>">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Hips:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="hips" placeholder="Enter measurement" step="0.1" min="0" value="<?= $defaults['hips'] ?? '' ?>">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Sleeves:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="sleeves" placeholder="Enter measurement" step="0.1" min="0" value="<?= $defaults['sleeve'] ?? '' ?>">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Thigh:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="thigh" placeholder="Enter measurement" step="0.1" min="0" value="<?= $defaults['thigh'] ?? '' ?>">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Inseam Length:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="inseam_length" placeholder="Enter measurement" step="0.1" min="0" value="<?= $defaults['inseam'] ?? '' ?>">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Jacket Length:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="jacket_length" placeholder="Enter measurement" step="0.1" min="0" value="<?= $defaults['jacket_length'] ?? '' ?>">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Pants Length:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="pant_length" placeholder="Enter measurement" step="0.1" min="0" value="<?= $defaults['pant_length'] ?? '' ?>">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div id="message" class="message <?php echo !empty($message) ? 'success' : ''; ?>" <?php echo !empty($message) ? 'style="display: block;"' : ''; ?>>
                <?php echo $message; ?>
            </div>

            <button type="submit" name="save_measurements" class="save-btn">Submit</button>
        </form>
    </div>
</body>
</html>

<?php include 'includes/footer.php'; ?>