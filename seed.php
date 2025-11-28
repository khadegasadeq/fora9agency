<?php
require_once "config.php";

try {

    $users = [
        ["Admin User", "admin@example.com", "admin123", "admin"],
        ["Student User", "student@example.com", "student123", "student"]
    ];

    $stmtCheckUser = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmtInsertUser = $pdo->prepare("INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)");

    foreach($users as $user){
        $fullname = $user[0];
        $email = $user[1];
        $password = password_hash($user[2], PASSWORD_DEFAULT);
        $role = $user[3];

        // تحقق إن كان المستخدم موجود مسبقا
        $stmtCheckUser->execute([$email]);
        $count = $stmtCheckUser->fetchColumn();

        if($count == 0){
            $stmtInsertUser->execute([$fullname, $email, $password, $role]);
            echo "✅ User added: $email<br>";
        } else {
            echo "⚠️ User already exists: $email — skipped<br>";
        }
    }

    $scholarships = [
        ["منحة الدراسة في روسيا 2026", "روسيا", "2026-01-16", "تمويل كامل لجميع المستويات الجامعية.", "https://t.me/fora9agency/98"],
        ["منحة معهد الدوحة للدراسات العليا", "قطر", "2026-01-15", "تمويل كامل للدراسات العليا.", "https://t.me/fora9agency/100"]
    ];

    $stmtCheckSch = $pdo->prepare("SELECT COUNT(*) FROM scholarships WHERE title = ?");
    $stmtInsertSch = $pdo->prepare("INSERT INTO scholarships (title, country, deadline, details, link) VALUES (?, ?, ?, ?, ?)");

    foreach($scholarships as $sch){
        $title = $sch[0];

        // تحقق إن كانت المنحة مضافة مسبقًا
        $stmtCheckSch->execute([$title]);
        $count = $stmtCheckSch->fetchColumn();

        if($count == 0){
            $stmtInsertSch->execute($sch);
            echo "✅ Scholarship added: $title<br>";
        } else {
            echo "⚠️ Scholarship already exists: $title — skipped<br>";
        }
    }

} catch(PDOException $e){
    echo "❌ Error: " . $e->getMessage();
}
?>
