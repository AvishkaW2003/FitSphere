<?php
session_start();
require_once __DIR__ . '/../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('user');
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            padding: 30px;
            width: 800px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2d3436;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            color: #636e72;
            font-size: 16px;
        }

        .size-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .size-section h2 {
            color: #2d3436;
            font-size: 18px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .size-section h2:before {
            content: "•";
            color: #D4AF37;
            font-size: 24px;
            margin-right: 10px;
        }

        .size-options {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .size-option {
            padding: 8px 20px;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }

        .size-option:hover {
            border-color: #D4AF37;
            color: #D4AF37;
        }

        .size-option.active {
            background: #D4AF37;
            color: white;
            border-color: #D4AF37;
        }

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

        .current-measurements {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .current-measurements h2 {
            color: #2d3436;
            font-size: 18px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .current-measurements h2:before {
            content: "•";
            color: #D4AF37;
            font-size: 24px;
            margin-right: 10px;
        }

        .measurements-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .measurement-item {
            background: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            border: 1px solid #e9ecef;
        }
    </style>
</head>
<body>
    <?php
    require_once __DIR__ . '/../../includes/db.php';

    use FitSphere\Database\Database;

    // Create database connection
    $database = new Database();
    $conn = $database->connect();

    // Handle form submission
    $message = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_measurements'])) {
        // Validate and save to database - only save fields that match database columns
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

        // Insert or update measurements
        $sql = "INSERT INTO measurements (customer_id, size, neck, chest, waist, hips, sleeve, thigh, inseam, jacket_length, pant_length, updated_at)
                VALUES (1, 'M', :neck, :chest, :waist, :hips, :sleeve, :thigh, :inseam, :jacket_length, :pant_length, NOW())
                ON DUPLICATE KEY UPDATE
                neck = VALUES(neck), chest = VALUES(chest), waist = VALUES(waist), hips = VALUES(hips),
                sleeve = VALUES(sleeve), thigh = VALUES(thigh), inseam = VALUES(inseam),
                jacket_length = VALUES(jacket_length), pant_length = VALUES(pant_length), updated_at = NOW()";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
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
            $message = "Error saving measurements: " . implode(", ", $stmt->errorInfo());
        }
    }

    // Fetch existing measurements
    $existing_measurements = null;
    $sql = "SELECT * FROM measurements WHERE customer_id = 1 ORDER BY updated_at DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $existing_measurements = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>

    <div class="container">
        <div class="header">
            <h1>Custom Measurements</h1>
            <p>Enter your precise measurements for a perfect fit</p>
        </div>

        <?php if ($existing_measurements): ?>
        <div class="current-measurements">
            <h2>Your Current Measurements</h2>
            <div class="measurements-list">
                <div class="measurement-item"><strong>Neck:</strong> <?php echo $existing_measurements['neck'] ? $existing_measurements['neck'] . ' cm' : 'Not set'; ?></div>
                <div class="measurement-item"><strong>Chest:</strong> <?php echo $existing_measurements['chest'] ? $existing_measurements['chest'] . ' cm' : 'Not set'; ?></div>
                <div class="measurement-item"><strong>Waist:</strong> <?php echo $existing_measurements['waist'] ? $existing_measurements['waist'] . ' cm' : 'Not set'; ?></div>
                <div class="measurement-item"><strong>Hips:</strong> <?php echo $existing_measurements['hips'] ? $existing_measurements['hips'] . ' cm' : 'Not set'; ?></div>
                <div class="measurement-item"><strong>Sleeves:</strong> <?php echo $existing_measurements['sleeve'] ? $existing_measurements['sleeve'] . ' cm' : 'Not set'; ?></div>
                <div class="measurement-item"><strong>Thigh:</strong> <?php echo $existing_measurements['thigh'] ? $existing_measurements['thigh'] . ' cm' : 'Not set'; ?></div>
                <div class="measurement-item"><strong>Inseam Length:</strong> <?php echo $existing_measurements['inseam'] ? $existing_measurements['inseam'] . ' cm' : 'Not set'; ?></div>
                <div class="measurement-item"><strong>Jacket Length:</strong> <?php echo $existing_measurements['jacket_length'] ? $existing_measurements['jacket_length'] . ' cm' : 'Not set'; ?></div>
                <div class="measurement-item"><strong>Pant Length:</strong> <?php echo $existing_measurements['pant_length'] ? $existing_measurements['pant_length'] . ' cm' : 'Not set'; ?></div>
            </div>
        </div>
        <?php endif; ?>


        <form method="POST" id="measurementForm">
            <div class="measurements-section">
                <h2>Custom Measurements</h2>
                <div class="measurements-grid">
                    <!-- Row 1 -->
                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Neck:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="neck" placeholder="Enter measurement" step="0.1" min="0">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Chest:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="chest" placeholder="Enter measurement" step="0.1" min="0">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2 -->
                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Waist:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="waist" placeholder="Enter measurement" step="0.1" min="0">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Hips:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="hips" placeholder="Enter measurement" step="0.1" min="0">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    <!-- Row 3 -->
                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Sleeves:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="sleeves" placeholder="Enter measurement" step="0.1" min="0">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Thigh:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="thigh" placeholder="Enter measurement" step="0.1" min="0">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    <!-- Row 4 -->
                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Inseam Length:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="inseam_length" placeholder="Enter measurement" step="0.1" min="0">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Jacket Length:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="jacket_length" placeholder="Enter measurement" step="0.1" min="0">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    <!-- Row 5 -->
                    <div class="form-group">
                        <div class="form-row">
                            <span class="form-label">Pants Length:</span>
                            <div class="input-wrapper">
                                <input type="number" class="measurement-input" name="pant_length" placeholder="Enter measurement" step="0.1" min="0">
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
