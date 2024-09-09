<?php
// รหัสผ่านส่วนตัวที่ฝังในโค้ด
$admin_password = "113333";

// ตรวจสอบว่ามีการส่งข้อมูลผ่านฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $input_admin_password = $_POST['admin_password'];

    // ตรวจสอบรหัสผ่านส่วนตัว
    if ($input_admin_password === $admin_password) {
        // เปิดไฟล์ pass.dat เพื่ออ่านรหัสผ่าน
        $file_path = 'pass.dat';
        if (file_exists($file_path)) {
            $file_handle = fopen($file_path, 'r');

            if ($file_handle) {
                // อ่านข้อมูลจากไฟล์
                $stored_password = fread($file_handle, filesize($file_path));
                fclose($file_handle);

                // แสดงรหัสผ่านที่เก็บในไฟล์
                echo "รหัสผ่านในปัจจุบันคือ: " . htmlspecialchars($stored_password);
            } else {
                echo "ไม่สามารถเปิดไฟล์ pass.dat ได้";
            }
        } else {
            echo "ไฟล์ pass.dat ไม่พบ";
        }
    } else {
        // แจ้งเตือนผู้ใช้ว่ารหัสผ่านไม่ถูกต้อง
        echo "คุณกำลังละเมิดข้อมูลของเรา!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ดูรหัสผ่าน</title>
</head>
<body>
    <form method="POST" action="get_password.php">
        <label>รหัสผ่านส่วนตัว:</label>
        <input type="password" name="admin_password" required><br><br>

        <input type="submit" value="ดูรหัสผ่าน">
    </form>
</body>
</html>
