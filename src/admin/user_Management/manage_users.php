<?php
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';

require_once __DIR__ . '/../../../includes/middleware/AuthMiddleware.php';
AuthMiddleware::requireRole('admin');

use FitSphere\Database\Database;

$db = new Database();
$conn = $db->connect();

$search = trim($_GET['search'] ?? '');
$statusFilter = $_GET['status'] ?? '';
$roleFilter = $_GET['role'] ?? '';

// ✅ FIX: Changed 'customers c' to 'users u'
$sql = "SELECT *
        FROM users u
        WHERE 1=1";
$params = [];

if ($search !== '') {
    // ✅ FIX: Changed 'customer_id' to 'user_id'
    $sql .= " AND (name LIKE :s OR email LIKE :s OR phone_no LIKE :s OR user_id = :idExact)";
    $params[':s'] = "%$search%";
    if (is_numeric($search)) $params[':idExact'] = intval($search);
    else $params[':idExact'] = 0;
}

if ($statusFilter !== '') {
    $sql .= " AND u.status = :status";
    $params[':status'] = $statusFilter;
}


$sql .= " ORDER BY join_date DESC";

$stmt = $conn->prepare($sql);
foreach ($params as $k => $v) $stmt->bindValue($k, $v);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>assets/css/users.css?v=<?= time() ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="user-container">
    <h2 style="text-align: center; margin-bottom:4rem;">Manage Users</h2>

    <div class="users-top">
        <div class="users-left">
            <div class="total-box">Users <strong><?= count($users) ?></strong></div>
        </div>

        <div class="users-actions">
            <input type="search" form="filterForm" name="search" class="booking-search" 
                 placeholder="Search by name, email, phone or id" value="<?= htmlspecialchars($search) ?>">

            <form id="filterForm" method="GET" class="filters-row">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="Active" <?= $statusFilter==='Active' ? 'selected':'' ?>>Active</option>
                    <option value="Suspended" <?= $statusFilter==='Suspended' ? 'selected':'' ?>>Suspended</option>
                    <option value="Blocked" <?= $statusFilter==='Blocked' ? 'selected':'' ?>>Blocked</option>
                </select>

                <button type="submit" class="apply-btn">Apply</button>
            </form>

            <!-- <a class="btn btn-outline" href="#">Export</a> -->
        </div>
    </div>

    <div class="table-wrapper mt-3">
        <table class="users-table table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) === 0): ?>
                    <tr><td colspan="8" class="text-center">No users found.</td></tr>
                <?php else: foreach ($users as $u): ?>
                    <tr>
                        <!-- ✅ FIX: Changed 'customer_id' to 'user_id' -->
                        <td><?= htmlspecialchars($u['user_id']) ?></td>
                        <td><?= htmlspecialchars($u['name']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['phone_no']) ?></td>
                        <td><?= htmlspecialchars($u['join_date']) ?></td>
                        <!-- ✅ FIX: Changed 'customer_id' to 'user_id' in ID for uniqueness -->
                        <td id="status-badge-<?= $u['user_id'] ?>">
                            <?php
                                if ($u['status'] === 'Active') echo "<span class='badge-active'>Active</span>";
                                elseif ($u['status'] === 'Suspended') echo "<span class='badge-suspended'>Suspended</span>";
                                else echo "<span class='badge-blocked'>Blocked</span>";
                            ?>
                        </td>
                        <td>
                            <!-- ✅ FIX: Changed 'customer_id' to 'user_id' in link -->
                            <a class="action-sm action-view" href="view_users.php?user_id=<?= $u['user_id'] ?>">View</a>
                            <?php
                                $toggleTarget = $u['status'] === 'Active' ? 'Suspended' : 'Active';
                                $toggleLabel = $u['status'] === 'Active' ? 'Suspend' : 'Activate';
                            ?>
                            <!-- ✅ FIX: Changed 'customer_id' to 'user_id' in data-id -->
                            <a href="#" data-id="<?= $u['user_id'] ?>" data-target="<?= $toggleTarget ?>" class="action-sm action-toggle toggle-status"><?= $toggleLabel ?></a>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="<?= $baseUrl ?>assets/js/users.js?v=<?= time() ?>"></script>
</body>
</html>
<?php include '../../../includes/footerAdmin.php'; ?>