<?php
// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบแล้วหรือยัง
session_start();
if (!isset($_COOKIE['admin_logged_in']) || $_COOKIE['admin_logged_in'] !== 'true') {
    header('Location: index.php');
    exit();
}

// ตรวจสอบว่ามีการส่งค่า 'lab_id', 'file1', และ 'file2' หรือไม่
if (!isset($_GET['lab_id']) || !isset($_GET['file1']) || !isset($_GET['file2'])) {
    die("Missing parameters.");
}

$lab_id = htmlspecialchars($_GET['lab_id']);
$file1 = htmlspecialchars($_GET['file1']);
$file2 = htmlspecialchars($_GET['file2']);

// สร้างชื่อไฟล์เต็ม
$file1_full = "{$file1}_{$lab_id}.c";
$file2_full = "{$file2}_{$lab_id}.c";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เปรียบเทียบไฟล์</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .file-container {
            display: flex;
            justify-content: space-between;
            height: 80vh;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        .file-content {
            width: 48%;
            overflow: auto;
            border: 1px solid #ddd;
            padding: 10px;
            box-sizing: border-box;
        }
        .file-header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <!-- Include เมนู -->
    <?php include 'admin_menu.php'; ?>

    <div class="container mt-4">
        <h1 class="mb-4">เปรียบเทียบไฟล์</h1>

        <div class="file-container">
            <div class="file-content">
                <div class="file-header">ไฟล์ 1: <?php echo htmlspecialchars($file1_full); ?></div>
                <pre><?php echo htmlspecialchars(file_get_contents("https://thailandfxwarrior.com/lab/student_c/{$file1_full}")); ?></pre>
            </div>
            <div class="file-content">
                <div class="file-header">ไฟล์ 2: <?php echo htmlspecialchars($file2_full); ?></div>
                <pre><?php echo htmlspecialchars(file_get_contents("https://thailandfxwarrior.com/lab/student_c/{$file2_full}")); ?></pre>
            </div>
        </div>
    </div>
<!-- Include the footer -->
<?php include 'footer.php'; ?>
</body>
</html>
