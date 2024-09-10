<?php
session_start();

// ลบข้อมูลในเซสชัน
session_unset();
session_destroy();

// ลบคุกกี้การเข้าสู่ระบบ
setcookie('admin_logged_in', '', time() - 3600, '/');

// นำผู้ใช้กลับไปที่หน้า index.php
header('Location: index.php');
exit();
?>
