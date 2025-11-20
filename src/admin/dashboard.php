<?php 
  include $_SERVER['DOCUMENT_ROOT'] . '/FitSphere/includes/headerAdmin.php';


  require_once __DIR__ . '/../../includes/auth/auth_admin.php';

  $user = Auth::user();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>

<body>
    
    <div class="dashboard-container">
        <div class="overlay">
            <h1 class="welcome-text text-center text-light">
                Welcome <span class="text-warning fw-bold">Admin!</span>
            </h1>

                <!-- Stats Cards -->
                <div class="container mt-4">
                    <div class="row justify-content-center">

                        <div class="col-md-2 col-sm-4 mb-3">
                            <div class="card stat-card green text-center p-3">
                                <h5>Total Rental</h5>
                                <h3>60</h3>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card stat-card blue text-center p-3">
                                <h5>Active Rental</h5>
                                <h3>18</h3>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card stat-card red text-center p-3">
                                <h5>Pending Pickup</h5>
                                <h3>6</h3>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-4 mb-3">
                            <div class="card stat-card pink text-center p-3">
                                <h5>Total Users</h5>
                                <h3>100</h3>
                            </div>
                        </div>

                    </div>
                </div>
        </div>
    </div>

<!-- Charts Section -->
<!-- bar chart -->
<div class="container my-4 p-4 border border-primary rounded bg-white shadow-sm">
  <div class="d-flex justify-content-between">
    <h5 class="fw-semibold">Monthly Revenue</h5>
    <span class="fw-bold">2025</span>
  </div>
  <canvas id="myChart1" height="75"></canvas>
</div>

<!-- bottom section -->
<div class="continer my-4">
  <div class="row g-4">

    <div class="col-md-6">
      <div class="p-4 bg-white shadow-sm rounded border border-light d-flex justify-content-between align-items-center chart-section">
        
          <div class="legend-box">
            <h4 class="fw-semibold mb-3">Most Sold Categoreis</h4>
            <div class="category-box"><div class="color-dot" style="background:rgba(255, 99, 133, 1);"></div><span>Wedding Suits</span></div>
            <div class="category-box"><div class="color-dot" style="background:rgba(54, 163, 235, 1);"></div><span>Nilame Suits</span></div>
            <div class="category-box"><div class="color-dot" style="background:rgba(255, 207, 86, 1);"></div><span>Business Suits</span></div>
            <div class="category-box"><div class="color-dot" style="background:rgba(75, 192, 192, 1);"></div><span>Indian Suits</span></div>
            <div class="category-box"><div class="color-dot" style="background:rgba(153, 102, 255, 1);"></div><span>Dinner Suits</span></div>
            <div class="category-box"><div class="color-dot" style="background:rgba(255, 160, 64, 1);"></div><span>Blazers</span></div>
          </div>

          <div class="chart-wrapper">
            <canvas id="myChart2"></canvas>
          </div>
      </div>
    </div>

    <div class="col-md-6">
        <div class="p-4 bg-white shadow-sm rounded border border-light">
          <h6 class="fw-semibold mb-3">Recent Bookings</h6>
          <table class="table table-sm align-middle text-center">
            <thead class="table-warning">
              <tr>
                <th>Booking ID</th>
                <th>Suit</th>
                <th>Start</th>
                <th>End</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr><td>111</td><td>Black Tuxedo</td><td>Oct 12</td><td>Oct 17</td><td>Active</td></tr>
              <tr><td>112</td><td>Navy Suit</td><td>Oct 13</td><td>Oct 15</td><td>Upcoming</td></tr>
              <tr><td>113</td><td>Nilame Suit</td><td>Oct 13</td><td>Oct 12</td><td>Active</td></tr>
              <tr><td>114</td><td>Business Suit</td><td>Oct 14</td><td>Oct 18</td><td>Active</td></tr>
              <tr><td>115</td><td>Dinner Suit</td><td>Oct 15</td><td>Oct 20</td><td>Active</td></tr>
            </tbody>
          </table>
          <a href="#" class="text-end text-muted small mb-0 ">See more →</a>
          
        </div>
      </div>

  </div>
</div>



<!-- Load Chart.js FIRST -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>
</html>

<?php include '../../includes/footerAdmin.php'; ?>