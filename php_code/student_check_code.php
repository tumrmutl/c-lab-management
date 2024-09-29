<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
if (!isset($_SESSION['student_logged_in']) || $_SESSION['student_logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

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

// ฟังก์ชันเพื่อตรวจสอบและ highlight โค้ด พร้อมเลขบรรทัด
// ฟังก์ชันเพื่อตรวจสอบและ highlight โค้ด พร้อมเลขบรรทัด
function highlight_code_issues($code) {
    $lines = explode("\n", $code);
    $highlightedCode = "<table class='table' style='border-collapse: collapse; width: 100%;'><thead><tr><th style='border-bottom: 1px solid black;'>Line</th><th style='border-bottom: 1px solid black;'>Code</th></tr></thead><tbody>";

    $inBlock = false; // ใช้เพื่อติดตามว่าเราอยู่ในบล็อกของ { หรือไม่
    $lineNumber = 1; // เริ่มนับบรรทัด

    foreach ($lines as $line) {
        $highlightedLine = htmlspecialchars($line);

        // ตรวจสอบว่ามีการเปิดบล็อกด้วย { หรือไม่
        if (preg_match('/\{/', $line)) {
            $inBlock = true;
        }

        // ตรวจสอบการมีคอมเม้นต์บอกปิดวงเล็บ
        if (preg_match('/\}\s*$/', $line) && !preg_match('/\/\/\s*end/', $line)) {
            $highlightedLine = "<span style='background-color: yellow'>" . $highlightedLine . "</span>";
            $inBlock = false; // ปิดบล็อกเมื่อเจอ }
        }
        // ตรวจสอบการเว้นวรรคในคำสั่งต่าง ๆ
        else if (preg_match('/\bif\(|\bfor\(|\bwhile\(/', $line) && !preg_match('/\s/', $line)) {
            $highlightedLine = "<span style='background-color: orange'>" . $highlightedLine . "</span>";
        }
        // ตรวจสอบการจัดโครงสร้างในบล็อก {}
        else if ($inBlock && !preg_match('/^\t|^ {4}/', $line) && !preg_match('/\{|\}/', $line)) {
            $highlightedLine = "<span style='background-color: orange'>" . $highlightedLine . "</span>";
        }
        // ตรวจสอบโครงสร้างการกด Tab
        else if (preg_match('/\{/', $line) && !preg_match('/^\t+/', $line)) {
            $highlightedLine = "<span style='background-color: lightblue'>" . $highlightedLine . "</span>";
        }
        // ตรวจสอบการไม่มีช่องว่างก่อนหรือหลัง ; (int a = 3;)
        else if (preg_match('/[^\s];|;\S/', $line)) {
            // highlight บรรทัดถ้าเครื่องหมาย ; ติดกับตัวอื่นโดยไม่มีช่องว่าง
            $highlightedLine = "<span style='background-color: lightgreen'>" . $highlightedLine . "</span>";
        }

        // เพิ่มบรรทัดในตารางพร้อมเลขบรรทัด และใช้ CSS เพื่อกำหนดระยะห่างระหว่างบรรทัด
        $highlightedCode .= "<tr style='border: none; height: auto; line-height: 1;'><td style='border-right: 1px solid black; padding: 0 5px;'>$lineNumber</td><td style='border-right: none; line-height: 1; padding: 0;'>$highlightedLine</td></tr>";
        $lineNumber++;
    }

    $highlightedCode .= "</tbody></table>";
    return $highlightedCode;
}


// ฟังก์ชันเพื่อตรวจสอบระดับการเว้นวรรคของบรรทัด
function get_indentation_level($line) {
    preg_match('/^\s*/', $line, $matches);
    return strlen($matches[0]); // คืนค่าจำนวนช่องว่างที่ขึ้นต้นในบรรทัด
}

// ฟังก์ชันเพื่อค้นหาจำนวนจุดที่ต้องแก้ไข
function count_issues($code) {
    $issues = 0;
    $lines = explode("\n", $code);
    $inBlock = false;
    $blockIndentLevel = 0;

    foreach ($lines as $line) {
        if (preg_match('/\{/', $line)) {
            $inBlock = true;
            $blockIndentLevel = get_indentation_level($line);
        }
        
        if (preg_match('/\}\s*$/', $line)) {
            $inBlock = false;
        } 
        else if ($inBlock && !preg_match('/^\t|^ {4}/', $line) && !preg_match('/\{|\}/', $line)) {
            $issues++;
        } 
        else if ($inBlock && get_indentation_level($line) <= $blockIndentLevel) {
            $issues++;
        }
    }
    
    return $issues;
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

$output = "" ;
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
    $conn->close();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $code = $_POST['studentCode'];
        $highlightedCode = highlight_code_issues($code);
        
        $output = "
            <div class='container mt-5'>
                <center>
                    <h2>Code with Formatting Issues Highlighted</h2>
                    <p>จำนวนจุดที่ต้องการปรับปรุงโค้ดให้ได้มาตรฐาน : <b>" . count_issues($code) . "</b> จุด</p>
                </center>
                <pre>" . $highlightedCode . "</pre>
            </div>
        " ;
    }

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
    <?=$output?>
</div>
<div class="container mt-5">
    <h2>Submit Your C Code</h2>
    <form action="student_check_code.php" method="post">
        <div class="mb-3">
            <label for="studentCode" class="form-label">Enter C code:</label>
            <textarea class="form-control" name="studentCode" id="studentCode" rows="15"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Check Code</button>
    </form>
</div>

<!-- Include the footer -->
<?php include 'footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>