-- SQL script to create the SehatGuardian database schema
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'patient', 'doctor') NOT NULL,

    -- Patient-specific fields
    gender ENUM('Male', 'Female', 'Other') DEFAULT NULL,
    medical_condition TEXT DEFAULT NULL,
    registered_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (registered_by) REFERENCES users(id) ON DELETE SET NULL
);