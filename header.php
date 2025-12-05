<?php
// header.php

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>وكالة فرص</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <!-- الشعار -->
        <div class="logo">
            <img src="assets/images/logo.jpg" alt="وكالة فرص">
        </div>

        <!-- قائمة الروابط -->
        <nav>
            <a href="index.php">الرئيسية</a>
            <a href="register.php">إنشاء حساب</a>
            <a href="login.php">تسجيل الدخول</a>
            <a href="about.php">عن الوكالة</a>

            <?php if(isset($_SESSION['user_id'])): ?>
                <?php
                // تحديد الرابط حسب الدور
                $dashboard_link = ($_SESSION['role'] == 'admin') ? 'admin.php' : 'dashboard.php';
                ?>
                <a href="<?= $dashboard_link ?>" style="background-color: var(--sun); color: var(--bg); padding:5px 10px; border-radius:5px; margin-left:10px;">
                    حسابي
                </a>
                <a href="logout.php" style="background-color:red; color:white; padding:5px 10px; border-radius:5px; margin-left:10px;">
                    تسجيل الخروج
                </a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
