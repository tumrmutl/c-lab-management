<?php
// กำหนดโฟลเดอร์ที่ใช้เก็บไฟล์
$target_dir = "student_c/";
$uploadOk = 1;

// ตรวจสอบว่าไฟล์ถูกส่งมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // กำหนดชื่อไฟล์และนามสกุล
    $fileName = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $fileName;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // อ่านรหัสจากไฟล์ pass.dat
    $passFile = 'pass.dat';
    $validPassword = trim(file_get_contents($passFile));
    
    // ตรวจสอบว่ามีการกรอกรหัสผ่านหรือไม่
    if (isset($_POST['password'])) {
        $enteredPassword = $_POST['password'];
        
        // ตรวจสอบว่ารหัสผ่านถูกต้องหรือไม่
        if ($enteredPassword !== $validPassword) {
            echo "รหัสผ่านไม่ถูกต้อง.";
            $uploadOk = 0;
        }
    } else {
        echo "กรุณากรอกรหัสผ่าน.";
        $uploadOk = 0;
    }
    
    // ตรวจสอบว่ามีการเลือกวิชาไหม
    if (isset($_POST['course'])) {
        $course = $_POST['course'];
    } else {
        echo "กรุณาเลือกวิชา.";
        $uploadOk = 0;
    }

    // ตรวจสอบชนิดของไฟล์
    if ($uploadOk == 1) {
        // ตรวจสอบนามสกุลไฟล์
        if (!in_array($fileType, ['c', 'cpp', 'py', 'java'])) {
            echo "ขออภัย, เพียงแค่ไฟล์ .c, .cpp, .py และ .java เท่านั้นที่สามารถอัพโหลดได้.";
            $uploadOk = 0;
        }

        // ตรวจสอบชื่อไฟล์ว่ามีการต่อท้ายด้วย _labN.c หรือไม่
        if (!preg_match('/_lab\d+\.' . $fileType . '$/', $fileName)) {
            echo "ขออภัย, ชื่อไฟล์ต้องมีการต่อท้ายด้วย _labN." . $fileType . " (N เป็นตัวเลข).";
            $uploadOk = 0;
        }

        // ตรวจสอบว่าไฟล์ถูกอัพโหลดสำเร็จหรือไม่
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "ไฟล์ ". htmlspecialchars($fileName) . " ได้รับการอัพโหลดแล้ว.";
                echo " วิชาที่เลือก: " . htmlspecialchars($course);
            } else {
                echo "เกิดข้อผิดพลาดในการอัพโหลดไฟล์ของคุณ.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>อัพโหลดไฟล์</title>
</head>
<body>
    <h1>อัพโหลดไฟล์</h1>
    <form action="student_lab.php" method="post" enctype="multipart/form-data">
        รหัสผ่าน: <input type="password" name="password" id="password" required>
        <br>
        เลือกวิชา:
        <select name="course" required>
            <option value="">-- เลือกวิชา --</option>
            <option value="ENGCC304">ENGCC304 Computer Programming</option>
            <option value="ENGCE174">ENGCE174 Com Pro for Com Eng</option>
            <option value="ENGCE117">ENGCE117 OOP</option>
        </select>
        <br>
        เลือกไฟล์เพื่ออัพโหลด:
        <input type="file" name="fileToUpload" id="fileToUpload" required>
        <br>
        <input type="submit" value="อัพโหลดไฟล์" name="submit">
    </form>
</body>
</html>
