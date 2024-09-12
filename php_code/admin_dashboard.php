<?php
// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบแล้วหรือยัง
session_start();
if (!isset($_COOKIE['admin_logged_in']) || $_COOKIE['admin_logged_in'] !== 'true') {
    header('Location: index.php');
    exit();
}

// นำเข้า config.php เพื่อดึงตารางวิชาที่ต้องการใช้
include 'config.php';

// ฟังก์ชันเพื่ออ่านรหัสผ่านปัจจุบันจากไฟล์ pass.dat
function getCurrentPassword() {
    $file_path = 'pass.dat';
    return file_exists($file_path) ? htmlspecialchars(file_get_contents($file_path)) : 'ไฟล์ pass.dat ไม่พบ';
}

// ฟังก์ชันเพื่อดึงข้อมูลการส่ง Lab จากฐานข้อมูล
function getLabStatistics($conn, $course_code) {
    $sql = "SELECT lab_id, COUNT(*) AS total_submissions,
                   SUM(CASE WHEN result = 1 THEN 1 ELSE 0 END) AS correct_submissions,
                   SUM(CASE WHEN result = 0 THEN 1 ELSE 0 END) AS incorrect_submissions
            FROM $course_code
            GROUP BY lab_id";
    $result = $conn->query($sql);

    if ($result === FALSE) {
        error_log("Error retrieving lab statistics for $course_code: " . $conn->error);
        return [];
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$env = parse_ini_file(__DIR__ . '/.env');
$conn = new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_NAME']);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลสถิติการส่ง Lab ของทุกวิชา
$all_lab_statistics = [];
foreach ($table as $course_code) {
    $all_lab_statistics[$course_code] = getLabStatistics($conn, $course_code);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'admin_menu.php'; ?>

    <div class="container mt-4">
        <h2>ข้อมูลรหัสส่ง Lab</h2>
        <p>รหัสปัจจุบัน: <?php echo getCurrentPassword(); ?></p>

        <h3 class="mt-4">สถิติการส่ง Lab</h3>

        <?php foreach ($all_lab_statistics as $course_code => $lab_statistics): ?>
            <h4>วิชา: <?php echo htmlspecialchars($course_code); ?></h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Lab</th>
                        <th>จำนวนการส่งทั้งหมด</th>
                        <th>จำนวนที่ทำถูกต้อง</th>
                        <th>จำนวนที่ทำผิด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lab_statistics as $stat): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($stat['lab_id']); ?></td>
                            <td><?php echo htmlspecialchars($stat['total_submissions']); ?></td>
                            <td><?php echo htmlspecialchars($stat['correct_submissions']); ?></td>
                            <td><?php echo htmlspecialchars($stat['incorrect_submissions']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
