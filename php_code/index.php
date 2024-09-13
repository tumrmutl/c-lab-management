<?php
// เริ่มเซสชัน
session_start();

// ฟังก์ชันเพื่อดึง student_id จาก email
function getStudentIdByEmail($conn, $email) {
    $sql = "SELECT student_id FROM student WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    return $student ? $student['student_id'] : null;
}

// เชื่อมต่อฐานข้อมูล
function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception(".env file not found at $path");
    }

    $envVars = [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);
        $envVars[trim($key)] = trim($value);
    }

    return $envVars;
}

try {
    $env = loadEnv(__DIR__ . '/.env');
    $conn = new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_NAME']);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // ตรวจสอบว่ามีการส่งข้อมูลผ่านฟอร์มหรือไม่
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // รับค่าจากฟอร์ม
        $input_email = $_POST['email'];
        $input_password = $_POST['password'];

        // ตรวจสอบอีเมลในฐานข้อมูล
        $sql_check = "SELECT id, password FROM student WHERE email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $input_email);
        $stmt_check->execute();
        $stmt_check->store_result();
        $stmt_check->bind_result($user_id, $hashed_password);
        $stmt_check->fetch();

        if ($stmt_check->num_rows > 0) {
            // อีเมลถูกต้อง
            if (password_verify($input_password, $hashed_password)) {
                // ตั้งค่าเซสชันสำหรับการเข้าสู่ระบบ
                $_SESSION['student_logged_in'] = true;
                $_SESSION['user_email'] = $input_email; // ใช้อีเมลของผู้ใช้
                $_SESSION['student_id'] = getStudentIdByEmail($conn, $input_email); // ใช้อีเมลของผู้ใช้

                setcookie('student_logged_in', 'true', time() + 3600, '/'); // Cookie valid for 1 hour
                session_set_cookie_params(3600);
                // เปลี่ยนเส้นทางไปยังหน้า student_dashboard.php
                header('Location: student_dashboard.php');
                exit();
            } else {
                // รหัสผ่านไม่ถูกต้อง
                $error_message = "รหัสผ่านไม่ถูกต้อง.";
            }
        } else {
            // อีเมลไม่ถูกต้อง
            $error_message = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง.";
        }

        $stmt_check->close();
    }
} catch (Exception $e) {
    $error_message = $e->getMessage();
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
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
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="register.php" class="btn btn-secondary" target="_blank">Register</a>
                <a href="forgot_password.php" class="btn btn-link" target="_blank">Forgot Password?</a>
            </div>    
        </form>
        
    </div>
<!-- Include the footer -->
<?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
