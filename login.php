<?php
include "config.php";
include "functions.php";

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
    $stmt->execute([$email, $role]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['fullname'];
        $_SESSION['role'] = $user['role'];

        if($role == 'admin'){
            header("Location: admin.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        $message = "البريد الإلكتروني أو كلمة المرور أو النوع غير صحيح!";
    }
}
?>

<?php include "header.php"; ?>

<div class="content">
    <h1 style="text-align:center; color: var(--sun);">تسجيل الدخول</h1>
    <?php if($message) echo showMessage($message, "red"); ?>

    <form method="POST" style="max-width:400px; margin:auto; display:flex; flex-direction:column; gap:10px;">
        <input type="email" name="email" placeholder="البريد الإلكتروني" required>
        <input type="password" name="password" placeholder="كلمة المرور" required>

        <label>نوع الحساب:</label>
        <select name="role" required>
            <option value="student">طالب</option>
            <option value="admin">ادمن</option>
        </select>

        <button type="submit" style="background-color: var(--sun); color: var(--bg); padding:10px; border:none; border-radius:5px;">دخول</button>
    </form>
</div>

<?php include "footer.php"; ?>
