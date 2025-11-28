<?php
include "config.php";
include "functions.php";
include "header.php";

checkLogin();

$user_id = $_SESSION['user_id'];

// جلب جميع الطلبات لهذا الطالب
$stmt = $pdo->prepare("
    SELECT a.id, s.title, s.country, a.status, a.applied_at
    FROM applications a
    JOIN scholarships s ON a.scholarship_id = s.id
    WHERE a.user_id = ?
");
$stmt->execute([$user_id]);
$applications = $stmt->fetchAll();

// ===== معالجة حذف طلب الطالب =====
if(isset($_POST['delete_application'])){
    $app_id = $_POST['application_id'];
    $stmt = $pdo->prepare("DELETE FROM applications WHERE id = ? AND user_id = ?");
    if($stmt->execute([$app_id, $_SESSION['user_id']])){
        $message = "تم حذف الطلب بنجاح!";
        // تحديث قائمة الطلبات بعد الحذف
        $stmt = $pdo->prepare("
            SELECT a.id, s.title, s.country, a.status, a.applied_at
            FROM applications a
            JOIN scholarships s ON a.scholarship_id = s.id
            WHERE a.user_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $applications = $stmt->fetchAll();
    } else {
        $message = "حدث خطأ أثناء حذف الطلب.";
    }
}
?>

<div class="content">
    <h2 style="text-align:center;">طلبات المنح الخاصة بي</h2>

    <!-- عرض الرسالة -->
    <?php if(isset($message)) echo "<p style='color:var(--sun); text-align:center;'>$message</p>"; ?>

    <?php if(count($applications) > 0): ?>
    <table border="1" style="margin:auto; border-collapse:collapse; width:80%;">
        <tr>
            <th>المنحة</th>
            <th>البلد</th>
            <th>الحالة</th>
            <th>تاريخ الطلب</th>
            <th>إجراء</th>
        </tr>
        <?php foreach($applications as $app): ?>
        <tr>
            <td><?= $app['title'] ?></td>
            <td><?= $app['country'] ?></td>
            <td><?= $app['status'] ?></td>
            <td><?= $app['applied_at'] ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="application_id" value="<?= $app['id'] ?>">
                    <button type="submit" name="delete_application" style="background-color:red; color:white; padding:5px 10px; border:none; border-radius:3px;">
                        حذف
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p style="text-align:center; color: var(--sun);">لم تقدّم على أي منحة حتى الآن.</p>
    <?php endif; ?>

    <div style="text-align:center; margin-top:20px;">
        <a href="dashboard.php" style="background-color: var(--sun); color: var(--bg); padding:10px 20px; border-radius:5px; text-decoration:none;">عودة للوحة التحكم</a>
        <a href="all_scholarships.php" style="background-color: var(--sun); color: var(--bg); padding:10px 20px; border-radius:5px; text-decoration:none; margin-left:10px;">
            عرض كل المنح
        </a>
    </div>
</div>

<?php include "footer.php"; ?>
