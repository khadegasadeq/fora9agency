<?php
include "config.php";
include "header.php";
?>

<div class="content">

    <!-- الوصف التعريفي البارز -->
    <div class="highlight-box">
        <h2>أفضل وكالة تعليمية وسفرية عربية في الشرق الأوسط</h2>
        <p>تسهيل الإجراءات للطلاب الراغبين بالسفر إلى الخارج والحصول على المنح الدراسية بسهولة</p>
    </div>

    <h1 style="color: var(--sun); text-align:center;">منح دراسية متاحة</h1>
    <p style="text-align:center;">اختر المنحة المناسبة وقدم عليها الآن!</p>

    <div class="scholarships-grid">
        <?php
        $stmt = $pdo->query("SELECT * FROM scholarships ORDER BY deadline ASC LIMIT 3");

        if($stmt->rowCount() > 0){
            while($row = $stmt->fetch()){
                ?>
                <div class="scholarship-card">
                    <h2><?php echo $row['title']; ?></h2>
                    <p><strong>الدولة:</strong> <?php echo $row['country']; ?></p>
                    <p><strong>آخر موعد للتقديم:</strong> <?php echo date('d-m-Y', strtotime($row['deadline'])); ?></p>
                    <p><?php echo substr($row['details'],0,120) . "..."; ?></p>
                    <a href="apply.php?id=<?php echo $row['id']; ?>">تقديم الآن</a>
                </div>
                <?php
            }
        } else {
            echo "<p style='color:white; text-align:center;'>لا توجد منح متاحة حالياً.</p>";
        }
        ?>       
        
    </div>

    <div style="text-align:center; margin-top:20px;">
            <a href="all_scholarships.php" style="background-color: var(--sun); color: var(--bg); padding:10px 20px; border-radius:5px; text-decoration:none;">
            عرض كل المنح
        </a>
        </div>

</div>

<?php include "footer.php"; ?>
