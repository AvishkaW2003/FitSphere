<?php
require_once __DIR__ . '/../../../includes/db.php';
use FitSphere\Database\Database;

$db = new Database();
$conn = $db->connect();


if (
    isset($_POST['action']) &&
    $_POST['action'] === 'toggle_status'
) {
    $user_id = intval($_POST['user_id']);
    $newStatus = $_POST['status'];

    if (!$user_id || !$newStatus) {
        echo json_encode(['success' => false, 'error' => 'Invalid data']);
        exit;
    }

    $upd = $conn->prepare("UPDATE customers SET status = :st WHERE customer_id = :id");
    $ok = $upd->execute([':st' => $newStatus, ':id' => $user_id]);

    if ($ok) {
        echo json_encode(['success'=>true]);
    } else {
        echo json_encode(['success'=>false, 'error'=>'DB error']);
    }
    exit;
}

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST['action']) &&
    $_POST['action'] === 'update_user'
) {
    $user_id = intval($_POST['user_id']);
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $status = $_POST['status'];

    $upd = $conn->prepare("
        UPDATE customers 
        SET name = :name, phone_no = :phone, status = :status 
        WHERE customer_id = :id
    ");

    $ok = $upd->execute([
        ':name' => $name,
        ':phone' => $phone,
        ':status' => $status,
        ':id'    => $user_id
    ]);

    if ($ok) {
        header("Location: manage_users.php?user_id={$user_id}&updated=1");
        exit;
    } else {
        die("Update failed");
    }
}

echo "Invalid request";
exit;
?>
<?php include '../../../includes/footerAdmin.php'; ?>
