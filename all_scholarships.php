<?php
include "config.php";
include "header.php";

// استلام قيمة البحث
$search = trim($_GET['search'] ?? '');

// بناء الاستعلام
if($search != ''){
    $stmt = $pdo->prepare("SELECT * FROM scholarships WHERE title LIKE ? OR country LIKE ? ORDER BY deadline ASC");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM scholarships ORDER BY deadline ASC");
}

$scholarships = $stmt->fetchAll();
?>

<div class="content">
    <h1 style="text-align:center; color: var(--sun); margin-bottom:20px;">جميع المنح الدراسية</h1>

    <!-- فورم البحث -->
<form method="GET" style="text-align:center; margin-bottom:30px;">
    <input type="text" name="search" placeholder="ابحث باسم المنحة أو الدولة" value="<?= htmlspecialchars($search) ?>" style="padding:8px; width:250px;">
    <button type="submit" style="padding:8px 15px; background-color: var(--sun); color: var(--bg); border:none; border-radius:5px;">بحث</button>
    <?php if($search != ''): ?>
        <a href="all_scholarships.php" style="padding:8px 15px; background-color: var(--accent); color: var(--bg); border-radius:5px; text-decoration:none; margin-left:10px;">عرض الكل</a>
    <?php endif; ?>
</form>


    <div class="scholarships-grid">
        <?php if(count($scholarships) > 0): ?>
            <?php foreach($scholarships as $row): ?>
                <div class="scholarship-card">
                    <h2><?= $row['title'] ?></h2>
                    <p><strong>الدولة:</strong> <?= $row['country'] ?></p>
                    <p><strong>آخر موعد للتقديم:</strong> <?= date('d-m-Y', strtotime($row['deadline'])) ?></p>
                    <p><?= substr($row['details'],0,120) . "..." ?></p>
                    <a href="apply.php?id=<?= $row['id'] ?>">تقديم الآن</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color:white; text-align:center;">لا توجد منح متاحة.</p>
        <?php endif; ?>
    </div>
</div>

<?php include "footer.php"; ?>
