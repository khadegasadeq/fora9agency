<?php
// functions.php

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

/**
 * تحقق إذا كان المستخدم مسجل الدخول
 * يمكن استدعاؤها في أي صفحة تحتاج تسجيل الدخول
 * إذا لم يكن مسجل سيتم تحويله إلى login.php
 */
function checkLogin() {
    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit;
    }
}

/**
 * تسجيل خروج المستخدم
 * يقوم بمسح كل الجلسات ثم إعادة توجيه المستخدم إلى login.php
 */
function logout() {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

/**
 * دالة لقص النصوص الطويلة
 * @param string $text النص المراد قصه
 * @param int $length الطول الأقصى للنص
 * @return string النص المختصر مع ... إذا تجاوز الطول
 */
function truncateText($text, $length = 120) {
    return strlen($text) > $length ? substr($text, 0, $length) . "..." : $text;
}

/**
 * دالة لإظهار رسالة جميلة في أي صفحة
 * @param string $message نص الرسالة
 * @param string $color لون النص (يمكن استخدام متغير CSS)
 * @return string رسالة HTML جاهزة للعرض
 */
function showMessage($message, $color='var(--sun)') {
    return "<p style='color:$color; text-align:center; padding:5px 0;'>$message</p>";
}

/**
 * دالة للتحقق من صلاحية الوصول حسب الدور
 * @param string $role المطلوب (مثلاً 'admin' أو 'student')
 * إذا لم يكن المستخدم من هذا الدور سيتم تحويله تلقائيًا إلى login.php
 */
function checkRole($role) {
    if(!isset($_SESSION['role']) || $_SESSION['role'] != $role){
        header("Location: login.php");
        exit;
    }
}
