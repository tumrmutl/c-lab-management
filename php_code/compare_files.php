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

// ฟังก์ชันเพื่อดึงข้อมูลไฟล์พร้อมเพิ่มเลขบรรทัด
function getFileLines($file) {
    $url = "https://thailandfxwarrior.com/lab/student_c/{$file}";
    if (@file_get_contents($url)) {
        $lines = file($url, FILE_IGNORE_NEW_LINES);
        return $lines;
    } else {
        return ["Error: ไม่พบไฟล์ " . htmlspecialchars($file)];
    }
}

// ดึงข้อมูลจากไฟล์
$file1_content = getFileLines($file1_full);
$file2_content = getFileLines($file2_full);

// หาไฟล์ที่มีจำนวนบรรทัดมากที่สุด
$maxLines = max(count($file1_content), count($file2_content));
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
        .line-number {
            color: gray;
            font-weight: bold;
            padding-right: 10px;
        }
        pre {
            white-space: pre-wrap;
        }
        .highlight {
            background-color: #ffffcc;
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
                <pre>
<?php
for ($i = 0; $i < $maxLines; $i++) {
    $line1 = isset($file1_content[$i]) ? htmlspecialchars($file1_content[$i]) : '';
    $line2 = isset($file2_content[$i]) ? htmlspecialchars($file2_content[$i]) : '';
    
    // ตรวจสอบว่าบรรทัดทั้งสองไฟล์เหมือนกันหรือไม่
    $highlightClass = ($line1 === $line2) ? 'highlight' : '';
    
    echo "<span class='line-number'>" . ($i + 1) . ":</span> ";
    echo "<span class='{$highlightClass}'>{$line1}</span>";
    echo "\n";
}
?>
                </pre>
            </div>
            <div class="file-content">
                <div class="file-header">ไฟล์ 2: <?php echo htmlspecialchars($file2_full); ?></div>
                <pre>
<?php
for ($i = 0; $i < $maxLines; $i++) {
    $line1 = isset($file1_content[$i]) ? htmlspecialchars($file1_content[$i]) : '';
    $line2 = isset($file2_content[$i]) ? htmlspecialchars($file2_content[$i]) : '';
    
    // ตรวจสอบว่าบรรทัดทั้งสองไฟล์เหมือนกันหรือไม่
    $highlightClass = ($line1 === $line2) ? 'highlight' : '';
    
    echo "<span class='line-number'>" . ($i + 1) . ":</span> ";
    echo "<span class='{$highlightClass}'>{$line2}</span>";
    echo "\n";
}
?>
                </pre>
            </div>
        </div>
    </div>
<!-- Include the footer -->
<?php include 'footer.php'; ?>
</body>
</html>
