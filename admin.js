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
