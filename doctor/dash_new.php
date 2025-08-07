<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Doctor Dashboard - Sehat Guardian</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      display: flex;
      background-color: #e8f8f9;
    }

    .sidebar {
      width: 250px;
      height: 100vh;
      background: #036c7c;
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
      background-color: #028f9d;
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
      background-color: #03a3b5;
      color: white;
      padding: 20px;
      border-radius: 12px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .welcome {
      font-size: 20px;
    }

    #datetime {
      background-color: white;
      color: black;
      padding: 8px 12px;
      border-radius: 8px;
      font-weight: bold;
      font-family: monospace;
    }

    .stats {
      display: flex;
      gap: 20px;
      margin-top: 30px;
      flex-wrap: wrap;
    }

    .card {
      flex: 1;
      min-width: 200px;
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      text-align: center;
    }

    .card h3 {
      font-size: 28px;
      color: #036c7c;
    }

    .card p {
      margin-top: 6px;
    }

    .logout {
      background: red;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      margin-top: 30px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Doctor</h2>
    <p style="text-align:center;">Dr. Maya George</p>

    <a href="#"><i class="fas fa-calendar-check"></i> View Appointments</a>
    <a href="#"><i class="fas fa-users"></i> View Patients</a>
    <a href="#"><i class="fas fa-pills"></i> Manage Prescriptions</a>
    <a href="#"><i class="fas fa-file-medical-alt"></i> Medical Records</a>
    <a href="#"><i class="fas fa-clock"></i> Manage Schedule</a>
    <a href="#"><i class="fas fa-chart-line"></i> View Reports</a>
    <a href="view_alerts.php"><i class="fas fa-bell"></i> View Alerts</a>
    <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <div class="main">
    <div class="header">
      <div class="welcome">
        👨‍⚕️ Welcome, Dr. Maya George<br/>
        <small>Ready to provide exceptional healthcare today!</small>
      </div>
      <div id="datetime"></div>
    </div>

    <div class="stats">
      <div class="card">
        <h3>12</h3>
        <p>Today's Appointments</p>
      </div>
      <div class="card">
        <h3>45</h3>
        <p>Total Patients</p>
      </div>
      <div class="card">
        <h3>8</h3>
        <p>Pending Reviews</p>
      </div>
      <div class="card">
        <h3>3</h3>
        <p>Urgent Cases</p>
      </div>
    </div>

    <button class="logout">🔴 Logout</button>
  </div>

  <script>
    function updateDateTime() {
      const now = new Date();
      const options = {
        year: 'numeric', month: '2-digit', day: '2-digit',
        hour: '2-digit', minute: '2-digit', second: '2-digit',
        hour12: false,
      };
      const formatted = now.toLocaleString('en-GB', options).replace(',', '');
      document.getElementById('datetime').textContent = formatted;
    }

    setInterval(updateDateTime, 1000);
    updateDateTime(); // Initial call
  </script>
</body>
</html>
