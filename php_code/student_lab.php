<?php
session_start();

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['student_logged_in']) || $_SESSION['student_logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

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

// ฟังก์ชันเพื่อโหลดการตั้งค่าฐานข้อมูลจากไฟล์ .env
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

    // ดึงรหัสนักเรียนจากเซสชัน
    $user_email = $_SESSION['user_email'];
    $student_id = getStudentIdByEmail($conn, $user_email);

    if (!$student_id) {
        throw new Exception("Student ID not found.");
    }

    $conn->close();
} catch (Exception $e) {
    $error_message = $e->getMessage();
}

// กำหนดโฟลเดอร์ที่ใช้เก็บไฟล์
$target_dir = "student_c/";
$uploadOk = 1;

// ตรวจสอบว่าไฟล์ถูกส่งมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir .= $_POST['course'] . "/" ;
    
    // กำหนดชื่อไฟล์และนามสกุล
    $fileName = basename($_FILES["fileToUpload"]["name"]);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // อ่านรหัสจากไฟล์ pass.dat
    $passFile = 'pass.dat';
    $validPassword = trim(file_get_contents($passFile));
    
    // ตรวจสอบว่ามีการกรอกรหัสผ่านหรือไม่
    if (isset($_POST['password'])) {
        $enteredPassword = $_POST['password'];
        
        // ตรวจสอบว่ารหัสผ่านถูกต้องหรือไม่
        if ($enteredPassword !== $validPassword) {
            $message = "<div class='alert alert-danger' role='alert'>รหัสผ่านไม่ถูกต้อง.</div>";
            $uploadOk = 0;
        }
    } else {
        $message = "<div class='alert alert-danger' role='alert'>กรุณากรอกรหัสผ่าน.</div>";
        $uploadOk = 0;
    }
    
    // ตรวจสอบว่ามีการเลือกวิชาไหม
    if (isset($_POST['course'])) {
        $course = $_POST['course'];
    } else {
        $message = "<div class='alert alert-danger' role='alert'>กรุณาเลือกวิชา.</div>";
        $uploadOk = 0;
    }

    // ตรวจสอบว่ามีการกรอกข้อมูลเลข Lab หรือไม่
    if (isset($_POST['lab_number'])) {
        $lab_number = $_POST['lab_number'];
    } else {
        $message = "<div class='alert alert-danger' role='alert'>กรุณากรอกเลข Lab.</div>";
        $uploadOk = 0;
    }

    // ตรวจสอบชนิดของไฟล์
    if ($uploadOk == 1) {
        // ตรวจสอบนามสกุลไฟล์
        if (!in_array($fileType, ['c', 'cpp', 'py', 'java'])) {
            $message = "<div class='alert alert-danger' role='alert'>ขออภัย, เพียงแค่ไฟล์ .c, .cpp, .py และ .java เท่านั้นที่สามารถอัพโหลดได้.</div>";
            $uploadOk = 0;
        }

        // ตรวจสอบว่าไฟล์ถูกอัพโหลดสำเร็จหรือไม่
        if ($uploadOk == 1) {
            // สร้างชื่อไฟล์ใหม่ตามรูปแบบ รหัสนักศึกษา_เลขLab.นามสกุล
            $newFileName = $student_id . "_" . $lab_number . "." . $fileType;
            $target_file = $target_dir . $newFileName ;

            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $message = "<div class='alert alert-success' role='alert'>ไฟล์ " . htmlspecialchars($newFileName) . " ได้รับการอัพโหลดแล้วที่ <a href='".$target_file."' target='_BLANK'>" . $target_file . "</a></div>";
                $message .= "<div class='alert alert-info' role='alert'>วิชาที่เลือก: " . htmlspecialchars($course) . "</div>";
            } else {
                $message = "<div class='alert alert-danger' role='alert'>เกิดข้อผิดพลาดในการอัพโหลดไฟล์ของคุณ.</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'student_menu.php'; ?>

    <div class="container mt-4">
        <h1 class="mb-4">Upload File</h1>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($message)): ?>
            <?php echo $message; ?>
        <?php endif; ?>

        <form action="student_lab.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="password">รหัสผ่าน</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="lab_number">เลข Lab</label>
                <input type="text" class="form-control" id="lab_number" name="lab_number" required>
            </div>
            <div class="form-group">
                <label for="course">เลือกวิชา</label>
                <select class="form-control" id="course" name="course" required>
                    <option value="">-- เลือกวิชา --</option>
                    <option value="ENGCC304">ENGCC304 Computer Programming</option>
                    <option value="ENGCE174">ENGCE174 Computer Programming for Computer Engineering</option>
                    <option value="ENGCE117">ENGCE117 Object-oriented Programming</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fileToUpload">เลือกไฟล์เพื่ออัพโหลด</label>
                <input type="file" class="form-control-file" id="fileToUpload" name="fileToUpload" required>
            </div>
            <button type="submit" class="btn btn-primary">อัพโหลดไฟล์</button>
        </form>
    </div>
<!-- Include the footer -->
<?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

