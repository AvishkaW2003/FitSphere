<?php
// src/admin/users/view_user.php
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';
require_once __DIR__ . '/../../../includes/db.php';
use FitSphere\Database\Database;

$db = new Database();
$conn = $db->connect();

if (!isset($_GET['user_id'])) {
  header("Location: manage_users.php");
  exit;
}
$user_id = intval($_GET['user_id']);

$stmt = $conn->prepare("SELECT * FROM customers WHERE customer_id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) die("User not found");
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>View User</title>
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/css/users.css?v=<?= time() ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="form-card">
    <div class="user-card">
        <h3><?= htmlspecialchars($user['name']) ?> <small class="text-muted">#<?= $user['customer_id'] ?></small></h3>
        <p class="small-muted"><?= htmlspecialchars($user['email']) ?> • <?= htmlspecialchars($user['phone_no']) ?></p>
    </div>

<div >
    <form method="POST" action="process_user_update.php" class="mt-3">
      <input type="hidden" name="action" value="update_user">
      <input type="hidden" name="user_id" value="<?= $user['customer_id'] ?>">

    <div>
        <div class="col">
          <label>Full Name</label>
          <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>">
        </div>
        <div class="col">
          <label>Phone</label>
          <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone_no']) ?>">
        </div>
    </div>
      
        <div class="col">
          <label>Status</label>
          <select name="status" class="form-select">
            <option value="Active" <?= $user['status']==='Active'?'selected':'' ?>>Active</option>
            <option value="Suspended" <?= $user['status']==='Suspended'?'selected':'' ?>>Suspended</option>
            <option value="Blocked" <?= $user['status']==='Blocked'?'selected':'' ?>>Blocked</option>
          </select>
        </div>
      </div>

      <div class="mt-3 d-flex justify-content-between">
        <a class="cancel-btn " href="manage_users.php">Back</a>
        <button class="submit-btn" type="submit">Save Changes</button>
      </div>
    </form>
</div>
    
  
</div>
</body>
</html>
