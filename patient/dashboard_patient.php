<?php
session_start();
require_once("../includes/db_connect.php"); // Make sure this sets $conn

// ✅ Extra cache prevention
header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// ✅ Check if patient is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit;
}

$patientId = $_SESSION['patient_id'];

// ✅ Handle emergency alert
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emergency_alert'])) {
    $stmt = $conn->prepare("SELECT assigned_doctor_id FROM patients WHERE id = ?");
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();

    if ($patient && $patient['assigned_doctor_id']) {
        $doctorId = $patient['assigned_doctor_id'];
        $alertMessage = "EMERGENCY: Patient ID $patientId needs immediate assistance!";

        $stmt = $conn->prepare("INSERT INTO alerts (patient_id, alert_type, message) VALUES (?, 'Emergency', ?)");
        $stmt->bind_param("is", $patientId, $alertMessage);
        $stmt->execute();

        $alertSuccess = "Emergency alert sent to your doctor!";
    } else {
        $alertError = "No assigned doctor found!";
    }
}

// ✅ Fetch upcoming medicines
$medicines = [];
$stmt = $conn->prepare("SELECT * FROM medicine_schedule WHERE patient_id = ? AND date >= CURDATE() ORDER BY time ASC");
$stmt->bind_param("i", $patientId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $medicines[] = $row;
}

// ✅ Fetch recent appointments
$appointments = [];
$stmt = $conn->prepare("
    SELECT a.*, u.name AS doctor_name 
    FROM appointments a
    JOIN users u ON a.doctor_id = u.id
    WHERE a.patient_id = ?
    ORDER BY a.appointment_date DESC LIMIT 3
");
$stmt->bind_param("i", $patientId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Patient Dashboard | Sehat Guardian</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    .emergency-btn {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 70px;
      height: 70px;
      border-radius: 50%;
      font-size: 24px;
      animation: pulse 1.5s infinite;
      z-index: 1000;
    }
    @keyframes pulse {
      0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
      70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(220, 53, 69, 0); }
      100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
    .medicine-card {
      transition: all 0.3s;
    }
    .medicine-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active text-white" href="#">
              <i class="fas fa-home me-2"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="add_medicine.php">
              <i class="fas fa-pills me-2"></i> Medicines
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="appointments.php">
              <i class="fas fa-calendar-check me-2"></i> Appointments
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="profile.php">
              <i class="fas fa-user me-2"></i> Profile
            </a>
          </li>
          <!-- ✅ Logout Link -->
          <li class="nav-item">
            <a class="nav-link text-white" href="../logout.php">
              <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      <?php if(isset($alertSuccess)): ?>
        <div class="alert alert-success alert-dismissible fade show">
          <?= htmlspecialchars($alertSuccess) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if(isset($alertError)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
          <?= htmlspecialchars($alertError) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <h2 class="h3 mb-4"><i class="fas fa-user me-2"></i> Welcome Back</h2>

      <!-- Stats Cards -->
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card bg-primary text-white">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-pills me-2"></i> Next Medicine</h5>
              <?php if(count($medicines) > 0): ?>
                <p class="card-text">
                  <?= htmlspecialchars($medicines[0]['name']) ?> 
                  at <?= date("h:i A", strtotime($medicines[0]['time'])) ?>
                </p>
              <?php else: ?>
                <p class="card-text">No medicines scheduled</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-success text-white">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-heartbeat me-2"></i> Last Checkup</h5>
              <p class="card-text">
                <?= count($appointments) > 0 ? 
                  htmlspecialchars($appointments[0]['doctor_name']) . " on " . 
                  date("M d, Y", strtotime($appointments[0]['appointment_date'])) : 
                  "No recent appointments" ?>
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-info text-white">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-bell me-2"></i> Alerts</h5>
              <p class="card-text">All systems normal</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Today's Medicines -->
      <div class="card mb-4">
        <div class="card-header">
          <i class="fas fa-pills me-2"></i> Today's Medicines
        </div>
        <div class="card-body">
          <?php if(count($medicines) > 0): ?>
            <div class="row">
              <?php foreach($medicines as $medicine): ?>
                <div class="col-md-4 mb-3">
                  <div class="card medicine-card">
                    <div class="card-body">
                      <h5 class="card-title"><?= htmlspecialchars($medicine['name']) ?></h5>
                      <p class="card-text">
                        <strong>Dosage:</strong> <?= htmlspecialchars($medicine['dosage']) ?><br>
                        <strong>Time:</strong> <?= date("h:i A", strtotime($medicine['time'])) ?>
                      </p>
                      <button class="btn btn-sm btn-success">
                        <i class="fas fa-check me-1"></i> Mark Taken
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p>No medicines scheduled for today.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Recent Appointments -->
      <div class="card">
        <div class="card-header">
          <i class="fas fa-calendar-alt me-2"></i> Recent Appointments
        </div>
        <div class="card-body">
          <?php if(count($appointments) > 0): ?>
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Doctor</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($appointments as $appointment): ?>
                  <tr>
                    <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                    <td><?= date("M d, Y", strtotime($appointment['appointment_date'])) ?></td>
                    <td><?= date("h:i A", strtotime($appointment['appointment_time'])) ?></td>
                    <td>
                      <span class="badge bg-<?= $appointment['status'] === 'Approved' ? 'success' : 'warning' ?>">
                        <?= ucfirst($appointment['status']) ?>
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <p>No recent appointments found.</p>
          <?php endif; ?>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Emergency Button -->
<form method="POST" action="dashboard_patient.php">
  <button type="submit" name="emergency_alert" class="btn btn-danger emergency-btn" title="Emergency Alert">
    <i class="fas fa-bell"></i>
  </button>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
