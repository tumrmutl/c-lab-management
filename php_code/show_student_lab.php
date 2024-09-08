<?php
header('Content-Type: application/json');

$directory = 'student_c'; // โฟลเดอร์ที่เก็บไฟล์
$base_url = 'https://thailandfxwarrior.com/lab/student_c/';
$files = array();

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

echo json_encode($files);
?>
