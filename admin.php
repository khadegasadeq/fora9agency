<?php
include "config.php";
include "functions.php";
include "header.php";

// تأكد أن الأدمن فقط يستطيع الدخول
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

$message = '';

// إضافة منحة جديدة
if(isset($_POST['add_scholarship'])){
    $title = $_POST['title'];
    $country = $_POST['country'];
    $details = $_POST['details'];
    $deadline = $_POST['deadline'];

    $stmt = $pdo->prepare("INSERT INTO scholarships (title, country, details, deadline) VALUES (?, ?, ?, ?)");
    if($stmt->execute([$title, $country, $details, $deadline])){
        $message = showMessage("تمت إضافة المنحة بنجاح!");
    } else {
        $message = showMessage("حدث خطأ أثناء الإضافة.", "red");
    }
}

// إضافة مستخدم جديد
if(isset($_POST['add_user'])){
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)");
    if($stmt->execute([$fullname, $email, $password, $role])){
        $message = showMessage("تمت إضافة المستخدم بنجاح!");
    } else {
        $message = showMessage("حدث خطأ أثناء إضافة المستخدم.", "red");
    }
}

// تعديل دور المستخدم عبر AJAX
if(isset($_POST['update_role']) && isset($_POST['user_id'])){
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];
    $stmt = $pdo->prepare("UPDATE users SET role=? WHERE id=?");
    if($stmt->execute([$new_role, $user_id])){
        echo "success";
    } else {
        echo "error";
    }
    exit;
}
?>

<div class="content">
    <h1 style="text-align:center;">لوحة الأدمن</h1>
    <?php if($message) echo $message; ?>

    <!-- أزرار سريعة للوصول -->
    <div style="text-align:center; margin-bottom:40px;">
        <button onclick="showSection('scholarshipsSection')" style="background-color: var(--sun); color: var(--bg); padding:10px 20px; border-radius:5px; margin-right:10px; border:none;">عرض كل المنح</button>
        <button onclick="showSection('usersSection')" style="background-color: var(--accent); color: var(--bg); padding:10px 20px; border-radius:5px; border:none;">عرض كل المستخدمين</button>
    </div>

    <!-- الفورمات -->
    <div id="formsSection">
        <h2>إضافة منحة جديدة</h2>
        <form method="POST" style="display:flex; flex-direction:column; gap:10px; max-width:500px; margin:auto;">
            <input type="text" name="title" placeholder="عنوان المنحة" required>
            <input type="text" name="country" placeholder="الدولة" required>
            <textarea name="details" placeholder="تفاصيل المنحة" required></textarea>
            <input type="date" name="deadline" required>
            <button type="submit" name="add_scholarship" style="background-color: var(--sun); color: var(--bg); padding:10px; border:none; border-radius:5px;">إضافة المنحة</button>
        </form>

        <h2 style="margin-top:40px;">إضافة مستخدم جديد</h2>
        <form method="POST" style="display:flex; flex-direction:column; gap:10px; max-width:500px; margin:auto;">
            <input type="text" name="fullname" placeholder="الاسم الكامل" required>
            <input type="email" name="email" placeholder="البريد الإلكتروني" required>
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <label>نوع الحساب:</label>
            <select name="role" required>
                <option value="student">طالب</option>
                <option value="admin">ادمن</option>
            </select>
            <button type="submit" name="add_user" style="background-color: var(--sun); color: var(--bg); padding:10px; border:none; border-radius:5px;">إضافة المستخدم</button>
        </form>
    </div>

    <!-- قسم المنح -->
    <div id="scholarshipsSection" style="display:none; margin-top:60px;">
        <h2>كل المنح</h2>
        <input type="text" id="searchScholarships" placeholder="ابحث في المنح..." style="margin-bottom:10px; padding:5px; width:300px;">
        <div id="scholarshipsTable">
        <?php
        $stmt = $pdo->query("SELECT * FROM scholarships ORDER BY deadline ASC");
        $scholarships = $stmt->fetchAll();
        if(count($scholarships) > 0):
        ?>
        <table border="1" style="margin:auto; border-collapse:collapse; width:90%; text-align:center;">
            <tr style="background-color: var(--accent); color: var(--bg);">
                <th>العنوان</th>
                <th>الدولة</th>
                <th>آخر موعد</th>
                <th>إجراءات</th>
            </tr>
            <?php foreach($scholarships as $sch): ?>
            <tr>
                <td><?= $sch['title'] ?></td>
                <td><?= $sch['country'] ?></td>
                <td><?= date('d-m-Y', strtotime($sch['deadline'])) ?></td>
                <td>
                    <a href="edit_scholarship.php?id=<?= $sch['id'] ?>" style="margin-right:5px;">✏️ تعديل</a>
                    <a href="delete_scholarship.php?id=<?= $sch['id'] ?>" onclick="return confirm('هل أنت متأكد من حذف هذه المنحة؟');" style="color:red;">🗑️ حذف</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <p style="text-align:center; color: var(--sun);">لا توجد منح حالياً.</p>
        <?php endif; ?>
        </div>
        <div style="text-align:center; margin-top:20px;">
            <button onclick="hideSection('scholarshipsSection')" style="background-color: gray; color:white; padding:10px 20px; border:none; border-radius:5px;">رجوع</button>
        </div>
    </div>

    <!-- قسم المستخدمين -->
    <div id="usersSection" style="display:none; margin-top:60px;">
        <h2>كل المستخدمين</h2>
        <input type="text" id="searchUsers" placeholder="ابحث في المستخدمين..." style="margin-bottom:10px; padding:5px; width:300px;">
        <div id="usersTable">
        <?php
        $stmt = $pdo->query("SELECT * FROM users ORDER BY id ASC");
        $users = $stmt->fetchAll();
        if(count($users) > 0):
        ?>
        <table border="1" style="margin:auto; border-collapse:collapse; width:90%; text-align:center;">
            <tr style="background-color: var(--accent); color: var(--bg);">
                <th>الاسم الكامل</th>
                <th>البريد الإلكتروني</th>
                <th>نوع الحساب</th>
                <th>تعديل الدور</th>
                <th>حذف</th>
                <th>المنح المقدمة</th>
            </tr>
            <?php foreach($users as $user): ?>
            <tr>
                <td><?= $user['fullname'] ?></td>
                <td><?= $user['email'] ?></td>
                <td id="roleText<?= $user['id'] ?>"><?= $user['role'] ?></td>
                <td>
                    <select onchange="updateRole(<?= $user['id'] ?>, this.value)">
                        <option value="student" <?= $user['role']=='student'?'selected':'' ?>>طالب</option>
                        <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>ادمن</option>
                    </select>
                </td>
                <td>
                    <a href="delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');" style="color:red;">🗑️</a>
                </td>
                <td>
                    <?php
                    $stmtApps = $pdo->prepare("SELECT s.title FROM applications a JOIN scholarships s ON a.scholarship_id = s.id WHERE a.user_id = ?");
                    $stmtApps->execute([$user['id']]);
                    $apps = $stmtApps->fetchAll();
                    if($apps):
                        foreach($apps as $a){
                            echo $a['title'] . "<br>";
                        }
                    else:
                        echo "-";
                    endif;
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <p style="text-align:center; color: var(--sun);">لا يوجد مستخدمين حالياً.</p>
        <?php endif; ?>
        </div>
        <div style="text-align:center; margin-top:20px;">
            <button onclick="hideSection('usersSection')" style="background-color: gray; color:white; padding:10px 20px; border:none; border-radius:5px;">رجوع</button>
        </div>
    </div>

    <!-- زر تسجيل الخروج -->
    <div style="text-align:center; margin-top:40px;">
        <a href="logout.php" style="background-color:red; color:white; padding:10px 20px; border-radius:5px; text-decoration:none;">تسجيل الخروج</a>
    </div>
</div>

<script>
function showSection(id){
    document.getElementById(id).style.display = 'block';
    document.getElementById('formsSection').style.display = 'none';
}
function hideSection(id){
    document.getElementById(id).style.display = 'none';
    document.getElementById('formsSection').style.display = 'block';
}

// البحث المباشر في المنح
document.getElementById('searchScholarships').addEventListener('keyup', function(){
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#scholarshipsTable table tr');
    rows.forEach((row, index)=>{
        if(index===0) return; // تجاهل العنوان
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
});

// البحث المباشر في المستخدمين
document.getElementById('searchUsers').addEventListener('keyup', function(){
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#usersTable table tr');
    rows.forEach((row, index)=>{
        if(index===0) return; // تجاهل العنوان
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
});

// تعديل دور المستخدم عبر AJAX
function updateRole(userId, newRole){
    fetch('update_role.php', {
        method:'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: `user_id=${userId}&role=${newRole}`
    })
    .then(res=>res.text())
    .then(data=>{
        if(data.trim() === 'success'){
            document.getElementById('roleText'+userId).innerText = newRole;
        } else {
            alert('حدث خطأ أثناء التعديل');
        }
    });
}

</script>

<?php include "footer.php"; ?>
