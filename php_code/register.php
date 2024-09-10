<?php
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // โหลดค่าจากไฟล์ .env
        $env = loadEnv(__DIR__ . '/.env');

        // เชื่อมต่อฐานข้อมูล
        $conn = new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_NAME']);
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // รับค่าจากฟอร์มและทำการป้องกัน SQL Injection
        $student_id = $conn->real_escape_string($_POST['student_id']);
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $github = $conn->real_escape_string($_POST['github']);
        $nickname = $conn->real_escape_string($_POST['nickname']);
        $facebook = $conn->real_escape_string($_POST['facebook']);
        $instagram = $conn->real_escape_string($_POST['instagram']);
        $line = $conn->real_escape_string($_POST['line']);
        $tel = $conn->real_escape_string($_POST['tel']);
        $major = $conn->real_escape_string($_POST['major']);

        // ตรวจสอบว่าอีเมลมีอยู่ในฐานข้อมูลแล้วหรือไม่
        $sql_check = "SELECT COUNT(*) FROM student WHERE email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($count > 0) {
            $error_message = "อีเมลนี้มีอยู่ในฐานข้อมูลแล้ว กรุณากด 'Forgot Password' หากคุณลืมรหัสผ่าน";
        } else {
            // สร้างคำสั่ง SQL สำหรับการบันทึกข้อมูล
            $sql_insert = "INSERT INTO student (student_id, name, email, password, github, nickname, facebook, instagram, line, tel, major) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // เตรียมคำสั่ง SQL
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sssssssssss", $student_id, $name, $email, $password, $github, $nickname, $facebook, $instagram, $line, $tel, $major);

            // ตรวจสอบและบันทึกข้อมูล
            if ($stmt_insert->execute()) {
                $success_message = "ลงทะเบียนสำเร็จ! คุณสามารถเข้าสู่ระบบได้.";
            } else {
                $error_message = "เกิดข้อผิดพลาดในการลงทะเบียน: " . $stmt_insert->error;
            }

            // ปิดการเชื่อมต่อ
            $stmt_insert->close();
        }

        $conn->close();

    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Student Registration</h2>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

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
            <div class="form-group">
                <label for="student_id">Student ID</label>
                <input type="text" class="form-control" id="student_id" name="student_id" required>
            </div>
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="github">GitHub Username</label>
                <input type="text" class="form-control" id="github" name="github">
            </div>
            <div class="form-group">
                <label for="nickname">Nickname</label>
                <input type="text" class="form-control" id="nickname" name="nickname">
            </div>
            <div class="form-group">
                <label for="facebook">Facebook Profile</label>
                <input type="text" class="form-control" id="facebook" name="facebook">
            </div>
            <div class="form-group">
                <label for="instagram">Instagram Profile</label>
                <input type="text" class="form-control" id="instagram" name="instagram">
            </div>
            <div class="form-group">
                <label for="line">LINE ID</label>
                <input type="text" class="form-control" id="line" name="line">
            </div>
            <div class="form-group">
                <label for="tel">Phone Number</label>
                <input type="text" class="form-control" id="tel" name="tel">
            </div>
            <div class="form-group">
                <label for="major">Major</label>
                <input type="text" class="form-control" id="major" name="major">
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
