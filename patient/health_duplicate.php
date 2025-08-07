<?php
session_start();
require_once("../includes/db_connect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$success_message = $error_message = "";

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_SESSION['user_id'];
    $today = date('Y-m-d');
    $bp = trim($_POST['bp']);
    $sugar = trim($_POST['sugar']);
    $pulse = trim($_POST['pulse']);
    $temperature = trim($_POST['temperature']);
    $sleep_hours = floatval($_POST['sleep_hours']);
    $water_glasses = intval($_POST['water_glasses']);
    $mood = trim($_POST['mood']);
    $symptoms = trim($_POST['symptoms']);

    $stmt = $conn->prepare("INSERT INTO health_logs (patient_id, log_date, bp, sugar, pulse, temperature, sleep_hours, water_glasses, mood, symptoms)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        $error_message = "Prepare failed: " . $conn->error;
    } else {
        $stmt->bind_param("isssssdiss", $patient_id, $today, $bp, $sugar, $pulse, $temperature, $sleep_hours, $water_glasses, $mood, $symptoms);

        if ($stmt->execute()) {
            $success_message = "Health log submitted successfully!";
        } else {
            $error_message = "Execution failed: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch health log history
$logs = [];
$patient_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM health_logs WHERE patient_id = $patient_id ORDER BY log_date DESC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Health Log</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e6f7f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .header {
            background: linear-gradient(90deg, #4db6ac, #00897b);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .form-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .form-card label::before {
            content: '🩺 ';
        }

        .form-card label[for="sugar"]::before { content: '🧁 '; }
        .form-card label[for="pulse"]::before { content: '❤️ '; }
        .form-card label[for="temperature"]::before { content: '🌡️ '; }
        .form-card label[for="sleep_hours"]::before { content: '😴 '; }
        .form-card label[for="water_glasses"]::before { content: '💧 '; }
        .form-card label[for="mood"]::before { content: '😊 '; }
        .form-card label[for="symptoms"]::before { content: '📋 '; }

        .summary-boxes {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .summary-box {
            flex: 1;
            background: #b2dfdb;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .table thead {
            background-color: #00897b;
            color: white;
        }

        .table td, .table th {
            vertical-align: middle;
        }

        .abnormal {
            background-color: #ffe0e0 !important;
        }

        .btn-teal {
            background-color: #00897b;
            color: white;
        }

        .btn-teal:hover {
            background-color: #00796b;
        }
    </style>
</head>
<body class="container py-4">

    <div class="header d-flex justify-content-between align-items-center">
        <h2>🌿 Daily Health Log</h2>
        <a href="dashboard_patient.php" class="btn btn-light">← Back to Dashboard</a>
    </div>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php elseif ($error_message): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Summary -->
    <div class="summary-boxes">
        <div class="summary-box">📝 Total Logs: <br> <?php echo count($logs); ?></div>
        <div class="summary-box">⏱️ Avg Sleep: <br> <?php echo round(array_sum(array_column($logs, 'sleep_hours')) / max(1, count($logs)), 1); ?> hrs</div>
        <div class="summary-box">💧 Avg Water: <br> <?php echo round(array_sum(array_column($logs, 'water_glasses')) / max(1, count($logs))); ?> glasses</div>
    </div>

    <!-- Form -->
    <div class="form-card mb-5">
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="bp" class="form-label">Blood Pressure</label>
                    <input type="text" name="bp" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="sugar" class="form-label">Sugar Level</label>
                    <input type="text" name="sugar" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="pulse" class="form-label">Pulse Rate</label>
                    <input type="text" name="pulse" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="temperature" class="form-label">Temperature (°F)</label>
                    <input type="text" name="temperature" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="sleep_hours" class="form-label">Sleep Hours</label>
                    <input type="number" step="0.1" name="sleep_hours" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="water_glasses" class="form-label">Water Intake (glasses)</label>
                    <input type="number" name="water_glasses" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="mood" class="form-label">Mood</label>
                    <select name="mood" class="form-select" required>
                        <option value="">Select mood</option>
                        <option value="Happy">😊 Happy</option>
                        <option value="Okay">🙂 Okay</option>
                        <option value="Sad">😢 Sad</option>
                        <option value="Angry">😠 Angry</option>
                        <option value="Tired">🥱 Tired</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label for="symptoms" class="form-label">Symptoms or Notes</label>
                    <textarea name="symptoms" rows="2" class="form-control"></textarea>
                </div>
            </div>
            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-teal px-4">➕ Save Log</button>
            </div>
        </form>
    </div>

    <!-- Log History -->
    <div class="form-card">
        <h4 class="mb-3">📊 Your Health History</h4>
        <?php if (!empty($logs)): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>📅 Date</th>
                            <th>🩺 BP</th>
                            <th>🧁 Sugar</th>
                            <th>❤️ Pulse</th>
                            <th>🌡️ Temp</th>
                            <th>😴 Sleep</th>
                            <th>💧 Water</th>
                            <th>😊 Mood</th>
                            <th>📋 Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr class="<?php echo ($log['sleep_hours'] < 5 || $log['water_glasses'] < 5) ? 'abnormal' : ''; ?>">
                                <td><?php echo htmlspecialchars(date('D, M j, Y', strtotime($log['log_date']))); ?></td>
                                <td><?php echo htmlspecialchars($log['bp']); ?></td>
                                <td><?php echo htmlspecialchars($log['sugar']); ?></td>
                                <td><?php echo htmlspecialchars($log['pulse']); ?></td>
                                <td><?php echo htmlspecialchars($log['temperature']); ?></td>
                                <td><?php echo htmlspecialchars($log['sleep_hours']); ?>h</td>
                                <td><?php echo htmlspecialchars($log['water_glasses']); ?></td>
                                <td><?php echo htmlspecialchars($log['mood']); ?></td>
                                <td><?php echo htmlspecialchars($log['symptoms']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No logs found.</p>
        <?php endif; ?>
    </div>

</body>
</html>
