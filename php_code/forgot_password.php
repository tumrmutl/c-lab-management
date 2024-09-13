<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// session_start();

// // ตรวจสอบให้แน่ใจว่าผู้ใช้ไม่ได้ล็อกอินอยู่
// if (isset($_SESSION['student_logged_in']) && $_SESSION['student_logged_in'] === true) {
//     header('Location: index.php');
//     exit();
// }

// ฟังก์ชันโหลดการตั้งค่าจากไฟล์ .env
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

// ฟังก์ชันเพื่อดึงข้อมูลนักศึกษาโดยอีเมล
function getStudentByEmail($conn, $email) {
    $sql = "SELECT student_id FROM student WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// ฟังก์ชันส่งอีเมลรีเซ็ตรหัสผ่าน
function sendResetEmail($email, $resetLink) {
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password: $resetLink";
    $headers = "From: no-reply@thailandfxwarrior.com";
    
    return mail($email, $subject, $message, $headers);
}

// ฟังก์ชันบันทึกข้อมูลการรีเซ็ตรหัสผ่านลงในตาราง student
function savePasswordResetToken($conn, $email, $token) {
    // ตั้งเวลาหมดอายุของ token
    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour')); // ตัวอย่าง: token หมดอายุหลัง 1 ชั่วโมง

    $sql = "UPDATE student SET reset_token = ?, token_expires_at = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $token, $expiresAt, $email);
    return $stmt->execute();
}


try {
    $env = loadEnv(__DIR__ . '/.env');
    $conn = new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_NAME']);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // ตรวจสอบว่ามีการส่งแบบฟอร์มรีเซ็ตรหัสผ่านหรือไม่
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $student = getStudentByEmail($conn, $email);

        if ($student) {
            // สร้างลิงก์รีเซ็ตรหัสผ่าน
            $token = bin2hex(random_bytes(32)); // สร้าง token แบบสุ่ม
            $resetLink = "https://thailandfxwarrior.com/lab/reset_password.php?token=$token";

            // บันทึก token ลงในฐานข้อมูล
            if (savePasswordResetToken($conn, $email, $token)) {
                // ส่งอีเมลรีเซ็ตรหัสผ่าน
                if (sendResetEmail($email, $resetLink)) {
                    $success_message = "A password reset link has been sent to your email.";
                } else {
                    $error_message = "Failed to send the reset email. Please try again.";
                }
            } else {
                $error_message = "Failed to save the reset token. Please try again.";
            }
        } else {
            $error_message = "Email not found. Please register first.";
        }
    }

    $conn->close();

} catch (Exception $e) {
    $error_message = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Forgot Password</h2>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>

    <form method="POST" action="forgot_password.php">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Submit</button>
        
    </form>
    <br /><a href="./index.php">< กลับหน้าแรก</a>
</div>
<!-- Include the footer -->
<?php include 'footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
