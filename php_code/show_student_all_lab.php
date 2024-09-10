<?php


// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบแล้วหรือยัง
session_start();
if (!isset($_COOKIE['admin_logged_in']) || $_COOKIE['admin_logged_in'] !== 'true') {
    header('Location: index.php');
    exit();
}

// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$env = parse_ini_file(__DIR__ . '/.env');
$conn = new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_NAME']);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลการส่ง Lab ของนักศึกษาทั้งหมด
$sql = "SELECT std_id, lab_id, student_output, teacher_output, result, timestamp
        FROM LAB 
        ORDER BY std_id ASC, lab_id ASC";
$result = $conn->query($sql);

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($result === FALSE) {
    die("Error retrieving student lab submissions: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลการส่ง Lab ของนักศึกษา</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

    <!-- Include เมนู -->
    <?php include 'admin_menu.php'; ?>

    <div class="container mt-4">
        <h1 class="mb-4">ข้อมูลการส่ง Lab ของนักศึกษา</h1>
        
        <!-- ตารางแสดงข้อมูล -->
        <table id="labTable" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>รหัสนักศึกษา</th>
                    <th>เลขที่ Lab</th>
                    <th>คำตอบของนักศึกษา</th>
                    <th>คำตอบของอาจารย์</th>
                    <th>ผลลัพธ์</th>
                    <th>เวลาส่ง Lab</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['std_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['lab_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['student_output']); ?></td>
                            <td><?php echo htmlspecialchars($row['teacher_output']); ?></td>
                            <td>
                                <?php if ($row['result'] == 1): ?>
                                    <i class="fas fa-check-circle" style="color: green;"></i>
                                <?php else: ?>
                                    <i class="fas fa-times-circle" style="color: red;"></i>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">ไม่มีข้อมูลการส่ง Lab</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- jQuery และ DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#labTable').DataTable({
                "language": {
                    "lengthMenu": "แสดง _MENU_ รายการต่อหน้า",
                    "zeroRecords": "ไม่พบข้อมูล",
                    "info": "แสดงหน้าที่ _PAGE_ จาก _PAGES_",
                    "infoEmpty": "ไม่มีข้อมูล",
                    "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)",
                    "search": "ค้นหา:",
                    "paginate": {
                        "first": "หน้าแรก",
                        "last": "หน้าสุดท้าย",
                        "next": "ถัดไป",
                        "previous": "ก่อนหน้า"
                    }
                }
            });
        });
    </script>
</body>
</html>
