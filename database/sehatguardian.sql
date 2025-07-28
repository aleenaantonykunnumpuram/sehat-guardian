-- ✅ 1. Create database if not exists
CREATE DATABASE IF NOT EXISTS sehat_guardian;
USE sehat_guardian;

-- ✅ 2. Create `users` table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'patient', 'doctor') NOT NULL,
  gender ENUM('Male','Female','Other') DEFAULT NULL,
  medical_condition TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ✅ 3. Create `doctors` table
CREATE TABLE IF NOT EXISTS doctors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL UNIQUE,
  specialization VARCHAR(100) DEFAULT NULL,
  license_no VARCHAR(50) DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ✅ 4. Create `medicine_schedule` table (UPDATED)
CREATE TABLE IF NOT EXISTS medicine_schedule (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  dosage VARCHAR(50) DEFAULT NULL,
  time TIME DEFAULT NULL,
  from_date DATE DEFAULT NULL,   
  to_date DATE DEFAULT NULL,     
  frequency VARCHAR(50) DEFAULT NULL,
  color VARCHAR(30) DEFAULT NULL,
  status ENUM('Taken','Missed','Pending') DEFAULT 'Pending',
  date DATE DEFAULT NULL,
  taken_at DATETIME DEFAULT NULL,
  FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ✅ 5. Create `health_logs` table
CREATE TABLE IF NOT EXISTS health_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  log_date DATE NOT NULL,
  bp VARCHAR(10) DEFAULT NULL,
  sugar VARCHAR(10) DEFAULT NULL,
  pulse VARCHAR(10) DEFAULT NULL,
  temperature VARCHAR(10) DEFAULT NULL,
  sleep_hours DECIMAL(3,1) DEFAULT NULL,
  water_glasses TINYINT DEFAULT NULL,
  mood VARCHAR(50) DEFAULT NULL,
  symptoms TEXT DEFAULT NULL,
  FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ✅ 6. Create `appointments` table
CREATE TABLE IF NOT EXISTS appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  doctor_id INT NOT NULL,
  appointment_date DATE NOT NULL,
  appointment_time TIME NOT NULL,
  status ENUM('Pending','Approved','Rejected') DEFAULT 'Pending',
  notes TEXT DEFAULT NULL,
  payment_status ENUM('Paid','Pending') DEFAULT 'Pending',
  payment_method ENUM('Cash','UPI','Cheque') DEFAULT NULL,
  receipt_url VARCHAR(255) DEFAULT NULL,
  FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ✅ 7. Create `alerts` table
CREATE TABLE IF NOT EXISTS alerts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  alert_type ENUM('Emergency','Missed Dose') NOT NULL,
  message TEXT DEFAULT NULL,
  status ENUM('Unread','Read') DEFAULT 'Unread',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ✅ 8. Create `reports` table
CREATE TABLE IF NOT EXISTS reports (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  generated_on DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE
);
