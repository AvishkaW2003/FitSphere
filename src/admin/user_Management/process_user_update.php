<?php

require_once __DIR__ . '/../../../includes/db.php';
use FitSphere\Database\Database;

$db = new Database();
$conn = $db->connect();


// --- 1. HANDLE STATUS TOGGLE ---
if (isset($_POST['action']) && $_POST['action'] === 'toggle_status') {
    
    $user_id = intval($_POST['user_id']);
    $newStatus = $_POST['status'];

    if (!$user_id || !$newStatus) {
        echo json_encode(['success' => false, 'error' => 'Invalid data']);
        exit;
    }

    // 🔥 FIX: Convert status to proper enum capitalization
    $newStatus = ucfirst(strtolower($newStatus));

    $upd = $conn->prepare("UPDATE users SET status = :st WHERE user_id = :id");
    $ok = $upd->execute([':st' => $newStatus, ':id' => $user_id]);

    echo json_encode(['success' => $ok]);
    exit;
}


// --- 2. HANDLE USER PROFILE UPDATE ---
if ($_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST['action']) &&
    $_POST['action'] === 'update_user') {

    $user_id = intval($_POST['user_id']);
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $status = trim($_POST['status']);

    // 🔥 FIX: Normalize to match ENUM
    $status = ucfirst(strtolower($status));

    $upd = $conn->prepare("
        UPDATE users 
        SET name = :name, phone_no = :phone, status = :status
        WHERE user_id = :id
    ");

    $ok = $upd->execute([
        ':name' => $name,
        ':phone' => $phone,
        ':status' => $status,
        ':id' => $user_id
    ]);

    if ($ok) {
        header("Location: view_user.php?user_id={$user_id}&updated=1");
        exit;
    } else {
        die("Update failed");
    }
}

// Fallback
echo json_encode(['success' => false, 'error' => 'Invalid request']);
exit;
?>
