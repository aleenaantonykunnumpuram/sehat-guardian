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
    <title>Admin Dashboard - Sehat Guardian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- ✅ Prevent Back cache using modern fix -->
    <script>
        window.addEventListener("pageshow", function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
    <style>
        /* ...existing CSS styles from your file... */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #006064 0%, #00838f 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px;
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
            padding: 10px;
        }
        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #006064, #00838f);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        .logo-text {
            font-size: 22px;
            font-weight: 700;
            color: #006064;
        }
        .user-profile {
            background: #f0f9ff;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        .user-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #006064, #00838f);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: 600;
            margin: 0 auto 10px;
        }
        .user-name {
            font-size: 18px;
            font-weight: 600;
            color: #006064;
            margin-bottom: 5px;
        }
        .user-role {
            font-size: 14px;
            color: #00838f;
        }
        .nav-menu {
            list-style: none;
            flex: 1;
            margin-bottom: 20px;
        }
        .nav-item {
            margin-bottom: 8px;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            color: #555;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .nav-link:hover, .nav-link.active {
            background: linear-gradient(135deg, #006064, #00838f);
            color: white;
            transform: translateX(5px);
        }
        .nav-icon {
            font-size: 20px;
        }
        .logout-section {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .emergency-btn {
            background: linear-gradient(135deg, #d32f2f, #f44336);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .emergency-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(211, 47, 47, 0.3);
        }
        .logout-btn {
            background: transparent;
            color: #666;
            border: 1px solid #ddd;
            padding: 12px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .logout-btn:hover {
            background: #f5f5f5;
            border-color: #006064;
            color: #006064;
        }
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .greeting {
            color: white;
        }
        .greeting h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .greeting p {
            font-size: 16px;
            opacity: 0.9;
        }
        .header-actions {
            display: flex;
            gap: 15px;
        }
        .notification-btn {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        .notification-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            background: #f44336;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #006064;
        }
        .card-action {
            color: #00838f;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .card-action:hover {
            color: #006064;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        .stat-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #006064;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .stat-change {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 20px;
            font-weight: 500;
        }
        .stat-change.positive {
            background: #e8f5e8;
            color: #4caf50;
        }
        .stat-change.negative {
            background: #ffebee;
            color: #f44336;
        }
        .activity-list {
            max-height: 400px;
            overflow-y: auto;
        }
        .activity-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .activity-item:last-child {
            border-bottom: none;
        }
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
        }
        .activity-icon.appointment {
            background: linear-gradient(135deg, #006064, #00838f);
        }
        .activity-icon.medication {
            background: linear-gradient(135deg, #ff9800, #f57c00);
        }
        .activity-icon.emergency {
            background: linear-gradient(135deg, #f44336, #d32f2f);
        }
        .activity-content {
            flex: 1;
        }
        .activity-title {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
        }
        .activity-time {
            font-size: 12px;
            color: #666;
        }
        .alert {
            background: linear-gradient(135deg, #fff3e0, #ffcc02);
            border: 1px solid #ffb300;
            color: #e65100;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-icon {
            font-size: 18px;
        }
        .quick-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        .quick-action-btn {
            background: linear-gradient(135deg, #006064, #00838f);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 96, 100, 0.3);
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            .header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .quick-actions {
                justify-content: center;
            }
        }
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #006064;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #00838f;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="logo">
                <div class="logo-icon">🏥</div>
                <div class="logo-text">Sehat Guardian</div>
            </div>
            <div class="user-profile">
                <div class="user-avatar">A</div>
                <div class="user-name"><?php echo htmlspecialchars($admin_name); ?></div>
                <div class="user-role">System Admin</div>
            </div>
            <nav class="nav-menu">
                <li class="nav-item">
                    <a href="#" class="nav-link active">
                        <span class="nav-icon">📊</span>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manage_doctors.php" class="nav-link">
                        <span class="nav-icon">👨‍⚕️</span>
                        Manage Doctors
                    </a>
                </li>
                <li class="nav-item">
                    <a href="view_patients.php" class="nav-link">
                        <span class="nav-icon">👥</span>
                        View Patients
                    </a>
                </li>
                <li class="nav-item">
                    <a href="appointments.php" class="nav-link">
                        <span class="nav-icon">📅</span>
                        Appointments
                    </a>
                </li>
                <li class="nav-item">
                    <a href="messages.php" class="nav-link">
                        <span class="nav-icon">💬</span>
                        Messages
                    </a>
                </li>
                <li class="nav-item">
                    <a href="reports.php" class="nav-link">
                        <span class="nav-icon">📋</span>
                        Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a href="settings.php" class="nav-link">
                        <span class="nav-icon">⚙️</span>
                        Settings
                    </a>
                </li>
            </nav>
            <div class="logout-section">
                <button class="emergency-btn" onclick="handleEmergency()">
                    <span>🚨</span>
                    Emergency Contact
                </button>
                <button class="logout-btn" onclick="confirmLogout()">
                    <span>🚪</span>
                    Log Out
                </button>
            </div>
        </div>
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <div class="greeting">
                    <h1>Good Morning, <?php echo htmlspecialchars($admin_name); ?></h1>
                    <p id="current-date"></p>
                </div>
                <div class="header-actions">
                    <button class="notification-btn" onclick="showNotifications()">
                        🔔
                        <span class="notification-badge">3</span>
                    </button>
                    <button class="notification-btn" onclick="showHelp()">
                        ❓
                    </button>
                </div>
            </div>
            <div class="alert">
                <span class="alert-icon">⚠️</span>
                <strong>System Alert:</strong> 3 patients have missed their medication reminders today. Please review and take action.
            </div>
            <div class="quick-actions">
                <button class="quick-action-btn" onclick="addNewDoctor()">
                    <span>👨‍⚕️</span>
                    Add Doctor
                </button>
                <button class="quick-action-btn" onclick="scheduleAppointment()">
                    <span>📅</span>
                    Schedule Appointment
                </button>
                <button class="quick-action-btn" onclick="sendReminder()">
                    <span>🔔</span>
                    Send Reminder
                </button>
                <button class="quick-action-btn" onclick="generateReport()">
                    <span>📊</span>
                    Generate Report
                </button>
            </div>
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number">24</div>
                    <div class="stat-label">Active Patients</div>
                    <div class="stat-change positive">+2 this week</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">👨‍⚕️</div>
                    <div class="stat-number">8</div>
                    <div class="stat-label">Doctors</div>
                    <div class="stat-change positive">+1 this month</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📅</div>
                    <div class="stat-number">12</div>
                    <div class="stat-label">Appointments Today</div>
                    <div class="stat-change positive">+3 pending</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">💊</div>
                    <div class="stat-number">89%</div>
                    <div class="stat-label">Medication Compliance</div>
                    <div class="stat-change negative">-2% this week</div>
                </div>
            </div>
            <!-- Dashboard Grid -->
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Activities</h3>
                        <a href="#" class="card-action">View All →</a>
                    </div>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon appointment">📅</div>
                            <div class="activity-content">
                                <div class="activity-title">New appointment scheduled</div>
                                <div class="activity-time">Rajesh Sharma - 2 hours ago</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon medication">💊</div>
                            <div class="activity-content">
                                <div class="activity-title">Medication reminder sent</div>
                                <div class="activity-time">Priya Patel - 4 hours ago</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon emergency">🚨</div>
                            <div class="activity-content">
                                <div class="activity-title">Emergency alert resolved</div>
                                <div class="activity-time">Dr. Kumar - 6 hours ago</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon appointment">📅</div>
                            <div class="activity-content">
                                <div class="activity-title">Appointment completed</div>
                                <div class="activity-time">Amit Singh - 1 day ago</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Health Trends</h3>
                        <a href="#" class="card-action">View Details →</a>
                    </div>
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <div style="font-size: 48px; margin-bottom: 10px;">📈</div>
                        <p>Health trends and analytics will be displayed here</p>
                        <p style="font-size: 12px; margin-top: 10px;">Integration with charting library needed</p>
                    </div>
                </div>
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">Upcoming Appointments</h3>
                        <a href="#" class="card-action">View All →</a>
                    </div>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon appointment">👨‍⚕️</div>
                            <div class="activity-content">
                                <div class="activity-title">Dr. Priya Mehta</div>
                                <div class="activity-time">Tomorrow - 10:30 AM</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon appointment">👨‍⚕️</div>
                            <div class="activity-content">
                                <div class="activity-title">Dr. Raj Kumar</div>
                                <div class="activity-time">Jul 11 - 2:00 PM</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon appointment">👨‍⚕️</div>
                            <div class="activity-content">
                                <div class="activity-title">Dr. Sarah Johnson</div>
                                <div class="activity-time">Jul 12 - 11:00 AM</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Initialize the dashboard
        document.addEventListener('DOMContentLoaded', function() {
            updateCurrentDate();
            initializeAnimations();
            updateStats();
        });

        // Update current date
        function updateCurrentDate() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', options);
        }

        // Initialize animations
        function initializeAnimations() {
            const cards = document.querySelectorAll('.dashboard-card, .stat-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.style.animation = 'fadeInUp 0.6s ease forwards';
            });
        }

        // Quick action functions
        function addNewDoctor() {
            // Redirect to manage doctors page with add parameter
            window.location.href = 'manage_doctors.php?action=add';
        }

        function scheduleAppointment() {
            alert('Schedule Appointment functionality would be implemented here');
        }

        function sendReminder() {
            alert('Send Reminder functionality would be implemented here');
        }

        function generateReport() {
            alert('Generate Report functionality would be implemented here');
        }

        // Notification functions
        function showNotifications() {
            alert('Notifications: 3 new medication reminders, 1 appointment update, 2 system alerts');
        }

        function showHelp() {
            alert('Help and support resources would be available here');
        }

        // Emergency contact
        function handleEmergency() {
            if (confirm('Are you sure you want to initiate emergency contact protocol?')) {
                alert('Emergency contacts have been notified. Response team is on the way.');
            }
        }

        // Logout function
        function confirmLogout() {
            if (confirm("Are you sure you want to logout from Sehat Guardian?")) {
                window.location.href = "../logout.php";
            }
        }

        // Update stats with animation
        function updateStats() {
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(stat => {
                const finalValue = stat.textContent;
                if (!isNaN(parseInt(finalValue))) {
                    animateNumber(stat, parseInt(finalValue));
                }
            });
        }

        // Animate numbers
        function animateNumber(element, target) {
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 20);
        }

        // Simulate real-time updates
        setInterval(() => {
            const badges = document.querySelectorAll('.notification-badge');
            badges.forEach(badge => {
                const currentValue = parseInt(badge.textContent);
                if (Math.random() > 0.9) { // 10% chance to update
                    badge.textContent = currentValue + 1;
                    badge.style.animation = 'pulse 0.5s ease';
                    setTimeout(() => {
                        badge.style.animation = '';
                    }, 500);
                }
            });
        }, 30000);

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }
            .dashboard-card, .stat-card {
                opacity: 0;
            }
        `;
        document.head.appendChild(style);

        // Mobile menu toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Add mobile menu button for small screens
        if (window.innerWidth <= 768) {
            const menuButton = document.createElement('button');
            menuButton.innerHTML = '☰';
            menuButton.style.cssText = `
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1000;
                background: rgba(0, 96, 100, 0.9);
                color: white;
                border: none;
                padding: 12px;
                border-radius: 8px;
                font-size: 18px;
                cursor: pointer;
            `;
            menuButton.onclick = toggleSidebar;
            document.body.appendChild(menuButton);
        }
    </script>
</body>
</html>