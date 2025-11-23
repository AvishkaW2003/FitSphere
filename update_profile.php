
<?php
use FitSphere\Database\Database;
// ... session start and authentication checks ...
$db = new Database();
$conn = $db->connect();
$userId = $_SESSION['user']['user_id']; // or 'id'
$new_name = $_POST['name'];
$new_email = $_POST['email'];
$new_phone = $_POST['phone'];

$stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE user_id = ?");
$stmt->execute([$new_name, $new_email, $new_phone, $userId]);

// Send success response back to the JS
echo json_encode(['success' => true, 'message' => 'Profile updated.']);
?>