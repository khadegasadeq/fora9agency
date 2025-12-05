<?php
//create_tables.php
require_once "config.php";

// جدول المنح
$scholarships = "
CREATE TABLE IF NOT EXISTS scholarships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    country VARCHAR(100),
    deadline DATE,
    details TEXT,
    link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
";
$pdo->exec($scholarships);

// جدول المستخدمين
$users = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','student') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
";
$pdo->exec($users);

// جدول الطلبات / التطبيقات
$applications = "
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    scholarship_id INT NOT NULL,
    status ENUM('pending','accepted','rejected') DEFAULT 'pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (scholarship_id) REFERENCES scholarships(id)
)
";

// اضفته للاونلاين
// ALTER TABLE scholarships CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
// ALTER TABLE scholarships MODIFY title VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
// ALTER TABLE scholarships MODIFY details TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

$pdo->exec($applications);

echo "✅ All tables created successfully";
?>
