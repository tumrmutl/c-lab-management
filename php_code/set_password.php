<?php
// รหัสผ่านส่วนตัวที่ฝังในโค้ด
$admin_password = "113333";

// ตรวจสอบว่ามีการส่งข้อมูลผ่านฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $input_admin_password = $_POST['admin_password'];
    $new_password = $_POST['new_password'];

    // ตรวจสอบรหัสผ่านส่วนตัว
    if ($input_admin_password === $admin_password) {
        // เปิดไฟล์ pass.dat เพื่อเขียนรหัสผ่านใหม่
        $file_path = 'pass.dat';
        $file_handle = fopen($file_path, 'w');

        if ($file_handle) {
            // เขียนรหัสผ่านใหม่ลงในไฟล์
            fwrite($file_handle, $new_password);
            fclose($file_handle);
            echo "รหัสผ่านได้ถูกบันทึกเรียบร้อยแล้ว";
        } else {
            echo "ไม่สามารถเปิดไฟล์ pass.dat ได้";
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
    <title>ตั้งค่ารหัสผ่าน</title>
</head>
<body>
    <form method="POST" action="set_password.php">
        <label>รหัสผ่านส่วนตัว:</label>
        <input type="password" name="admin_password" required><br><br>

        <label>รหัสผ่านใหม่สำหรับใช้งาน:</label>
        <input type="password" name="new_password" required><br><br>

        <input type="submit" value="บันทึก">
    </form>
</body>
</html>
