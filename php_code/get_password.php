<?php
// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบแล้วหรือยัง
session_start();
if (!isset($_COOKIE['admin_logged_in']) || $_COOKIE['admin_logged_in'] !== 'true') {
    header('Location: index.php');
    exit();
}

// รหัสผ่านส่วนตัวที่ฝังในโค้ด
$admin_password = "113333";

// ตรวจสอบว่ามีการส่งข้อมูลผ่านฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $input_admin_password = $_POST['admin_password'];

    // ตรวจสอบรหัสผ่านส่วนตัว
    if ($input_admin_password === $admin_password) {
        // เปิดไฟล์ pass.dat เพื่ออ่านรหัสผ่าน
        $file_path = 'pass.dat';
        if (file_exists($file_path)) {
            $file_handle = fopen($file_path, 'r');

            if ($file_handle) {
                // อ่านข้อมูลจากไฟล์
                $stored_password = fread($file_handle, filesize($file_path));
                fclose($file_handle);

                // แสดงรหัสผ่านที่เก็บในไฟล์
                echo "<div class='alert alert-info' role='alert'>รหัสผ่านในปัจจุบันคือ: " . htmlspecialchars($stored_password) . "</div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>ไม่สามารถเปิดไฟล์ pass.dat ได้</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>ไฟล์ pass.dat ไม่พบ</div>";
        }
    } else {
        // แจ้งเตือนผู้ใช้ว่ารหัสผ่านไม่ถูกต้อง
        echo "<div class='alert alert-warning' role='alert'>คุณกำลังละเมิดข้อมูลของเรา!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ดูรหัสผ่าน</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <!-- Include เมนู -->
    <?php include 'admin_menu.php'; ?>

    <div class="container mt-4">
        <h1 class="mb-4">ดูรหัสผ่าน</h1>
        <form method="POST" action="get_password.php">
            <div class="form-group">
                <label for="admin_password">รหัสผ่านส่วนตัว:</label>
                <input type="password" class="form-control" id="admin_password" name="admin_password" required>
            </div>
            <button type="submit" class="btn btn-primary">ดูรหัสผ่าน</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
