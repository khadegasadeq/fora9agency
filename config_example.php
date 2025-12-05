<?php


// rename it to  config.php

$host = 'Yourhost';
$user = 'Yourusername';
$pass = '';
$dbname = 'YourDB';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => true
        ]
    );
} catch (PDOException $e) {
    die("❌ Database Connection Failed: " . $e->getMessage());
}
?>
