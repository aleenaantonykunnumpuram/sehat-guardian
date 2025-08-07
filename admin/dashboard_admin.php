<?php

session_start();

// OPTIONAL: If you need DB for admin details, require it:
// require_once("../includes/db_connect.php");

// ✅ Prevent browser caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// ✅ Check if logged in as Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location:login.php");
    exit();
}

// ✅ Get admin name from session, fallback to default
$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard - Sehat Guardian</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      display: flex;
      background-color: #f0f4f8;
    }

    .sidebar {
      width: 250px;
      height: 100vh;
      background: #0B2B4C;
      color: white;
      padding: 20px;
      position: fixed;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      padding: 12px 20px;
      margin: 8px 0;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      transition: 0.3s;
    }

    .sidebar a:hover {
      background-color: #1B4D72;
    }

    .sidebar a i {
      margin-right: 10px;
    }

    .main {
      margin-left: 250px;
      padding: 30px;
      flex: 1;
    }

    .header {
      background-color: #0B2B4C;
      color: white;
      padding: 20px;
      border-radius: 12px;
    }

    .alert-box {
      margin-top: 20px;
      background-color: #fff3cd;
      padding: 15px;
      border-left: 8px solid #ffa000;
      border-radius: 8px;
      font-weight: 500;
    }

    .action-buttons {
      display: flex;
      gap: 15px;
      margin: 20px 0;
    }

    .action-buttons button {
      padding: 10px 20px;
      background-color: #11698E;
      border: none;
      color: white;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .action-buttons button:hover {
      background-color: #0A3A5F;
    }

    .cards {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }

    .card {
      flex: 1;
      min-width: 220px;
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .card h3 {
      font-size: 32px;
      color: #0B2B4C;
    }

    .card p {
      font-size: 14px;
      margin-top: 6px;
      color: #333;
    }

    .card small {
      display: block;
      margin-top: 5px;
      color: green;
    }

    .lower-section {
      display: flex;
      gap: 20px;
      margin-top: 30px;
    }

    .lower-section .card {
      flex: 1;
    }
  </style>

</head>
<body>
  <div class="sidebar">
  <h2>Admin</h2>
  <p style="text-align:center; font-size: 14px;">System Admin</p>

  <a href="dash_admin.php"><i class="fas fa-home"></i> Dashboard</a>
  <a href="manage_doctors.php"><i class="fas fa-user-md"></i> Manage Doctors</a>
  <a href="manage_patients.php"><i class="fas fa-users"></i> View Patients</a>
  <a href="appointment_list.php"><i class="fas fa-calendar"></i> Appointments</a>
  <a href="view_alert.php"><i class="fas fa-envelope"></i> Alerts</a>
  <a href="dashboard_admin_report.php"><i class="fas fa-chart-bar"></i> Reports</a>
  <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
  <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>


  <div class="main">
    <div class="header">
      <h2>Good Morning, Admin</h2>
      <p id="datetime"></p>

    </div>

    <div class="alert-box">
      <i class="fas fa-exclamation-triangle"></i> System Alert: 3 patients have missed their medication reminders today. Please review and take action.
    </div>

    <div class="action-buttons">
      <button><i class="fas fa-user-plus"></i> Add Doctor</button>
      <button><i class="fas fa-calendar-plus"></i> Schedule Appointment</button>
      <button><i class="fas fa-bell"></i> Send Reminder</button>
      <button><i class="fas fa-file-alt"></i> Generate Report</button>
    </div>

    <div class="cards">
      <div class="card">
        <h3>24</h3>
        <p>Active Patients</p>
        <small>+2 this week</small>
      </div>
      <div class="card">
        <h3>8</h3>
        <p>Doctors</p>
        <small>+1 this month</small>
      </div>
      <div class="card">
        <h3>12</h3>
        <p>Appointments Today</p>
        <small>+3 pending</small>
      </div>
      <div class="card">
        <h3>89</h3>
        <p>Medication Compliance</p>
        <small style="color: red;">-2% this week</small>
      </div>
    </div>

    <div class="lower-section">
      <div class="card">
        <h4>Recent Activities</h4>
        <p><i class="fas fa-calendar-plus"></i> New appointment scheduled - 2 hrs ago</p>
        <p><i class="fas fa-pills"></i> Medication reminder sent - 4 hrs ago</p>
      </div>
      <div class="card">
        <h4>Health Trends</h4>
        <p>Health trends and analytics will be displayed here.</p>
      </div>
      <div class="card">
        <h4>Upcoming Appointments</h4>
        <p><i class="fas fa-user-md"></i> Dr. Priya Mehta - Tomorrow 10:30 AM</p>
        <p><i class="fas fa-user-md"></i> Dr. Raj Kumar - Jul 11 - 2:00 PM</p>
      </div>
    </div>
  </div>
  <script>
  function updateDateTime() {
    const now = new Date();

    // Format: Wednesday, August 6, 2025 at 01:09 PM
    const options = {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      hour12: true,
    };

    const formatted = now.toLocaleString('en-US', {
  weekday: 'long',
  year: 'numeric',
  month: 'long',
  day: 'numeric',
  hour: '2-digit',
  minute: '2-digit',
  hour12: true
});

    
    document.getElementById('datetime').textContent = formatted;
  }

  // Call once immediately
  updateDateTime();

  // Update every second
  setInterval(updateDateTime, 1000);
</script>

</body>
</html>
