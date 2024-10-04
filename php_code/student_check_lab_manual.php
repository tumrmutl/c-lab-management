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
                // ตรวจสอบว่าเป็นไฟล์ .c หรือ .cpp เท่านั้น
                if ($file != "." && $file != ".." && !is_dir($directory . '/' . $file)) {
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    if (in_array($extension, ['c', 'cpp'])) {
                        // ตรวจสอบรูปแบบชื่อไฟล์ (student_id_course_code_labX.c)
                        if (preg_match('/^\d+_[A-Za-z0-9]+_lab\d+\.(c|cpp)$/', $file)) {
                            $files[] = $base_url . $file;
                        }
                    }
                }
            }
            closedir($dh);
        }
    }

    // ส่งกลับรายการไฟล์ในรูปแบบ JSON
    if (!empty($files)) {
        echo json_encode($files);
    } else {
        echo json_encode(array('error' => 'No valid files found.'));
    }
} else {
    // กรณีที่ไม่มี subject ส่งกลับมาหรือไม่พบโฟลเดอร์
    echo json_encode(array('error' => 'Subject not specified or directory does not exist.'));
}
?>
