<?php
// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบแล้วหรือยัง
session_start();
if (!isset($_COOKIE['admin_logged_in']) || $_COOKIE['admin_logged_in'] !== 'true') {
    header('Location: index.php');
    exit();
}

// นำเข้า config.php เพื่อดึงรหัสวิชาจากอาเรย์ $table
include 'config.php';

// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$env = parse_ini_file(__DIR__ . '/.env');
$conn = new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_NAME']);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ฟังก์ชันดึงข้อมูลจากฐานข้อมูลตามรหัสวิชา
function getSimilarityData($conn, $course_code) {
    $table_name = $course_code . '_similarity'; // ใช้ตารางที่ลงท้ายด้วย _similarity ตามรหัสวิชา
    $sql = "SELECT lab_id, file1, file2, similarity, hash_similarity, structure_similarity, token_similarity, embedding_similarity, tfidf_similarity
            FROM $table_name
            ORDER BY lab_id ASC";

    $result = $conn->query($sql);
    if ($result === FALSE) {
        error_log("Error retrieving similarity data for $course_code: " . $conn->error);
        return [];
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

// ดึงข้อมูลการเปรียบเทียบไฟล์ของทุกวิชา
$all_similarity_data = [];
foreach ($table as $course_code) {
    $all_similarity_data[$course_code] = getSimilarityData($conn, $course_code);
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

        <!-- วนลูปแสดงข้อมูลสำหรับแต่ละวิชา -->
        <?php foreach ($all_similarity_data as $course_code => $similarity_data): ?>
            <h3>วิชา: <?php echo htmlspecialchars($course_code); ?></h3>

            <table id="similarityTable_<?php echo htmlspecialchars($course_code); ?>" class="table table-striped table-bordered">
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
                    <?php if (count($similarity_data) > 0): ?>
                        <?php foreach ($similarity_data as $row): ?>
                            <?php
                                $file_path1 = $course_code . "/" . $row['file1'] ;
                                $file_path2 = $course_code . "/" . $row['file2'] ;
                            ?>
                            <tr>
                                <td><a href="compare_files.php?lab_id=<?php echo htmlspecialchars($row['lab_id']); ?>&file1=<?php echo urlencode(htmlspecialchars($row['file1'])); ?>&file2=<?php echo urlencode(htmlspecialchars($row['file2'])); ?>" target="_blank"><?php echo htmlspecialchars($row['lab_id']); ?></a></td>
                                <td><a href="https://thailandfxwarrior.com/lab/student_c/<?php echo htmlspecialchars($file_path1); ?>_<?php echo htmlspecialchars($row['lab_id']); ?>.c" target="_blank"><?php echo htmlspecialchars($row['file1']); ?></a></td>
                                <td><a href="https://thailandfxwarrior.com/lab/student_c/<?php echo htmlspecialchars($file_path2); ?>_<?php echo htmlspecialchars($row['lab_id']); ?>.c" target="_blank"><?php echo htmlspecialchars($row['file2']); ?></a></td>
                                <td class="percent-cell"><?php echo htmlspecialchars($row['similarity']); ?>%</td>
                                <td class="percent-cell"><?php echo htmlspecialchars($row['hash_similarity']); ?>%</td>
                                <td class="percent-cell"><?php echo htmlspecialchars($row['structure_similarity']); ?>%</td>
                                <td class="percent-cell"><?php echo htmlspecialchars($row['token_similarity']); ?>%</td>
                                <td class="percent-cell"><?php echo htmlspecialchars($row['embedding_similarity']); ?>%</td>
                                <td class="percent-cell"><?php echo htmlspecialchars($row['tfidf_similarity']); ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">ไม่มีข้อมูลการเปรียบเทียบไฟล์สำหรับวิชานี้</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <br />
        <?php endforeach; ?>
    </div>

    <!-- jQuery และ DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // กำหนด DataTables สำหรับแต่ละตาราง
            <?php foreach ($table as $course_code): ?>
                $('#similarityTable_<?php echo htmlspecialchars($course_code); ?>').DataTable({
                    "pageLength": 100,
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
            <?php endforeach; ?>

            function getColor(value) {
                var red, green, blue;

                if (value >= 45) {
                    var salmonRed = 250;
                    var salmonGreen = 128;
                    var salmonBlue = 114;

                    var percentage = (value - 40) / 60;

                    red = Math.round(salmonRed - (255 - salmonRed) * percentage);
                    green = Math.round(salmonGreen - (255 - salmonGreen) * percentage);
                    blue = Math.round(salmonBlue - (255 - salmonBlue) * percentage);
                } else {
                    red = 255;
                    green = 255;
                    blue = 255;
                }

                return 'rgb(' + red + ',' + green + ',' + blue + ')';
            }

            $('table').on('draw', function() {
                $('.percent-cell').each(function() {
                    var value = parseFloat($(this).text().replace('%', ''));
                    if (!isNaN(value) && value >= 0 && value <= 100) {
                        var color = getColor(value);
                        $(this).css('background-color', color);
                        $(this).css('color', '#333');
                    }
                });
            }).draw();
        });
    </script>
</body>
</html>
