<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['student_logged_in']) || $_SESSION['student_logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

include 'config.php';

function av( $input ) {
    echo '<pre>' ;
    print_r( $input ) ;
    echo '</pre>' ;
}

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

// ฟังก์ชันเพื่อดึงข้อมูลนักศึกษา
function getStudentProfile($conn, $student_id) {
    $sql = "SELECT student_id, name, email, github, nickname, facebook, instagram, line, tel, major FROM student WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// ฟังก์ชันเพื่ออัปเดตข้อมูลนักศึกษา
function updateStudentProfile($conn, $data) {
    // ตรวจสอบให้แน่ใจว่า new_student_id มีค่าและมีความแตกต่างจาก student_id
    if ($data['new_student_id'] !== $data['student_id']) {
        // อัปเดตคำสั่ง SQL เพื่อเปลี่ยน student_id
        $sql = "UPDATE student SET student_id = ?, name = ?, email = ?, github = ?, nickname = ?, facebook = ?, instagram = ?, line = ?, tel = ?, major = ? WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssss", 
            $data['new_student_id'], $data['name'], $data['email'], $data['github'], $data['nickname'], 
            $data['facebook'], $data['instagram'], $data['line'], $data['tel'], 
            $data['major'], $data['student_id']
        );
    } else {
        // หาก student_id ไม่เปลี่ยนแปลง ใช้คำสั่ง SQL ที่ไม่เปลี่ยนแปลง student_id
        $sql = "UPDATE student SET name = ?, email = ?, github = ?, nickname = ?, facebook = ?, instagram = ?, line = ?, tel = ?, major = ? WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", 
            $data['name'], $data['email'], $data['github'], $data['nickname'], 
            $data['facebook'], $data['instagram'], $data['line'], $data['tel'], 
            $data['major'], $data['student_id']
        );
    }

    return $stmt->execute();
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

try {
    $env = loadEnv(__DIR__ . '/.env');
    $conn = new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_NAME']);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // ใช้ email ที่ login มาเพื่อดึง student_id
    $email = $_SESSION['user_email'];
    $student_id = getStudentIdByEmail($conn, $email);

    if (!$student_id) {
        throw new Exception("Student ID not found for email: $email");
    }

    // ตรวจสอบว่ามีการส่งแบบฟอร์มอัปเดตข้อมูลหรือไม่
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = [
            'student_id' => $student_id,
            'new_student_id' => $_POST['student_id'],
            'name' => $_POST['name'],
            'email' => $email,
            'github' => $_POST['github'],
            'nickname' => $_POST['nickname'],
            'facebook' => $_POST['facebook'],
            'instagram' => $_POST['instagram'],
            'line' => $_POST['line'],
            'tel' => $_POST['tel'],
            'major' => $_POST['major']
        ];

        if (updateStudentProfile($conn, $data)) {
            $success_message = "Profile updated successfully!";
            $student_id = getStudentIdByEmail($conn, $email);
        } else {
            $error_message = "Failed to update profile.";
        }
    }

    $profile = getStudentProfile($conn, $student_id);

    //av( $profile ) ;

    // ตรวจสอบว่าดึงข้อมูลได้หรือไม่ ถ้าไม่ได้ให้ตั้งค่าฟิลด์ว่าง
    if (!$profile) {
        $profile = [
            'student_id' => '',
            'name' => '',
            'email' => '',
            'github' => '',
            'nickname' => '',
            'facebook' => '',
            'instagram' => '',
            'line' => '',
            'tel' => '',
            'major' => ''
        ];
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
    <title>Student Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- เมนูด้านบน -->
<?php include('student_menu.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Student Profile</h2>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>

    <form method="POST" action="student_profile.php">
        <div class="form-group">
            <label for="student_id">รหัสนักศึกษา</label>
            <input type="text" class="form-control" id="student_id" name="student_id" value="<?= htmlspecialchars($profile['student_id']) ?>" required>
        </div>
        <div class="form-group">
            <label for="name">ชื่อจริง - นามสกุล</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($profile['name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input disabled type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($profile['email']) ?>" required>
        </div>
        <div class="form-group">
            <label for="github">GitHub</label>
            <input type="text" class="form-control" id="github" name="github" value="<?= htmlspecialchars($profile['github']) ?>">
        </div>
        <div class="form-group">
            <label for="nickname">ชื่อเล่น</label>
            <input type="text" class="form-control" id="nickname" name="nickname" value="<?= htmlspecialchars($profile['nickname']) ?>">
        </div>
        <div class="form-group">
            <label for="facebook">Facebook</label>
            <input type="text" class="form-control" id="facebook" name="facebook" value="<?= htmlspecialchars($profile['facebook']) ?>">
        </div>
        <div class="form-group">
            <label for="instagram">Instagram</label>
            <input type="text" class="form-control" id="instagram" name="instagram" value="<?= htmlspecialchars($profile['instagram']) ?>">
        </div>
        <div class="form-group">
            <label for="line">LINE Notify <small>(ไม่ใช่ Line ID นะ)</small></label>
            <input type="text" class="form-control" id="line" name="line" value="<?= htmlspecialchars($profile['line']) ?>">
        </div>
        <div class="form-group">
            <label for="tel">เบอร์โทรศัพท์</label>
            <input type="text" class="form-control" id="tel" name="tel" value="<?= htmlspecialchars($profile['tel']) ?>">
        </div>
        <div class="form-group">
            <label for="major">หลักสูตร</label>
            <!-- <input type="text" class="form-control" id="major" name="major" value="<?= htmlspecialchars($profile['major']) ?>"> -->

            
                <select class="form-control" id="major" name="major" required>
                    <option value="">-- เลือกหลักสูตร --</option>
                <?php foreach( $class as $value ) : ?>
                    <?php
                        if( $profile['major'] == $value ) {
                            echo "<option value='".$profile['major']."' selected>".$profile['major']."</option>" ;
                        } else {
                            echo "<option value='".$value."'>".$value."</option>" ;
                        }
                    ?>
                <?php endforeach ; ?>
                </select>

        </div>
        <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
    </form>
</div>
<!-- Include the footer -->
<?php include 'footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
