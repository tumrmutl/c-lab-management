<?php

function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception(".env file not found at $path");
    }

    $envVars = [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // ข้ามบรรทัดที่เป็น comment
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // แยก key และ value
        list($key, $value) = explode('=', $line, 2);
        $envVars[trim($key)] = trim($value);
    }

    return $envVars;
}


// โหลดค่าในไฟล์ .env
$env = loadEnv(__DIR__ . '/.env');

// รับค่าจาก .env
$servername = $env['DB_HOST'];
$username = $env['DB_USERNAME'];
$password = $env['DB_PASSWORD'];
$dbname = $env['DB_NAME'];

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



/////////////


echo "Connected successfully<br>";

// ทำการ query ข้อมูลจาก table `ENGCC304`
$sql = "SELECT * FROM ENGCC304";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // แสดงข้อมูลจาก query
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . " - Name: " . $row["name"] . "<br>";
    }
} else {
    echo "0 results";
}

/////////////

// // ตรวจสอบว่ามีการอัพโหลดไฟล์
// if (isset($_FILES['csv_file']['tmp_name'])) {
//     $csvFile = fopen($_FILES['csv_file']['tmp_name'], 'r');

//     // อ่านข้อมูลในไฟล์ CSV ทีละบรรทัด
//     while (($row = fgetcsv($csvFile)) !== FALSE) {
//         $student_id = $row[0];
//         $lab_number = $row[1];
//         $student_output = $row[2];
//         $teacher_output = $row[3];
//         $result = $row[4];

//         // อัปเดตฐานข้อมูล
//         $sql = "UPDATE student_table SET student_output='$student_output', teacher_output='$teacher_output', result='$result' WHERE student_id='$student_id' AND lab_number='$lab_number'";

//         if ($conn->query($sql) === TRUE) {
//             echo "Record updated successfully for student ID: $student_id\n";
//         } else {
//             echo "Error updating record for student ID: $student_id - " . $conn->error . "\n";
//         }
//     }

//     fclose($csvFile);
// } else {
//     echo "No file uploaded.";
// }

$conn->close();
?>
