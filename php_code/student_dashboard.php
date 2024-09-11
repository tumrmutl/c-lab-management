<?php
// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบแล้วหรือไม่
session_start();
if (!isset($_SESSION['student_logged_in']) || $_SESSION['student_logged_in'] !== true) {
    header('Location: index.php');
    exit();
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

// ฟังก์ชันเพื่อดึงข้อมูล Lab Overview ทั้งหมด
function getLabOverviewDetails($conn) {
    // Lab ID ทั้งหมดที่มีการส่งข้อมูล
    $sql_labs = "
        SELECT lab_id, 
               COUNT(*) AS total_submissions,
               SUM(CASE WHEN result = 0 THEN 1 ELSE 0 END) AS incorrect_submissions,
               SUM(CASE WHEN result = 1 THEN 1 ELSE 0 END) AS correct_submissions
        FROM LAB
        GROUP BY lab_id
        ORDER BY lab_id ASC
    ";
    $result_labs = $conn->query($sql_labs);
    $lab_details = [];
    
    while ($row = $result_labs->fetch_assoc()) {
        $lab_details[] = [
            'lab_id' => $row['lab_id'],
            'total_submissions' => $row['total_submissions'],
            'incorrect_submissions' => $row['incorrect_submissions'],
            'correct_submissions' => $row['correct_submissions']
        ];
    }

    return $lab_details;
}


// ฟังก์ชันเพื่อดึงข้อมูลภาพรวมของ Lab
function getLabOverview($conn, $student_id) {
    // จำนวน Lab ID ทั้งหมด
    $sql_total_labs = "SELECT COUNT(DISTINCT lab_id) AS total_labs FROM LAB";
    $result_total = $conn->query($sql_total_labs);
    $total_labs = $result_total->fetch_assoc()['total_labs'];

    // จำนวน Lab ที่ทำถูกต้อง
    $sql_correct = "SELECT COUNT(DISTINCT lab_id) AS correct_labs FROM LAB WHERE std_id = ? AND result = 1";
    $stmt_correct = $conn->prepare($sql_correct);
    $stmt_correct->bind_param("s", $student_id);
    $stmt_correct->execute();
    $result_correct = $stmt_correct->get_result();
    $correct_labs = $result_correct->fetch_assoc()['correct_labs'];

    // จำนวน Lab ที่ทำผิด
    $sql_incorrect = "SELECT COUNT(DISTINCT lab_id) AS incorrect_labs FROM LAB WHERE std_id = ? AND result = 0";
    $stmt_incorrect = $conn->prepare($sql_incorrect);
    $stmt_incorrect->bind_param("s", $student_id);
    $stmt_incorrect->execute();
    $result_incorrect = $stmt_incorrect->get_result();
    $incorrect_labs = $result_incorrect->fetch_assoc()['incorrect_labs'];

    return [
        'total_labs' => $total_labs,
        'correct_labs' => $correct_labs,
        'incorrect_labs' => $incorrect_labs
    ];
}

// ฟังก์ชันเพื่อดึงข้อมูลการส่ง Lab ของนักเรียน
// ฟังก์ชันเพื่อดึงข้อมูลการส่ง Lab ของนักเรียน
function getStudentLabDetails($conn, $student_id) {
    // Lab ที่ยังไม่ได้ส่ง
    $sql_not_submitted = "
        SELECT DISTINCT lab_id 
        FROM LAB 
        WHERE lab_id NOT IN (
            SELECT DISTINCT lab_id 
            FROM LAB 
            WHERE std_id = ?
        )
        ORDER BY lab_id ASC
    ";
    $stmt_not_submitted = $conn->prepare($sql_not_submitted);
    $stmt_not_submitted->bind_param("s", $student_id);
    $stmt_not_submitted->execute();
    $result_not_submitted = $stmt_not_submitted->get_result();
    $not_submitted_labs = [];
    while ($row = $result_not_submitted->fetch_assoc()) {
        $not_submitted_labs[] = $row['lab_id'];
    }

    // Lab ที่ส่งแล้วแต่ทำผิด
    $sql_submitted_incorrect = "
        SELECT DISTINCT lab_id 
        FROM LAB 
        WHERE std_id = ? AND result = 0
        ORDER BY lab_id ASC
    ";
    $stmt_submitted_incorrect = $conn->prepare($sql_submitted_incorrect);
    $stmt_submitted_incorrect->bind_param("s", $student_id);
    $stmt_submitted_incorrect->execute();
    $result_submitted_incorrect = $stmt_submitted_incorrect->get_result();
    $submitted_incorrect_labs = [];
    while ($row = $result_submitted_incorrect->fetch_assoc()) {
        $submitted_incorrect_labs[] = $row['lab_id'];
    }

    // Lab ที่ส่งแล้วและทำถูกต้อง
    $sql_submitted_correct = "
        SELECT DISTINCT lab_id 
        FROM LAB 
        WHERE std_id = ? AND result = 1
        ORDER BY lab_id ASC
    ";
    $stmt_submitted_correct = $conn->prepare($sql_submitted_correct);
    $stmt_submitted_correct->bind_param("s", $student_id);
    $stmt_submitted_correct->execute();
    $result_submitted_correct = $stmt_submitted_correct->get_result();
    $submitted_correct_labs = [];
    while ($row = $result_submitted_correct->fetch_assoc()) {
        $submitted_correct_labs[] = $row['lab_id'];
    }

    return [
        'not_submitted_labs' => $not_submitted_labs,
        'submitted_incorrect_labs' => $submitted_incorrect_labs,
        'submitted_correct_labs' => $submitted_correct_labs
    ];
}

// ฟังก์ชันเพื่อดึง Lab ID จากชื่อไฟล์
function extractLabIdFromFileName($file_name) {
    // สมมติว่าไฟล์ชื่อเป็นรูปแบบ: studentID_labID.c
    $parts = explode('_', basename($file_name, '.c')); // แยก studentID และ labID
    return isset($parts[1]) ? $parts[1] : null; // คืนค่าที่เป็น labID
}

// ฟังก์ชันเพื่อดึงข้อมูลไฟล์ที่อัพโหลดและรอตรวจสอบของนักเรียนปัจจุบัน
function getPendingLabFiles($directory, $student_id) {
    $file_paths = [];
    $files = glob($directory . "*.c");
    
    foreach ($files as $file) {
        $file_name = basename($file);
        $lab_id = extractLabIdFromFileName($file_name);
        // ตรวจสอบว่า Lab ID ของไฟล์ตรงกับรหัสนักเรียน
        if ($lab_id && strpos($file_name, $student_id . '_') === 0) {
            $file_paths[] = 'https://thailandfxwarrior.com/lab/student_c/' . $file_name;
        }
    }
    
    return $file_paths;
}

try {
    $env = loadEnv(__DIR__ . '/.env');
    $conn = new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_NAME']);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $user_email = $_SESSION['user_email']; // ใช้อีเมลจากเซสชัน
    $student_id = getStudentIdByEmail($conn, $user_email);

    if (!$student_id) {
        throw new Exception("Student ID not found.");
    }

    $lab_overview = getLabOverview($conn, $student_id);
    $student_lab_details = getStudentLabDetails($conn, $student_id);
    $lab_overview_details = getLabOverviewDetails($conn); // เรียกใช้ฟังก์ชันใหม่

    // กำหนดโฟลเดอร์ที่ใช้เก็บไฟล์
    $upload_directory = 'student_c/';

    // ดึงข้อมูลไฟล์ที่รอตรวจสอบของนักเรียนปัจจุบัน
    $pending_lab_files = getPendingLabFiles($upload_directory, $student_id);


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
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card {
            margin-bottom: 20px;
        }
        .table thead th {
            background-color: #f8f9fa;
        }
        .status-correct {
            color: #28a745;
            font-weight: bold;
        }
        .status-incorrect {
            color: #dc3545;
            font-weight: bold;
        }
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include 'student_menu.php'; ?>

    <div class="container mt-4">
        <h2 class="mb-4 text-center">Student Dashboard</h2>

        <!-- Display Error Message if Exists -->
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Lab Overview Section -->
        <div class="card">
            <div class="card-header">
                <h4>Lab Overview</h4>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th>Lab ID</th>
                            <th>Total Submissions</th>
                            <th>Correct</th>
                            <th>Incorrect</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lab_overview_details as $lab): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($lab['lab_id']); ?></td>
                                <td><?php echo htmlspecialchars($lab['total_submissions']); ?></td>
                                <td class="status-correct"><?php echo htmlspecialchars($lab['correct_submissions']); ?></td>
                                <td class="status-incorrect"><?php echo htmlspecialchars($lab['incorrect_submissions']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Student Lab Submission Details -->
        <div class="row">
            <!-- Not Submitted Labs -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Labs Not Submitted</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($student_lab_details['not_submitted_labs'])): ?>
                            <ul class="list-group">
                                <?php foreach ($student_lab_details['not_submitted_labs'] as $lab_id): ?>
                                    <li class="list-group-item status-pending"><?php echo htmlspecialchars($lab_id); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No labs pending submission</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Incorrect Labs -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Labs Submitted Incorrectly</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($student_lab_details['submitted_incorrect_labs'])): ?>
                            <ul class="list-group">
                                <?php foreach ($student_lab_details['submitted_incorrect_labs'] as $lab_id): ?>
                                    <li class="list-group-item status-incorrect"><?php echo htmlspecialchars($lab_id); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No incorrect submissions</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Correct Labs -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Labs Submitted Correctly</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($student_lab_details['submitted_correct_labs'])): ?>
                            <ul class="list-group">
                                <?php foreach ($student_lab_details['submitted_correct_labs'] as $lab_id): ?>
                                    <li class="list-group-item status-correct"><?php echo htmlspecialchars($lab_id); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No correct submissions</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Lab Files Section -->
        <div class="card mt-4">
            <div class="card-header">
                <h4>Your Pending Lab Files</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($pending_lab_files)): ?>
                    <ul class="list-group">
                        <?php foreach ($pending_lab_files as $file_url): ?>
                            <li class="list-group-item">
                                <a href="<?php echo htmlspecialchars($file_url); ?>" target="_blank"><?php echo htmlspecialchars(basename($file_url)); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">No pending lab files for review.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Include the footer -->
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
