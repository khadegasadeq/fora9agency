<?php
include "config.php";
include "header.php";

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']); // تم تعديل الاسم
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // تحقق من وجود المستخدم مسبقاً
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $message = "البريد الإلكتروني مستخدم مسبقاً!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
        if($stmt->execute([$fullname, $email, $password])) {
            $message = "تم التسجيل بنجاح! يمكنك تسجيل الدخول الآن.";
        } else {
            $message = "حدث خطأ أثناء التسجيل.";
        }
    }
}
?>

<div class="content">
    <h1 style="text-align:center;">إنشاء حساب</h1>
    <?php if($message) echo "<p style='color:var(--sun); text-align:center;'>$message</p>"; ?>
    <form method="POST" style="max-width:400px; margin:auto; display:flex; flex-direction:column; gap:10px;">
        <input type="text" name="fullname" placeholder="الاسم الكامل" required>
        <input type="email" name="email" placeholder="البريد الإلكتروني" required>
        <input type="password" name="password" placeholder="كلمة المرور" required>
        <button type="submit" style="background-color: var(--sun); color: var(--bg); padding:10px; border:none; border-radius:5px;">تسجيل</button>
    </form>
</div>

<?php include "footer.php"; ?>
