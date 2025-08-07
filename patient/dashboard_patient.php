<?php
session_start();
include '../includes/db_connect.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../users/patient_portal.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Mark medicine as taken if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['taken_id'])) {
    $taken_id = intval($_POST['taken_id']);
    $update_sql = "UPDATE medicine_schedule SET status = 'Taken' WHERE id = ? AND patient_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $taken_id, $user_id);
    $stmt->execute();
}

// ✅ Fetch patient info
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// ✅ Fetch pending medicines
$today = date('Y-m-d');
$sql = "SELECT * FROM medicine_schedule 
        WHERE patient_id = ? 
          AND from_date <= ? 
          AND to_date >= ? 
          AND status = 'Pending' 
          AND TIME(time) <= CURTIME()";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $user_id, $today, $today);
$stmt->execute();
$medicines = $stmt->get_result();

// ✅ Fetch recent appointments
$appointments = [];
$appointment_sql = "SELECT u.name AS doctor_name, a.appointment_date, a.appointment_time, a.status 
                    FROM appointments a
                    JOIN doctors d ON a.doctor_id = d.id
                    JOIN users u ON d.user_id = u.id
                    WHERE a.patient_id = ?
                    ORDER BY a.appointment_date DESC
                    LIMIT 5";
$stmt = $conn->prepare($appointment_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}
?>

<?php if (isset($_GET['alert_sent']) && $_GET['alert_sent'] == 1): ?>
<script>
    alert("🚨 Emergency alert has been sent to your doctor and admin!");
</script>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Patient Dashboard - Sehat Guardian</title>
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body { font-family: 'Segoe UI', sans-serif; background-color: #f4f4f4; }
    .sidebar { height: 100vh; background-color: #00416A; color: white; }
    .sidebar a { color: white; text-decoration: none; display: block; padding: 10px; }
    .sidebar a:hover { background-color: #002c4a; }
    .card { border: none; border-radius: 15px; }
    .greeting { font-size: 1.4rem; }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <nav class="col-md-3 col-lg-2 d-md-block sidebar py-4">
      <div class="text-center mb-4">
        <h3>Sehat Guardian</h3>
      </div>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link text-white" href="dashboard_patient.php"><i class="fas fa-home me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="add_medicine.php"><i class="fas fa-pills me-2"></i> Medicine Reminder</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="book_appointment.php"><i class="fas fa-calendar-check me-2"></i> Appointments</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="checklist.php"><i class="fas fa-check-square me-2"></i> Daily Checklist</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="health_log.php"><i class="fas fa-notes-medical me-2"></i> Health Log</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="alerts.php"><i class="fas fa-bell me-2"></i> Alerts</a></li>
        <li class="nav-item mt-3"><a class="nav-link text-danger" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      <h2 class="mb-4 greeting">👋 Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
      <h5>Current Time: <span id="live-time"></span></h5>

      <!-- Medicine Reminder -->
<?php
// Refetch medicines after submission if POST is set
$medicines->data_seek(0); // reset pointer
$showedAny = false;
?>
<div class="alert alert-warning" id="medicine-section">
  <strong>💊 Medicine Reminder:</strong>
  <ul id="medicine-list">
    <?php while ($row = $medicines->fetch_assoc()): ?>
      <?php
        $isTaken = ($row['status'] === 'Taken');
        $medicineTime = strtotime($row['time']);
        $currentTime = strtotime(date("H:i:s"));
      ?>
      <?php if (!$isTaken && $currentTime >= $medicineTime): $showedAny = true; ?>
        <li data-time="<?= $row['time'] ?>" data-id="<?= $row['id'] ?>" class="medicine-item">
          <strong><?= htmlspecialchars($row['name']) ?></strong> at <?= date("h:i A", strtotime($row['time'])) ?>
          <form method="POST" class="d-inline medicine-form" data-id="<?= $row['id'] ?>">

            <input type="hidden" name="taken_id" value="<?= $row['id'] ?>">
            <button type="submit" class="btn btn-sm btn-success ms-2">Mark as Taken</button>
          </form>
        </li>
      <?php endif; ?>
    <?php endwhile; ?>
  </ul>

  <?php if (!$showedAny): ?>
    <div class="alert alert-success mt-3" id="motivational-msg">
      ✅ Well done! You’ve taken all your medicines on time. Stay healthy! 💪
    </div>
  <?php endif; ?>
</div>


      <h2 class="h3 mb-4"><i class="fas fa-user me-2"></i> Welcome Back</h2>

      <!-- Stats Cards -->
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card bg-primary text-white shadow">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-pills me-2"></i> Medicines</h5>
              <p class="card-text">Check your medicine schedule.</p>
              <a href="add_medicine.php" class="btn btn-light btn-sm">View Now</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card bg-success text-white shadow">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-calendar-check me-2"></i> Appointments</h5>
              <p class="card-text">View and manage appointments.</p>
              <a href="book_appointment.php" class="btn btn-light btn-sm">View Now</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card bg-warning text-dark shadow">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-notes-medical me-2"></i> Health Log</h5>
              <p class="card-text">Keep your vitals updated daily.</p>
              <a href="health_log.php" class="btn btn-dark btn-sm">Update Now</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Emergency Alert -->
      <div class="alert alert-danger d-flex justify-content-between align-items-center" role="alert">
        <div>
          <strong>Need Help?</strong> If you're not feeling well, click the red button to send an emergency alert.
        </div>
        <form action="send_alert.php" method="POST">
          <input type="hidden" name="alert_type" value="not_well">
          <button type="submit" class="btn btn-danger">I'm not well</button>
        </form>
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

<!-- JS Scripts -->
<script>
  // 🔁 Reload if cached
  window.addEventListener("pageshow", function (event) {
    if (event.persisted) window.location.reload();
  });

  // 🕒 Live Clock
  function updateLiveTime() {
    const now = new Date();
    document.getElementById("live-time").textContent = now.toLocaleTimeString();
  }
  setInterval(updateLiveTime, 1000);
  updateLiveTime();

  // 💊 Reminder Pop-up Logic
  const medicineItems = document.querySelectorAll('.medicine-item');
  const popCounts = {};

  function checkReminders() {
    const now = new Date();
    const currentTime = now.getHours() * 60 + now.getMinutes();

    medicineItems.forEach(item => {
      const scheduledTime = item.dataset.time;
      const [hour, minute] = scheduledTime.split(':');
      const medTime = parseInt(hour) * 60 + parseInt(minute);
      const medId = item.dataset.id;

      const button = item.querySelector('button');
      const alreadyTaken = button.disabled;

      if (!alreadyTaken && currentTime >= medTime) {
        popCounts[medId] = popCounts[medId] || 0;

        if (popCounts[medId] < 3) {
          alert("⏰ Reminder: Please take your medicine scheduled at " + scheduledTime);
          popCounts[medId]++;
        }
      }
    });
  }

  setInterval(checkReminders, 5 * 60 * 1000); // every 5 min
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Handle Mark as Taken via AJAX
  document.querySelectorAll('.medicine-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();

      const medId = this.dataset.id;
      const liItem = this.closest('li');

      fetch(window.location.href, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ taken_id: medId })
      })
      .then(response => response.ok ? response.text() : Promise.reject())
      .then(() => {
        liItem.remove(); // Remove medicine from list

        // Check if any medicines are left
        if (document.querySelectorAll('.medicine-item').length === 0) {
          document.getElementById('medicine-list').innerHTML = '';
          document.getElementById('medicine-section').innerHTML = `
            <div class="alert alert-success mt-3" id="motivational-msg">
              ✅ Well done! You’ve taken all your medicines on time. Stay healthy! 💪
            </div>
          `;
        }
      })
      .catch(() => alert('❌ Failed to update status. Please try again.'));
    });
  });
</script>

</body>
</html>
