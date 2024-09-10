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

// ดึงข้อมูลการเปรียบเทียบไฟล์ทั้งหมดจากตาราง file_similarity
$sql = "SELECT lab_id, file1, file2, similarity, hash_similarity, structure_similarity, token_similarity, embedding_similarity, tfidf_similarity FROM file_similarity ORDER BY lab_id ASC";
$result = $conn->query($sql);

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($result === FALSE) {
    die("Error retrieving file similarity data: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลการเปรียบเทียบไฟล์</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

    <!-- Include เมนู -->
    <?php include 'admin_menu.php'; ?>

    <div class="container mt-4">
        <h1 class="mb-4">ข้อมูลการเปรียบเทียบไฟล์</h1>
        
        <!-- ตารางแสดงข้อมูล -->
        <table id="similarityTable" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Lab ID</th>
                    <th>ไฟล์ 1</th>
                    <th>ไฟล์ 2</th>
                    <th>Similarity (%)</th>
                    <th>Hash Similarity (%)</th>
                    <th>Structural Similarity (%)</th>
                    <th>Token Similarity (%)</th>
                    <th>Embedding Similarity (%)</th>
                    <th>TF-IDF Similarity (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><a href="compare_files.php?lab_id=<?php echo htmlspecialchars($row['lab_id']); ?>&file1=<?php echo urlencode(htmlspecialchars($row['file1'])); ?>&file2=<?php echo urlencode(htmlspecialchars($row['file2'])); ?>" target="_blank"><?php echo htmlspecialchars($row['lab_id']); ?></a></td>
                            <td><a href="https://thailandfxwarrior.com/lab/student_c/<?php echo htmlspecialchars($row['file1']); ?>_<?php echo htmlspecialchars($row['lab_id']); ?>.c" target="_blank"><?php echo htmlspecialchars($row['file1']); ?></a></td>
                            <td><a href="https://thailandfxwarrior.com/lab/student_c/<?php echo htmlspecialchars($row['file2']); ?>_<?php echo htmlspecialchars($row['lab_id']); ?>.c" target="_blank"><?php echo htmlspecialchars($row['file2']); ?></a></td>
                            <td class="percent-cell"><?php echo htmlspecialchars($row['similarity']); ?>%</td>
                            <td class="percent-cell"><?php echo htmlspecialchars($row['hash_similarity']); ?>%</td>
                            <td class="percent-cell"><?php echo htmlspecialchars($row['structure_similarity']); ?>%</td>
                            <td class="percent-cell"><?php echo htmlspecialchars($row['token_similarity']); ?>%</td>
                            <td class="percent-cell"><?php echo htmlspecialchars($row['embedding_similarity']); ?>%</td>
                            <td class="percent-cell"><?php echo htmlspecialchars($row['tfidf_similarity']); ?>%</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">ไม่มีข้อมูลการเปรียบเทียบไฟล์</td>
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
            // กำหนด DataTables
            var table = $('#similarityTable').DataTable({
                "pageLength": 100, // กำหนดให้แสดง 100 records ต่อหน้า
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

            function getColor(value) {
                var red, green, blue;

                if (value >= 45) {
                    // ค่อยๆ ลดความสว่างจากสี Salmon (#FA8072) เป็นสีขาวเมื่อค่า 40% ถึง 100%
                    // สี Salmon: rgb(250, 128, 114)
                    // สีขาว: rgb(255, 255, 255)

                    var salmonRed = 250;
                    var salmonGreen = 128;
                    var salmonBlue = 114;

                    var percentage = (value - 40) / 60; // เปอร์เซ็นต์ระหว่าง 0 ถึง 1 (40% ถึง 100%)

                    red = Math.round(salmonRed - (255 - salmonRed) * percentage);
                    green = Math.round(salmonGreen - (255 - salmonGreen) * percentage);
                    blue = Math.round(salmonBlue - (255 - salmonBlue) * percentage);
                } else {
                    // ใช้สีขาวสำหรับค่า < 40
                    red = 255;
                    green = 255;
                    blue = 255;
                }

                return 'rgb(' + red + ',' + green + ',' + blue + ')';
            }

            // แปลงสีของ % ในแต่ละเซลล์ หลังจาก DataTables ตั้งค่าเสร็จ
            table.on('draw', function() {
                $('.percent-cell').each(function() {
                    var value = parseFloat($(this).text().replace('%', ''));
                    if (!isNaN(value) && value >= 0 && value <= 100) {
                        var color = getColor(value);
                        $(this).css('background-color', color);
                        $(this).css('color', '#333'); // ใช้สีเทาเข้มสำหรับตัวอักษร
                    }
                });
            }).draw(); // เรียกใช้งาน draw เพื่อให้แน่ใจว่า dataTable ถูกตั้งค่าเรียบร้อยแล้ว
            
        });
    </script>
</body>
</html>
