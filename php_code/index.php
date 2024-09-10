<?php
// รหัสผ่านส่วนตัวที่ฝังในโค้ด
$admin_username = "admin";
$admin_password = "113333";

// ตรวจสอบว่ามีการส่งข้อมูลผ่านฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // ตรวจสอบชื่อผู้ใช้และรหัสผ่าน
    if ($input_username === $admin_username && $input_password === $admin_password) {
        // ตั้งค่า cookie สำหรับการเข้าสู่ระบบ
        setcookie('admin_logged_in', 'true', time() + 3600, '/'); // Cookie valid for 1 hour
        
        // เปลี่ยนเส้นทางไปยังหน้า admin_dashboard.php
        header('Location: admin_dashboard.php');
        exit();
    } else {
        // แจ้งเตือนผู้ใช้ว่ารหัสผ่านไม่ถูกต้อง
        $error_message = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Admin Login</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
