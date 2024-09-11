<?php
header('Content-Type: application/json');

// รับค่า subject จาก GET parameter
$subject = isset($_GET['subject']) ? $_GET['subject'] : null;

// ตรวจสอบว่ามี subject หรือไม่
if ($subject) {
    $directory = 'student_c/' . $subject; // โฟลเดอร์ที่เก็บไฟล์ตามรหัสวิชา
    $base_url = 'https://thailandfxwarrior.com/lab/student_c/' . $subject . '/';
    $files = array();

    // ตรวจสอบว่ามีโฟลเดอร์ตาม subject หรือไม่
    if (is_dir($directory)) {
        if ($dh = opendir($directory)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != ".." && !is_dir($directory . '/' . $file)) {
                    $files[] = $base_url . $file;
                }
            }
            closedir($dh);
        }
    }

    // ส่งกลับรายการไฟล์ในรูปแบบ JSON
    echo json_encode($files);
} else {
    // กรณีที่ไม่มี subject ส่งกลับมาหรือไม่พบโฟลเดอร์
    echo json_encode(array('error' => 'Subject not specified or directory does not exist.'));
}
?>
