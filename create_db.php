<?php
// create_db.php
require_once "config.php";

try {
    
    $pdoNoDB = new PDO("mysql:host=$host", $user, $pass);
    $pdoNoDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE DATABASE IF NOT EXISTS $dbname
            CHARACTER SET utf8mb4
            COLLATE utf8mb4_general_ci";
    $pdoNoDB->exec($sql);

    echo "✅ Database created successfully ^^";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
