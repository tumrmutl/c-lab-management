<?php
// เริ่มเซสชัน
session_start();

// ตรวจสอบว่ามีการส่งข้อมูลผ่านฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // ตั้งค่าชื่อผู้ใช้และรหัสผ่าน (ตัวอย่าง)
    $registered_users = [
        'student1' => 'password123',
        'student2' => 'password456',
        // เพิ่มชื่อผู้ใช้และรหัสผ่านอื่น ๆ ตามต้องการ
    ];

    // ตรวจสอบชื่อผู้ใช้และรหัสผ่าน
    if (array_key_exists($input_username, $registered_users) && $registered_users[$input_username] === $input_password) {
        // ตั้งค่าเซสชันสำหรับการเข้าสู่ระบบ
        $_SESSION['student_logged_in'] = true;
        $_SESSION['username'] = $input_username;

        // เปลี่ยนเส้นทางไปยังหน้า student_dashboard.php
        header('Location: student_dashboard.php');
        exit();
    } else {
        // แจ้งเตือนผู้ใช้ว่าชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง
        $error_message = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Student Login</h2>
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
        <div class="mt-3">
            <a href="register.php" class="btn btn-secondary">Register</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
