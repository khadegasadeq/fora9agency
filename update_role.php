<?php
include "config.php";
session_start();

// تأكد أن الأدمن فقط يستطيع الوصول
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    http_response_code(403);
    echo "error";
    exit;
}

if(isset($_POST['user_id']) && isset($_POST['role'])){
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];

    $stmt = $pdo->prepare("UPDATE users SET role=? WHERE id=?");
    if($stmt->execute([$new_role, $user_id])){
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "error";
}
?>
