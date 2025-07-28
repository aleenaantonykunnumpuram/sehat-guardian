<?php
session_start();
require_once("../includes/db_connect.php");

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Check session
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: doctor_login.php");
    exit();
}

// ✅ Fetch doctor details (force server-side check)
$doctor_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ? AND role = 'doctor'");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$stmt->bind_result($doctor_name);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - Sehat Guardian</title>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #e0f7fa 0%, #f0fffe 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            color: #008080;
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .header .subtitle {
            color: #006666;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .dashboard {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 128, 128, 0.15);
            position: relative;
            overflow: hidden;
        }

        .dashboard::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(0,128,128,0.03) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .welcome-section h2 {
            color: #008080;
            font-size: 2rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .doctor-icon {
            font-size: 2.5rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .welcome-section p {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .stat-card {
            background: linear-gradient(135deg, #008080 0%, #006666 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 128, 128, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 128, 128, 0.4);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .btn {
            display: block;
            padding: 20px 25px;
            background: linear-gradient(135deg, #008080 0%, #006666 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 128, 128, 0.3);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 25px rgba(0, 128, 128, 0.4);
        }

        .btn-icon {
            font-size: 1.5rem;
            margin-right: 10px;
            vertical-align: middle;
        }

        .btn-logout {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        .btn-logout:hover {
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
        }

        .quick-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            border-left: 5px solid #008080;
            position: relative;
            z-index: 1;
        }

        .quick-info h3 {
            color: #008080;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .quick-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .info-item {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .info-item .label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }

        .info-item .value {
            font-size: 1.2rem;
            font-weight: bold;
            color: #008080;
        }

        .logout-section {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .time-display {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0, 128, 128, 0.1);
            padding: 10px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            color: #008080;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .dashboard {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .welcome-section h2 {
                font-size: 1.5rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .actions-grid {
                grid-template-columns: 1fr;
            }
            
            .time-display {
                position: static;
                margin-bottom: 20px;
                display: inline-block;
            }
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
    </style>
    
    <script>
        window.addEventListener("pageshow", function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        // Real-time clock
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString();
            const dateString = now.toLocaleDateString();
            document.getElementById('current-time').textContent = `${dateString} ${timeString}`;
        }

        // Update time every second
        setInterval(updateTime, 1000);

        // Initialize time on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateTime();
            
            // Add subtle animations to stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.style.animation = 'fadeInUp 0.6s ease forwards';
            });
        });

        // CSS for fade in animation
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
        `;
        document.head.appendChild(style);
    </script>
</head>
<body>
    <div class="header">
        <h1>🏥 Sehat Guardian</h1>
        <div class="subtitle">Healthcare Management System</div>
    </div>

    <div class="dashboard">
        <div class="time-display" id="current-time"></div>
        
        <div class="welcome-section">
            <h2>
                <span class="doctor-icon">👨‍⚕️</span>
                Welcome, Dr. <?= htmlspecialchars($_SESSION['user_name']) ?>
            </h2>
            <p>Ready to provide exceptional healthcare today!</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">12</div>
                <div class="stat-label">Today's Appointments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">45</div>
                <div class="stat-label">Total Patients</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">8</div>
                <div class="stat-label">Pending Reviews</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">3</div>
                <div class="stat-label">Urgent Cases</div>
            </div>
        </div>

        <div class="quick-info">
            <h3>📊 Quick Overview</h3>
            <div class="quick-info-grid">
                <div class="info-item">
                    <div class="label">Next Appointment</div>
                    <div class="value">10:30 AM</div>
                </div>
                <div class="info-item">
                    <div class="label">Consultation Hours</div>
                    <div class="value">9 AM - 6 PM</div>
                </div>
                <div class="info-item">
                    <div class="label">Status</div>
                    <div class="value">Active</div>
                </div>
            </div>
        </div>

        <div class="actions-grid">
            <a href="view_appointments.php" class="btn" style="position: relative;">
                <span class="btn-icon">📅</span>
                View Appointments
                <span class="notification-badge">5</span>
            </a>
            <a href="view_patients.php" class="btn">
                <span class="btn-icon">👥</span>
                View Patients
            </a>
            <a href="prescriptions.php" class="btn">
                <span class="btn-icon">💊</span>
                Manage Prescriptions
            </a>
            <a href="medical_records.php" class="btn">
                <span class="btn-icon">📋</span>
                Medical Records
            </a>
            <a href="schedule.php" class="btn">
                <span class="btn-icon">⏰</span>
                Manage Schedule
            </a>
            <a href="reports.php" class="btn">
                <span class="btn-icon">📊</span>
                View Reports
            </a>
        </div>

        <div class="logout-section">
            <a href="../logout.php" class="btn btn-logout">
                <span class="btn-icon">🚪</span>
                Logout
            </a>
        </div>
    </div>
</body>
</html>