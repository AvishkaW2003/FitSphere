<?php
// CRITICAL: Ensure the session is started and includes are correct.
include 'includes/header.php';

require_once __DIR__ . '/includes/middleware/AuthMiddleware.php';
require_once __DIR__ . '/session.php'; 
require_once 'includes/db.php'; 

use FitSphere\Database\Database;
use FitSphere\Core\Session;

Session::start();
AuthMiddleware::requireRole('user');

// --- 1. Get User ID and Connection ---
$user = Session::get('user');
$userId = $user['user_id'] ?? $user['id'] ?? null; 

if (!$userId) {
    header("Location: /FitSphere/login.php?error=no_session");
    exit;
}

// --- 2. Database Connection and User Details Fetch ---
$fullName = 'Error';
$email = 'Error';
$phone = 'Error';
$recentBookings = [];
$totalBookings = 0;

try {
    // Attempt connection
    $db = new Database();
    $conn = $db->connect(); 
    
    // Check if the connection is a valid PDO object before running queries
    if (!$conn instanceof \PDO) {
         throw new \Exception("Database connection failed or returned an invalid object.");
    }
    
    // FIX: Changed 'phone' to 'phone_no' to match your table schema
    $stmt_user = $conn->prepare("SELECT name, email, phone_no FROM users WHERE user_id = :uid");
    $stmt_user->bindParam(':uid', $userId, \PDO::PARAM_INT);
    $stmt_user->execute();
    $userData = $stmt_user->fetch(\PDO::FETCH_ASSOC);

    // Assign fetched values or use safe defaults
    $fullName = htmlspecialchars($userData['name'] ?? 'N/A');
    $email = htmlspecialchars($userData['email'] ?? 'N/A');
    // FIX: Using the correct column key 'phone_no' for the variable assignment
    $phone = htmlspecialchars($userData['phone_no'] ?? 'N/A'); 

    // --- 3. Fetch Recent Bookings and Count ---
    
    // Fetch count
    $stmt_count = $conn->prepare("SELECT COUNT(booking_id) AS total FROM bookings WHERE customer_id = :uid");
    $stmt_count->bindParam(":uid", $userId);
    $stmt_count->execute();
    $totalBookings = $stmt_count->fetchColumn();

    // Fetch recent bookings
    $stmt_bookings = $conn->prepare("
        SELECT 
            b.booking_id,
            ps.title AS outfit_name,
            b.start_date,
            b.end_date,
            b.total_price,
            b.status
        FROM bookings b
        LEFT JOIN product_inventory pi ON b.product_id = pi.product_id
        LEFT JOIN product_styles ps ON pi.style_id = ps.style_id
        WHERE b.customer_id = :uid
        ORDER BY b.booking_id DESC
        LIMIT 3
    ");
    $stmt_bookings->bindParam(":uid", $userId);
    $stmt_bookings->execute();
    $recentBookings = $stmt_bookings->fetchAll(\PDO::FETCH_ASSOC);

} catch (\PDOException $e) {
    // PDO errors
    error_log("Profile Fetch (PDO) Error: " . $e->getMessage());
    $fullName = 'Database Error'; 
    $email = 'Connection Failed'; 
    $phone = 'Check logs';
    
} catch (\Exception $e) {
    // General errors
    error_log("Profile General Error: " . $e->getMessage());
    $fullName = 'System Error'; 
    $email = 'Check logs'; 
    $phone = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>User Profile – Fitsphere</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;600&display=swap" rel="stylesheet">

  <style>
    :root{
      --gold: #d4aa2a;     /* your gold */
      --gold-dark: #b28d20;
      --black: #111;
      --panel: #fff;
      --bg: #f4f4f4;
      --muted: #666;
    }

    *{box-sizing:border-box}
    body{
      margin:0;
      font-family:"Josefin Sans", sans-serif;
      background:var(--bg);
      color:var(--black);
      -webkit-font-smoothing:antialiased;
    }

    .container{width:90%;max-width:1100px;margin:8rem auto}

    h1{ text-align:center; font-size:32px; margin-bottom:22px; }

    /* profile card */
    .profile-card{
      background:var(--panel);
      border-radius:12px;
      padding:28px;
      display:flex;
      gap:30px;
      align-items:center;
      box-shadow:0 6px 18px rgba(0,0,0,0.08);
    }

    .profile-img{
      width:180px;
      height:180px;
      border-radius:12px;
      background:#f0f0f0;
      display:flex;
      align-items:center;
      justify-content:center;
      box-shadow:0 6px 14px rgba(0,0,0,0.06);
    }
    .profile-img img{ width:120px; height:120px; object-fit:cover; border-radius:8px; }

    .profile-info{flex:1}
    .profile-info p{ font-size:18px; margin:8px 0; color:var(--black) }

    /* BUTTON STYLES - unified font weight */
    .btn, .detail-btn {
      padding:10px 18px;
      border-radius:8px;
      font-size:16px;
      cursor:pointer;
      border:0;
      transition:background .18s, transform .12s;
      font-weight:400; /* ensure same weight as detail button */
    }

    .btn:active{ transform:translateY(1px); }

    .btn-edit{ background:var(--gold); color:var(--black); }
    .btn-edit:hover{ background:var(--gold-dark); }

    .btn-pass{ background:var(--black); color:#fff; }
    .btn-pass:hover{ background:#222; }

    .detail-btn{
      display:inline-block;
      background:var(--gold);
      color:var(--black);
      border-radius:8px;
      text-decoration:none;
      margin-top:12px;
    }
    .detail-btn:hover{ background:var(--gold-dark); }

    .btn-box{ margin-top:14px; }

    /* info cards */
    .info-row{ display:flex; gap:20px; margin-top:24px; }
    .card{
      flex:1;
      background:var(--panel);
      padding:20px;
      border-radius:10px;
      box-shadow:0 6px 14px rgba(0,0,0,0.06);
    }
    .card h2{ margin:0 0 12px; font-size:20px }
    .card p{ margin:8px 0; color:var(--muted) }

    /* bookings table */
    .bookings{ margin-top:26px; }
    .table-wrap{ overflow:auto; background:transparent; padding:0 }
    table{ width:100%; border-collapse:collapse; background:transparent; }
    th, td{ padding:12px 14px; text-align:left; border-bottom:1px solid #f0f0f0; }
    thead th{ background:linear-gradient(90deg,var(--gold),var(--gold-dark)); color:var(--black); font-weight:700; }
    .status{ padding:6px 10px; border-radius:999px; font-weight:700; font-size:13px; display:inline-block; }

    .status-upcoming{ background:#fff3e6; color:#d97400; border:1px solid rgba(208,116,0,0.06); }
    .status-booked{ background:#fff4f1; color:#d95a00; border:1px solid rgba(208,116,0,0.06); }
    .status-returned{ background:#eefaf1; color:#0b8a5a; border:1px solid rgba(0,0,0,0.02); }
    .link-btn{ color:var(--gold); font-weight:700; text-decoration:none; }

    /* modal base */
    .modal{
      position:fixed; inset:0;
      display:flex; align-items:center; justify-content:center;
      background:rgba(0,0,0,0.45);
      opacity:0; visibility:hidden; transition:opacity .18s, visibility .18s;
      z-index:60;
    }
    .modal.visible{ opacity:1; visibility:visible; }

    .modal-box{
      width:100%; max-width:520px; background:var(--panel); border-radius:10px; padding:20px;
      box-shadow:0 18px 40px rgba(0,0,0,0.28);
    }
    .modal-head{ display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
    .modal-head h3{ margin:0; font-size:18px; color:var(--black); }
    .modal-close{ background:transparent; border:0; font-size:24px; cursor:pointer; color:var(--muted) }

    .form-row{ display:grid; grid-template-columns:1fr; gap:10px; margin-top:8px }
    label{ display:block; font-weight:600; font-size:14px; margin-top:8px; color:var(--black) }
    input[type="text"], input[type="email"], input[type="tel"], input[type="password"]{
      width:100%; padding:10px 12px; border-radius:8px; border:1px solid #eee; font-size:15px; margin-top:6px;
    }

    .modal-actions{ display:flex; gap:10px; justify-content:flex-end; margin-top:14px }
    .btn-cancel{ background:#f3f3f3; color:var(--black); border:1px solid #e6e6e6; padding:10px 14px; border-radius:8px; cursor:pointer }
    .btn-save{ background:var(--gold); color:var(--black); padding:10px 14px; border-radius:8px; border:0; cursor:pointer }

    /* small responsive */
    @media (max-width:860px){
      .profile-card{ flex-direction:column; text-align:center; align-items:center }
      .info-row{ flex-direction:column; }
      .modal-box{ margin:0 12px; }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>My Profile</h1>

    <!-- Profile card -->
    <div class="profile-card" role="region" aria-label="User profile">
      <div class="profile-img" aria-hidden="true">
        <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Profile avatar">
      </div>

      <div class="profile-info">
        <p><strong>Name:</strong> <?= $fullName ?></p>
        <p><strong>Email:</strong> <?= $email ?></p>
        <p><strong>Phone:</strong> <?= $phone ?></p>

        <div class="btn-box" role="toolbar" aria-label="Profile actions">
          <button class="btn btn-edit" id="editProfileBtn">Edit Profile</button>
          <button class="btn btn-pass" id="changePassBtn">Change Password</button>
        </div>
      </div>
    </div>

    <!-- Info cards -->
    <div class="info-row" aria-live="polite">
      <div class="card">
        <h2>Rental Summary:</h2>
        <p>• Total Bookings: <?= $totalBookings ?></p>
        <p>• Active Rentals: 2</p>
        <p>• Pending Returns: 1</p>
        <p>• Total Spent: Rs. 7,500</p>
      </div>

      <div class="card">
        <h2>Saved Measurements:</h2>
        <p>M (Standard)</p>
        <p>Last Updated: Oct 2025</p>
        <a class="detail-btn" href="#" role="button">View Detail</a>
      </div>
    </div>

    <!-- Bookings -->
    <div class="bookings">
      <h2 style="margin-top:26px">Recent Bookings</h2>
      <div class="table-wrap" role="table">
        <table aria-label="Recent bookings">
          <thead>
            <tr>
              <th>Booking ID</th>
              <th>Outfit</th>
              <th>Date</th>
              <th>Price</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
      <?php if (empty($recentBookings)): ?>
        <tr>
          <td colspan="6" style="text-align:center;">No recent bookings found.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($recentBookings as $booking): 
          // Function to determine CSS class based on status
          $status_lower = strtolower($booking['status']);
          $status_class = match($status_lower) {
            'upcoming' => 'status-upcoming',
            'active' => 'status-booked',
            'returned' => 'status-returned',
            default => 'status-booked', // Default style for unknown status
          };
          
          // Set link text based on status
          $link_text = ($status_lower == 'returned') ? 'Receipt' : 'View';
        ?>
        <tr>
          <td>#<?= htmlspecialchars($booking['booking_id']) ?></td>
          <td><?= htmlspecialchars($booking['outfit_name'] ?? 'N/A') ?></td>
          <td><?= htmlspecialchars($booking['start_date']) ?></td>
          <td>Rs. <?= number_format(htmlspecialchars($booking['total_price']), 2) ?></td>
          <td><span class="status <?= $status_class ?>"><?= htmlspecialchars($booking['status']) ?></span></td>
          <td><a class="link-btn" href="booking_details.php?id=<?= $booking['booking_id'] ?>"><?= $link_text ?></a></td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
     </tbody>
        </table>
      </div>
    </div>

  </div>

  <!-- EDIT PROFILE MODAL -->
  <div id="editModal" class="modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="editTitle">
    <div class="modal-box" role="document">
      <div class="modal-head">
        <h3 id="editTitle">Edit Profile</h3>
        <button class="modal-close" aria-label="Close edit" data-target="editModal">&times;</button>
      </div>

      <form id="editForm" class="modal-form" onsubmit="return false;">
        <div class="form-row">
          <label for="fullName">Full name</label>
          <input id="fullName" type="text" value="<?= $fullName ?>">

          <label for="email">Email</label>
          <input id="email" type="email" value="<?= $email ?>">

          <label for="phone">Phone</label>
          <input id="phone" type="tel" value="<?= $phone ?>">
        </div>

        <div class="modal-actions">
          <button type="button" class="btn-cancel" data-target="editModal">Cancel</button>
          <button type="button" class="btn-save" id="saveEdit">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- CHANGE PASSWORD MODAL -->
  <div id="passModal" class="modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="passTitle">
    <div class="modal-box">
      <div class="modal-head">
        <h3 id="passTitle">Change Password</h3>
        <button class="modal-close" aria-label="Close change password" data-target="passModal">&times;</button>
      </div>

      <form id="passForm" class="modal-form" onsubmit="return false;">
        <div class="form-row">
          <label for="currentPw">Current password</label>
          <input id="currentPw" type="password" placeholder="Enter current password">

          <label for="newPw">New password</label>
          <input id="newPw" type="password" placeholder="At least 8 characters">

          <label for="confirmPw">Confirm new password</label>
          <input id="confirmPw" type="password" placeholder="Re-type new password">

          <small style="color:var(--muted); margin-top:6px; display:block">Password must be at least 8 characters.</small>
        </div>

        <div class="modal-actions">
          <button type="button" class="btn-cancel" data-target="passModal">Cancel</button>
          <button type="button" class="btn-save" id="savePass">Change</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // helper: open/close modal
    function showModal(id){
      const el = document.getElementById(id);
      if(el) el.classList.add('visible'), el.setAttribute('aria-hidden','false');
    }
    function hideModal(id){
      const el = document.getElementById(id);
      if(el) el.classList.remove('visible'), el.setAttribute('aria-hidden','true');
    }

    document.addEventListener('click', function(e){
      // open edit
      if(e.target.matches('#editProfileBtn')) showModal('editModal');
      // open pass
      if(e.target.matches('#changePassBtn')) showModal('passModal');

      // close by close buttons or cancel
      if(e.target.matches('.modal-close') || e.target.matches('.btn-cancel')){
        const tgt = e.target.getAttribute('data-target');
        if(tgt) hideModal(tgt);
        else {
          // if modal-close without data-target determine parent modal
          const modal = e.target.closest('.modal');
          if(modal) hideModal(modal.id);
        }
      }
    });

    // Save edit (fake)
    document.getElementById('saveEdit').addEventListener('click', function(){
      const name = document.getElementById('fullName').value.trim();
      const email = document.getElementById('email').value.trim();
      const phone = document.getElementById('phone').value.trim();
      if(!name || !email){ alert('Please provide a name and email.'); return; }
      // update UI (front-end only)
      document.querySelector('.profile-info').querySelector('p:nth-child(1)').innerHTML = '<strong>Name:</strong> ' + name;
      document.querySelector('.profile-info').querySelector('p:nth-child(2)').innerHTML = '<strong>Email:</strong> ' + email;
      document.querySelector('.profile-info').querySelector('p:nth-child(3)').innerHTML = '<strong>Phone:</strong> ' + phone;
      hideModal('editModal');
      // subtle success
      setTimeout(()=> alert('Profile saved (demo).'), 150);
    });

    // Change password validation (front-end only)
    document.getElementById('savePass').addEventListener('click', function(){
      const cur = document.getElementById('currentPw').value;
      const nw = document.getElementById('newPw').value;
      const cf = document.getElementById('confirmPw').value;

      if(!cur || !nw || !cf){ alert('Please fill all password fields.'); return; }
      if(nw.length < 8){ alert('New password must be at least 8 characters.'); return; }
      if(nw !== cf){ alert('New password and confirm do not match.'); return; }

      // fake success (in real app send to server)
      hideModal('passModal');
      setTimeout(()=> alert('Password changed (demo).'), 120);
      // clear fields
      document.getElementById('currentPw').value = '';
      document.getElementById('newPw').value = '';
      document.getElementById('confirmPw').value = '';
    });

    // close modal when clicking outside modal content
    document.querySelectorAll('.modal').forEach(mod => {
      mod.addEventListener('click', function(e){
        if(e.target === mod) hideModal(mod.id);
      });
    });

    // close on escape
    document.addEventListener('keydown', function(e){
      if(e.key === 'Escape'){
        document.querySelectorAll('.modal.visible').forEach(m => hideModal(m.id));
      }
    });
  </script>
</body>
</html>
<?php include 'includes/footer.php'; ?>