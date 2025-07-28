<?php
session_start();
require_once("../includes/db_connect.php");

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];
$today = date('Y-m-d');

// ✅ Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bp = trim($_POST['bp']);
    $sugar = trim($_POST['sugar']);
    $pulse = trim($_POST['pulse']);
    $temperature = trim($_POST['temperature']);
    $sleep_hours = floatval($_POST['sleep_hours']);
    $water_glasses = intval($_POST['water_glasses']);
    $mood = trim($_POST['mood']);
    $symptoms = trim($_POST['symptoms']);

    // Check if today's log exists
    $stmt = $conn->prepare("SELECT id FROM health_logs WHERE patient_id = ? AND log_date = ?");
    $stmt->bind_param("is", $patient_id, $today);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE health_logs SET bp=?, sugar=?, pulse=?, temperature=?, sleep_hours=?, water_glasses=?, mood=?, symptoms=? WHERE patient_id=? AND log_date=?");
        $stmt->bind_param("ssssdiiss", $bp, $sugar, $pulse, $temperature, $sleep_hours, $water_glasses, $mood, $symptoms, $patient_id, $today);
    } else {
        $stmt = $conn->prepare("INSERT INTO health_logs (patient_id, log_date, bp, sugar, pulse, temperature, sleep_hours, water_glasses, mood, symptoms) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssdiis", $patient_id, $today, $bp, $sugar, $pulse, $temperature, $sleep_hours, $water_glasses, $mood, $symptoms);
    }
    $stmt->execute();
}

// ✅ Get today's log
$stmt = $conn->prepare("SELECT * FROM health_logs WHERE patient_id = ? AND log_date = ?");
$stmt->bind_param("is", $patient_id, $today);
$stmt->execute();
$today_log = $stmt->get_result()->fetch_assoc();

// ✅ Get recent logs
$stmt = $conn->prepare("SELECT * FROM health_logs WHERE patient_id = ? ORDER BY log_date DESC LIMIT 7");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$recent_logs = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Health Log | Sehat Guardian</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h2 class="mb-4">🩺 Daily Health Log</h2>

  <form method="POST" class="row g-4 mb-5">
    <!-- Vitals -->
    <div class="col-12">
      <h5>📏 Vitals</h5>
      <div class="row g-3">
        <div class="col-md-3">
          <label>Blood Pressure</label>
          <input type="text" name="bp" class="form-control" placeholder="120/80" value="<?= htmlspecialchars($today_log['bp'] ?? '') ?>" required>
        </div>
        <div class="col-md-3">
          <label>Blood Sugar (mg/dL)</label>
          <input type="text" name="sugar" class="form-control" placeholder="e.g. 95" value="<?= htmlspecialchars($today_log['sugar'] ?? '') ?>" required>
        </div>
        <div class="col-md-3">
          <label>Pulse (bpm)</label>
          <input type="text" name="pulse" class="form-control" placeholder="e.g. 75" value="<?= htmlspecialchars($today_log['pulse'] ?? '') ?>" required>
        </div>
        <div class="col-md-3">
          <label>Temperature (°C)</label>
          <input type="text" name="temperature" class="form-control" placeholder="e.g. 37.0" value="<?= htmlspecialchars($today_log['temperature'] ?? '') ?>" required>
        </div>
      </div>
    </div>

    <!-- Sleep & Water -->
    <div class="col-12">
      <h5>💧 Sleep & Hydration</h5>
      <div class="row g-3">
        <div class="col-md-3">
          <label>Sleep Hours</label>
          <input type="number" step="0.1" name="sleep_hours" class="form-control" placeholder="e.g. 7.5" value="<?= htmlspecialchars($today_log['sleep_hours'] ?? '') ?>" required>
        </div>
        <div class="col-md-3">
          <label>Water (Glasses)</label>
          <input type="number" name="water_glasses" class="form-control" placeholder="e.g. 8" value="<?= htmlspecialchars($today_log['water_glasses'] ?? '') ?>" required>
        </div>
      </div>
    </div>

    <!-- Mood Notes -->
    <div class="col-12">
      <h5>📝 Mood & Symptoms</h5>
      <div class="mb-3">
        <label>Mood Notes</label>
        <input type="text" name="mood" class="form-control" placeholder="How are you feeling?" value="<?= htmlspecialchars($today_log['mood'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <label>Symptoms</label>
        <textarea name="symptoms" class="form-control" rows="3"><?= htmlspecialchars($today_log['symptoms'] ?? '') ?></textarea>
      </div>
    </div>

    <div class="col-12">
      <button type="submit" class="btn btn-primary">💾 Save Log</button>
      <a href="dashboard_patient.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
  </form>

  <h4>📅 Recent Logs</h4>
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Date</th><th>BP</th><th>Sugar</th><th>Pulse</th><th>Temp</th><th>Sleep</th><th>Water</th><th>Mood</th><th>Symptoms</th>
        </tr>
      </thead>
      <tbody>
        <?php while($log = $recent_logs->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($log['log_date']) ?></td>
            <td><?= htmlspecialchars($log['bp']) ?></td>
            <td><?= htmlspecialchars($log['sugar']) ?></td>
            <td><?= htmlspecialchars($log['pulse']) ?></td>
            <td><?= htmlspecialchars($log['temperature']) ?></td>
            <td><?= htmlspecialchars($log['sleep_hours']) ?></td>
            <td><?= htmlspecialchars($log['water_glasses']) ?></td>
            <td><?= htmlspecialchars($log['mood']) ?></td>
            <td><?= htmlspecialchars($log['symptoms']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</body>
</html>
