<?php
// กำหนดโฟลเดอร์ที่ใช้เก็บไฟล์
$target_dir = "student_c/";
$uploadOk = 1;

// ตรวจสอบว่าไฟล์ถูกส่งมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // กำหนดชื่อไฟล์และนามสกุล
    $fileName = basename($_FILES["fileToUpload"]["name"]);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // อ่านรหัสจากไฟล์ pass.dat
    $passFile = 'pass.dat';
    $validPassword = trim(file_get_contents($passFile));
    
    // ตรวจสอบว่ามีการกรอกรหัสผ่านหรือไม่
    if (isset($_POST['password'])) {
        $enteredPassword = $_POST['password'];
        
        // ตรวจสอบว่ารหัสผ่านถูกต้องหรือไม่
        if ($enteredPassword !== $validPassword) {
            echo "<div class='alert alert-danger' role='alert'>รหัสผ่านไม่ถูกต้อง.</div>";
            $uploadOk = 0;
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>กรุณากรอกรหัสผ่าน.</div>";
        $uploadOk = 0;
    }
    
    // ตรวจสอบว่ามีการเลือกวิชาไหม
    if (isset($_POST['course'])) {
        $course = $_POST['course'];
    } else {
        echo "<div class='alert alert-danger' role='alert'>กรุณาเลือกวิชา.</div>";
        $uploadOk = 0;
    }

    // ตรวจสอบว่ามีการกรอกข้อมูลรหัสนักศึกษาและเลข Lab หรือไม่
    if (isset($_POST['student_id']) && isset($_POST['lab_number'])) {
        $student_id = $_POST['student_id'];
        $lab_number = $_POST['lab_number'];
    } else {
        echo "<div class='alert alert-danger' role='alert'>กรุณากรอกรหัสนักศึกษาและเลข Lab.</div>";
        $uploadOk = 0;
    }

    // ตรวจสอบชนิดของไฟล์
    if ($uploadOk == 1) {
        // ตรวจสอบนามสกุลไฟล์
        if (!in_array($fileType, ['c', 'cpp', 'py', 'java'])) {
            echo "<div class='alert alert-danger' role='alert'>ขออภัย, เพียงแค่ไฟล์ .c, .cpp, .py และ .java เท่านั้นที่สามารถอัพโหลดได้.</div>";
            $uploadOk = 0;
        }

        // ตรวจสอบชื่อไฟล์ว่ามีการต่อท้ายด้วย _labN.c หรือไม่
        // if (!preg_match('/_lab\d+\.' . $fileType . '$/', $fileName)) {
        //     echo "<div class='alert alert-danger' role='alert'>ขออภัย, ชื่อไฟล์ต้องมีการต่อท้ายด้วย _labN." . $fileType . " (N เป็นตัวเลข).</div>";
        //     $uploadOk = 0;
        // }

        // ตรวจสอบว่าไฟล์ถูกอัพโหลดสำเร็จหรือไม่
        if ($uploadOk == 1) {
            // สร้างชื่อไฟล์ใหม่ตามรูปแบบ รหัสนักศึกษา_เลขLab.นามสกุล
            $newFileName = $student_id . "_" . $lab_number . "." . $fileType;
            $target_file = $target_dir . $newFileName;

            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "<div class='alert alert-success' role='alert'>ไฟล์ " . htmlspecialchars($newFileName) . " ได้รับการอัพโหลดแล้ว.</div>";
                echo "<div class='alert alert-info' role='alert'>วิชาที่เลือก: " . htmlspecialchars($course) . "</div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>เกิดข้อผิดพลาดในการอัพโหลดไฟล์ของคุณ.</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Upload File</h1>
        <form action="student_lab.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="password">รหัสผ่าน</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="student_id">รหัสนักศึกษา</label>
                <input type="text" class="form-control" id="student_id" name="student_id" required>
            </div>
            <div class="form-group">
                <label for="lab_number">เลข Lab</label>
                <input type="text" class="form-control" id="lab_number" name="lab_number" required>
            </div>
            <div class="form-group">
                <label for="course">เลือกวิชา</label>
                <select class="form-control" id="course" name="course" required>
                    <option value="">-- เลือกวิชา --</option>
                    <option value="ENGCC304">ENGCC304 Computer Programming</option>
                    <option value="ENGCE174">ENGCE174 Computer Programming for Computer Engineering</option>
                    <option value="ENGCE117">ENGCE117 Object-oriented Programming</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fileToUpload">เลือกไฟล์เพื่ออัพโหลด</label>
                <input type="file" class="form-control-file" id="fileToUpload" name="fileToUpload" required>
            </div>
            <button type="submit" class="btn btn-primary">อัพโหลดไฟล์</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
