<?php
// ---------------- DATABASE CONNECTION ----------------
$conn = new mysqli("localhost", "root", "", "measurements"); // change your DB name

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ---------------- SAVE MEASUREMENTS ----------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $standardSize = $_POST["standardSize"];
    $neck = $_POST["neck"];
    $shoulders = $_POST["shoulders"];
    $bicep = $_POST["bicep"];
    $wrist = $_POST["wrist"];
    $chest = $_POST["chest"];
    $sleeves = $_POST["sleeves"];
    $stomach = $_POST["stomach"];
    $jacketLength = $_POST["jacketLength"];
    $waist = $_POST["waist"];
    $hips = $_POST["hips"];
    $thigh = $_POST["thigh"];
    $knee = $_POST["knee"];
    $inseamLength = $_POST["inseamLength"];
    $pantsLength = $_POST["pantsLength"];

    // Example user_id (you can replace this with login session user_id)
    $user_id = 1;

    $sql = "INSERT INTO measurements (
            user_id, standard_size, neck, shoulders, bicep, wrist, chest, sleeves, stomach, 
            jacket_length, waist, hips, thigh, knee, inseam_length, pants_length
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssssssssssss",  
        $user_id, $standardSize, $neck, $shoulders, $bicep, $wrist, 
        $chest, $sleeves, $stomach, $jacketLength, $waist, $hips, 
        $thigh, $knee, $inseamLength, $pantsLength
    );

    if ($stmt->execute()) {
        $successMessage = "Measurements saved successfully!";
    } else {
        $errorMessage = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Measurements</title>
<style>
* {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Futura', 'Century Gothic', Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 40px 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            font-size: 2.2rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        .subtitle {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 2rem;
            font-style: italic;
            color: #333;
        }

        .guide-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .guide-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .guide-section {
            flex: 1;
        }

        .guide-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-align: center;
        }

        .measurements-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .measurement-item {
            text-align: center;
        }

        .measurement-item img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 5px;
            background: #e3f2fd;
        }

        .measurement-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #333;
        }

        .pants-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
        }

        .custom-guide {
            background: white;
            padding: 25px;
            border-radius: 8px;
            border: 3px solid #ddd;
        }

        .custom-guide h3 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .required-list {
            font-size: 0.85rem;
            line-height: 1.8;
            color: #333;
        }

        .required-list div {
            margin-bottom: 3px;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-section {
            margin-bottom: 25px;
        }

        .form-section label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 1rem;
        }

        .size-selector {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .size-selector label {
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0;
        }

        .size-selector select {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #e8e8e8;
            font-size: 0.95rem;
            min-width: 150px;
        }

        .size-info {
            color: #666;
            font-style: italic;
            margin-left: 10px;
        }

        .custom-header {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .measurements-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 40px;
        }

        .form-group {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .form-group label {
            min-width: 130px;
            font-weight: 600;
            margin: 0;
        }

        .form-group input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #e8e8e8;
            font-size: 0.95rem;
        }

        .form-group span {
            font-weight: 600;
            color: #333;
        }

        .save-button {
            display: block;
            width: 250px;
            margin: 40px auto 0;
            padding: 15px;
            background: linear-gradient(135deg, #d4af37 0%, #c5a028 100%);
            color: #000;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .save-button:hover {
            background: linear-gradient(135deg, #c5a028 0%, #b89120 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .img-placeholder {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.7rem;
            font-weight: 600;
            text-align: center;
            padding: 5px;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .measurements-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .pants-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .measurements-form {
                grid-template-columns: 1fr;
            }

            .guide-header {
                flex-direction: column;
            }

            h1 {
                font-size: 1.8rem;
            }

            .subtitle {
                font-size: 1.2rem;
            }
        }

</style>
</head>
<body>

<div class="container">
    <h1>My Measurements</h1>
    <p class="subtitle">"Perfect fit starts with your details."</p>

    <?php if (!empty($successMessage)): ?>
        <p style="color: green; font-weight: bold;"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
        <p style="color: red; font-weight: bold;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <form method="POST">

        <div class="guide-card">
            <img src="images/mesurment.png" 
                 style="width: 100%; border-radius: 8px;">
        </div>

        <div class="form-card">
            <div class="size-selector">
                <label>Standard Size:</label>
                <select name="standardSize">
                    <option value="">Select size</option>
                    <option value="xs">XS</option>
                    <option value="s">S</option>
                    <option value="m">M</option>
                    <option value="l">L</option>
                    <option value="xl">XL</option>
                    <option value="xxl">XXL</option>
                </select>
            </div>

            <h2 class="custom-header">Custom Measurements</h2>

            <div class="measurements-form">

                <?php
                // Create all form fields automatically
                $fields = [
                    "neck", "shoulders", "bicep", "wrist", "chest", "sleeves",
                    "stomach", "jacketLength", "waist", "hips", "thigh", "knee",
                    "inseamLength", "pantsLength"
                ];
                foreach ($fields as $f) {
                    echo "
                    <div class='form-group'>
                        <label>".ucwords(str_replace('_',' ',$f)).":</label>
                        <input type='number' name='$f' step='0.1' value='0'>
                        <span>cm</span>
                    </div>";
                }
                ?>

            </div>

            <button class="save-button" type="submit">Save Measurements</button>
        </div>

    </form>
</div>

</body>
</html>
