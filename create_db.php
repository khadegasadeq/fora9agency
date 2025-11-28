<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'fora9agency_db';

try {
    // الاتصال بدون قاعدة بيانات
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // إنشاء قاعدة البيانات إذا لم تكن موجودة
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname
            CHARACTER SET utf8mb4
            COLLATE utf8mb4_general_ci";
    $pdo->exec($sql);

    echo "✅ Database created successfully ^^";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
