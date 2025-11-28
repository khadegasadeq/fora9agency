<?php
include "config.php";
include "functions.php";
include "header.php";

// 1. تأكد أن المستخدم مسجل دخول
checkLogin();

// 2. فقط الطلاب يمكنهم التقديم
checkRole('student');

// 3. التحقق من وجود ID المنحة
$scholarship_id = $_GET['id'] ?? null;
$message = '';

if(!$scholarship_id){
    echo "<p style='color:white; text-align:center;'>رقم المنحة غير محدد.</p>";
    include "footer.php";
    exit;
}

// 4. التحقق من وجود المنحة
$stmt = $pdo->prepare("SELECT * FROM scholarships WHERE id = ?");
$stmt->execute([$scholarship_id]);
$scholarship = $stmt->fetch();

if(!$scholarship){
    echo "<p style='color:white; text-align:center;'>هذه المنحة غير موجودة.</p>";
    include "footer.php";
    exit;
}

// 5. التقديم على المنحة
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user_id = $_SESSION['user_id'];

    // تحقق إذا الطالب قدم على المنحة مسبقًا
    $stmtCheck = $pdo->prepare("SELECT * FROM applications WHERE user_id = ? AND scholarship_id = ?");
    $stmtCheck->execute([$user_id, $scholarship_id]);

    if($stmtCheck->rowCount() > 0){
        $message = showMessage("⚠️ لقد تقدمت لهذه المنحة مسبقًا.", "red");
    } else {
        $stmtInsert = $pdo->prepare("INSERT INTO applications (user_id, scholarship_id) VALUES (?, ?)");
        if($stmtInsert->execute([$user_id, $scholarship_id])){
            $message = showMessage("✅ تم تقديم طلبك بنجاح!");
        } else {
            $message = showMessage("❌ حدث خطأ أثناء تقديم الطلب.", "red");
        }
    }
}
?>

<div class="content">
    <h1 style="text-align:center;">تقديم على المنحة: <?php echo $scholarship['title']; ?></h1>
    <?php if($message) echo $message; ?>

    <form method="POST" style="max-width:400px; margin:auto; display:flex; flex-direction:column; gap:10px;">
        <p>هل أنت متأكد من رغبتك في التقديم على هذه المنحة؟</p>
        <button type="submit" style="background-color: var(--sun); color: var(--bg); padding:10px; border:none; border-radius:5px;">
            تأكيد التقديم
        </button>
    </form>

    <div style="text-align:center; margin-top:20px;">
        <a href="dashboard.php" style="background-color: var(--sun); color: var(--bg); padding:10px 20px; border-radius:5px; text-decoration:none;">
            العودة للوحة التحكم
        </a>
    </div>
</div>

<?php include "footer.php"; ?>
