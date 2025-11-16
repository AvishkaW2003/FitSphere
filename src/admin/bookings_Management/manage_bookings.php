<?php
// manage_bookings.php
include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';
require_once __DIR__ . '/../../../includes/db.php';
use FitSphere\Database\Database;

$db = new Database();
$conn = $db->connect();

$search = trim($_GET['search'] ?? '');
$statusFilter = $_GET['status'] ?? '';
$sort = $_GET['sort'] ?? 'created_at';

$sql = "SELECT b.*, c.name AS customer_name, p.title AS product_title
        FROM bookings b
        LEFT JOIN customers c ON b.customer_id = c.customer_id
        LEFT JOIN products p ON b.product_id = p.product_id
        WHERE 1=1";

$params = [];
if ($search !== '') {
    $sql .= " AND (c.name LIKE :search OR p.title LIKE :search OR b.booking_id LIKE :search)";
    $params[':search'] = "%$search%";
}
if ($statusFilter !== '') {
    $sql .= " AND b.status = :status";
    $params[':status'] = $statusFilter;
}
$allowedSort = ['created_at','start_date','end_date','total_price'];
if (!in_array($sort, $allowedSort)) $sort = 'created_at';
$sql .= " ORDER BY b.$sort DESC";

$stmt = $conn->prepare($sql);
foreach ($params as $k=>$v) $stmt->bindValue($k, $v);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Manage Bookings</title>
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/css/adminManagement.css?v=<?= time() ?>">

</head>
<body>

<div class="admin-container">

  <h2 class="text-center my-4">Manage Bookings</h2>

  <div class="booking-top">

    <!-- LEFT SIDE (Total Bookings) -->
    <div class="booking-left">
        <div class="total-box">
            Total Bookings <strong><?= count($bookings) ?></strong>
        </div>
    </div>

    <!-- RIGHT SIDE (filters + search + export) -->
    <div class="booking-actions">

        <input type="search" 
               name="search" 
               form="filterForm"
               class="form-control booking-search"
               placeholder="Search by customer, suit or id"
               value="<?= htmlspecialchars($search) ?>">

        <form method="GET" id="filterForm" class="filters-row">

            <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit();">
                <option value="">All Status</option>
                <option value="Active" <?= $statusFilter==='Active' ? 'selected':'' ?>>Active</option>
                <option value="Upcoming" <?= $statusFilter==='Upcoming' ? 'selected':'' ?>>Upcoming</option>
                <option value="Completed" <?= $statusFilter==='Completed' ? 'selected':'' ?>>Completed</option>
                <option value="Overdue" <?= $statusFilter==='Overdue' ? 'selected':'' ?>>Overdue</option>
                <option value="Cancelled" <?= $statusFilter==='Cancelled' ? 'selected':'' ?>>Cancelled</option>
            </select>

            <select name="sort" class="form-select" onchange="document.getElementById('filterForm').submit();">
                <option value="created_at" <?= $sort==='created_at' ? 'selected':'' ?>>Newest</option>
                <option value="start_date" <?= $sort==='start_date' ? 'selected':'' ?>>Start Date</option>
                <option value="end_date" <?= $sort==='end_date' ? 'selected':'' ?>>End Date</option>
                <option value="total_price" <?= $sort==='total_price' ? 'selected':'' ?>>Total Price</option>
            </select>

        </form>

        <!-- <a class="btn btn-outline export-btn" href="#">Export</a> -->
    </div>

</div>

  <div class="table-wrapper">
    <table class="table" >
      <thead style="background-color: #d4af37ff;">
      <tr>
        <th>ID</th>
        <th>Customer Name</th>
        <th>Suit</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Total Price</th>
        <th>Deposit</th>
        <th>Late Days</th>
        <th>Late Fee</th>
        <th>Refund</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
      </thead>

      <tbody>
      <?php if (count($bookings) === 0): ?>
        <tr><td colspan="12">No bookings found.</td></tr>
      <?php else: ?>
        <?php foreach ($bookings as $b): ?>
          <tr>
            <td><?= $b['booking_id'] ?></td>
            <td><?= $b['customer_name'] ?></td>
            <td><?= $b['product_title'] ?></td>
            <td><?= $b['start_date'] ?></td>
            <td><?= $b['end_date'] ?></td>
            <td><?= number_format($b['total_price'],2) ?></td>
            <td><?= number_format($b['deposit'],2) ?></td>
            <td><?= $b['late_days'] ?></td>
            <td><?= number_format($b['late_fee'],2) ?></td>
            <td><?= number_format($b['refund'],2) ?></td>
            <td>
              <?php
                if ($b['status']=="Active") echo "<span class='badge-active'>Active</span>";
                elseif ($b['status']=="Overdue") echo "<span class='badge-overdue'>Overdue</span>";
                elseif ($b['status']=="Completed") echo "<span class='badge-completed'>Completed</span>";
                elseif ($b['status']=="Upcoming") echo "<span class='badge-upcoming'>Upcoming</span>";
                else echo "<span class='badge-cancel'>Cancelled</span>";
              ?>
            </td>
            <td>
              <a class="action-btn edit-btn" href="view_bookings.php?booking_id=<?= $b['booking_id'] ?>">View</a> <br><br>
              <a class="action-btn return-btn " href="view_bookings.php?booking_id=<?= $b['booking_id'] ?>&mode=return">Return</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>

</body>
</html>

</html>
