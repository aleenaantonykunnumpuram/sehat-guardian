<?php
session_start();
include '../includes/db_connect.php';

// Only allow admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'unseen';

switch ($filter) {
    case 'all':
        $sql = "SELECT alerts.id, users.name, alerts.created_at, alerts.message, alerts.seen_by_admin 
                FROM alerts
                JOIN users ON alerts.patient_id = users.id
                ORDER BY alerts.created_at DESC";
        break;
    case 'seen':
        $sql = "SELECT alerts.id, users.name, alerts.created_at, alerts.message, alerts.seen_by_admin 
                FROM alerts
                JOIN users ON alerts.patient_id = users.id
                WHERE alerts.seen_by_admin = 1
                ORDER BY alerts.created_at DESC";
        break;
    default: // unseen
        $sql = "SELECT alerts.id, users.name, alerts.created_at, alerts.message, alerts.seen_by_admin 
                FROM alerts
                JOIN users ON alerts.patient_id = users.id
                WHERE alerts.seen_by_admin = 0
                ORDER BY alerts.created_at DESC";
        break;
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - View Alerts</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f2f2f2; }
        h2 { color: #333; }
        a.button {
            display: inline-block;
            padding: 8px 15px;
            background: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        a.button:hover { background: #0056b3; }
        ul { list-style: none; padding: 0; }
        li {
            background: white;
            padding: 10px;
            margin: 8px 0;
            border-left: 5px solid #ff4d4d;
        }
        .seen { border-left-color: #28a745; }
        .btn-submit {
            padding: 10px 20px;
            background: green;
            color: white;
            border: none;
            margin-top: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h2>📢 Emergency Alerts (Admin View)</h2>

    <!-- Filter Buttons -->
    <div>
        <a href="?filter=unseen" class="button">🔍 Unseen</a>
        <a href="?filter=seen" class="button">✅ Seen</a>
        <a href="?filter=all" class="button">📋 All</a>
        <a href="dashboard_admin.php" class="button">🏠 Back to Dashboard</a>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <form method="POST" action="mark_alert_seen.php">
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="<?= $row['seen_by_admin'] ? 'seen' : '' ?>">
                        <?php if (!$row['seen_by_admin']): ?>
                            <input type="checkbox" name="seen_alerts[]" value="<?= $row['id'] ?>"> 
                        <?php endif; ?>
                        <strong><?= $row['name'] ?></strong> - <?= $row['created_at'] ?><br>
                        <?= htmlspecialchars($row['message']) ?>
                    </li>
                <?php endwhile; ?>
            </ul>
            <?php if ($filter !== 'seen'): ?>
                <button type="submit" class="btn-submit">Mark Selected as Seen</button>
            <?php endif; ?>
        </form>
    <?php else: ?>
        <p>No alerts found.</p>
    <?php endif; ?>
</body>
</html>
