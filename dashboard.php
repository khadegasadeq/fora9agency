<?php
include "config.php";
include "functions.php";
include "header.php";

checkLogin();

// جلب المنح التي قدم الطالب عليها
$stmt = $pdo->prepare("SELECT a.id, s.title, s.country, s.deadline 
                       FROM applications a 
                       JOIN scholarships s ON a.scholarship_id = s.id
                       WHERE a.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$applications = $stmt->fetchAll();
?>

<div class="content">
    <h1 style="text-align:center;">لوحة التحكم</h1>
    <p style="text-align:center;">مرحباً، <?php echo $_SESSION['username']; ?></p>

    <div style="text-align:center; margin-bottom:20px;">
    <a href="all_scholarships.php" 
       style="background-color: var(--sun); color: var(--bg); padding:10px 20px; border-radius:5px; text-decoration:none;">
       عرض المنح
    </a>
    <a href="application.php" 
       style="background-color: var(--sun); color: var(--bg); padding:10px 20px; border-radius:5px; text-decoration:none;">
       عرض الطلبات
    </a>
    </div>


    <div style="text-align:center; margin-bottom:20px;">
        <a href="application.php" 
           style="background-color: var(--sun); color: var(--bg); padding:10px 20px; border-radius:5px; text-decoration:none;">
           عرض الطلبات
        </a>
    </div>

    <h2 style="text-align:center; margin-top:20px;">المنح التي قدمت عليها</h2>

    <?php if(count($applications) > 0): ?>
        <table style="margin:auto; border-collapse:collapse; width:80%;">
            <thead>
                <tr style="background-color: var(--accent); color: var(--bg);">
                    <th style="padding:10px; border:1px solid var(--bg);">#</th>
                    <th style="padding:10px; border:1px solid var(--bg);">اسم المنحة</th>
                    <th style="padding:10px; border:1px solid var(--bg);">الدولة</th>
                    <th style="padding:10px; border:1px solid var(--bg);">آخر موعد</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($applications as $index => $app): ?>
                    <tr style="background-color: var(--mark); color: var(--bg);">
                        <td style="padding:10px; border:1px solid var(--bg);"><?php echo $index+1; ?></td>
                        <td style="padding:10px; border:1px solid var(--bg);"><?php echo $app['title']; ?></td>
                        <td style="padding:10px; border:1px solid var(--bg);"><?php echo $app['country']; ?></td>
                        <td style="padding:10px; border:1px solid var(--bg);"><?php echo date('d-m-Y', strtotime($app['deadline'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center; color: var(--sun);">لم تقدّم على أي منحة حتى الآن.</p>
    <?php endif; ?>

    <div style="text-align:center; margin-top:20px;">
        <a href="logout.php" style="background-color: var(--sun); color: var(--bg); padding:10px 20px; border-radius:5px; text-decoration:none;">تسجيل الخروج</a>
    </div>
</div>

<?php include "footer.php"; ?>
