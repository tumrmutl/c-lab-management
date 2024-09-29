
<!-- student_menu.php -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="student_dashboard.php">ภาพรวมทั้งหมด</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="student_profile.php">โปรไฟล์</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="student_lab.php">อัพโหลดงาน</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="show_student_score.php">ตรวจสอบงาน</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="student_check_code.php">ตรวจสอบความเรียบร้อยก่อนส่ง</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">ล็อกเอาท์</a>
            </li>
        </ul>

        <!-- แสดงข้อมูล รหัสนักศึกษา และ อีเมล ที่มุมขวาของเมนู -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="navbar-text">
                    <?php echo htmlspecialchars($_SESSION['student_id']); ?>
                </span>
            </li>
            <li class="nav-item">
                <span class="navbar-text ml-3">
                    <?php echo htmlspecialchars($_SESSION['user_email']); ?>
                </span>
            </li>
        </ul>
    </div>
</nav>