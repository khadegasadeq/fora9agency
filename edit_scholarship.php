<?php
include "config.php";
include "functions.php";
include "header.php";

// تأكد أن الأدمن فقط يستطيع الدخول
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

$scholarship_id = $_GET['id'] ?? null;
$message = '';

if(!$scholarship_id){
    echo "<p style='color:white; text-align:center;'>المنحة غير محددة.</p>";
    include "footer.php";
    exit;
}

// جلب بيانات المنحة
$stmt = $pdo->prepare("SELECT * FROM scholarships WHERE id=?");
$stmt->execute([$scholarship_id]);
$scholarship = $stmt->fetch();

if(!$scholarship){
    echo "<p style='color:white; text-align:center;'>المنحة غير موجودة.</p>";
    include "footer.php";
    exit;
}

// معالجة تعديل المنحة
if(isset($_POST['update_scholarship'])){
    $title = $_POST['title'];
    $country = $_POST['country'];
    $details = $_POST['details'];
    $deadline = $_POST['deadline'];

    $stmt = $pdo->prepare("UPDATE scholarships SET title=?, country=?, details=?, deadline=? WHERE id=?");
    if($stmt->execute([$title, $country, $details, $deadline, $scholarship_id])){
        $message = showMessage("تم تعديل المنحة بنجاح!");
        // إعادة جلب البيانات بعد التحديث
        $stmt = $pdo->prepare("SELECT * FROM scholarships WHERE id=?");
        $stmt->execute([$scholarship_id]);
        $scholarship = $stmt->fetch();
    } else {
        $message = showMessage("حدث خطأ أثناء التعديل.", "red");
    }
}
?>

<div class="content">
    <h1 style="text-align:center;">تعديل المنحة</h1>
    <?php if($message) echo $message; ?>

    <form method="POST" style="display:flex; flex-direction:column; gap:10px; max-width:500px; margin:auto;">
        <input type="text" name="title" value="<?= htmlspecialchars($scholarship['title']) ?>" placeholder="عنوان المنحة" required>
        <input type="text" name="country" value="<?= htmlspecialchars($scholarship['country']) ?>" placeholder="الدولة" required>
        <textarea name="details" placeholder="تفاصيل المنحة" required><?= htmlspecialchars($scholarship['details']) ?></textarea>
        <input type="date" name="deadline" value="<?= $scholarship['deadline'] ?>" required>
        <button type="submit" name="update_scholarship" style="background-color: var(--sun); color: var(--bg); padding:10px; border:none; border-radius:5px;">حفظ التعديلات</button>
    </form>

    <div style="text-align:center; margin-top:20px;">
        <a href="admin.php" style="background-color: gray; color:white; padding:10px 20px; border-radius:5px; text-decoration:none;">رجوع للوحة الأدمن</a>
    </div>
</div>

<?php include "footer.php"; ?>
