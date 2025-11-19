<?php
include(__DIR__ . '/../../../includes/headerAdmin.php');
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Core\Session;
use FitSphere\Database\Database;

Session::start();

// --- FIXED SESSION USER ---
$user = Session::get("user");

if (!$user || $user["role"] !== "admin") {
    die("No logged-in admin found.");
}

$userId = $user["id"];

$db = new Database();
$conn = $db->connect();

// Fetch admin
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $userId]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    die("Admin not found.");
}

$message = "";  // <-- FIXED: Always defined

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);

    if ($name && $email) {
        $update = $conn->prepare("
            UPDATE users SET name = :name, email = :email WHERE id = :id
        ");

        $update->execute([
            'name' => $name,
            'email' => $email,
            'id' => $userId
        ]);

        $message = "Profile updated successfully!";
        $admin['name'] = $name;
        $admin['email'] = $email;

        // Update session
        $user["name"] = $name;
        $user["email"] = $email;
        Session::set("user", $user);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../../../assets/css/profile.css">
</head>

<body>
<div class="profile-container">
    <h2>Edit Profile</h2>

    <?php if ($message): ?>
        <p class="success"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" class="form-card">
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($admin['name']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>

        <button class="btn-save">Save Changes</button>
        <a href="profile.php" class="btn-cancel">Cancel</a>
    </form>
</div>
</body>
</html>
<?php include '../../../includes/footerAdmin.php'; ?>
