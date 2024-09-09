<?php

function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception(".env file not found at $path");
    }

    $envVars = [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);
        $envVars[trim($key)] = trim($value);
    }

    return $envVars;
}

function getStudentLabData($student_id, $conn) {
    $student_id = $conn->real_escape_string($student_id);

    $sql = "SELECT * FROM LAB WHERE std_id='$student_id' ORDER BY `lab_id` ASC";
    $result = $conn->query($sql);

    if ($result === FALSE) {
        error_log("Error retrieving data for student ID: $student_id, Error: " . $conn->error);
        return [];
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

try {
    $env = loadEnv(__DIR__ . '/.env');

    $conn = new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_NAME']);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $student_id = '';
    $student_data = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['student_id'])) {
        $student_id = $_POST['student_id'];
        $student_data = getStudentLabData($student_id, $conn);
    }

} catch (Exception $e) {
    echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Submission Status</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .pre-wrap {
            white-space: pre-wrap; /* CSS 2.1 */
            white-space: -moz-pre-wrap; /* Firefox */
            white-space: -pre-wrap; /* Opera 4-6 */
            white-space: -o-pre-wrap; /* Opera 7+ */
            word-wrap: break-word; /* IE 5.5+ */
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Check Your Lab Submission Status</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="student_id">Student ID</label>
                <input type="text" class="form-control" id="student_id" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Check Status</button>
        </form>

        <?php if (!empty($student_data)): ?>
            <h3 class="mt-4">Submission Details</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Lab</th>
                        <th>Student Output</th>
                        <th>Teacher Output</th>
                        <th>Result</th>
                        <th>Submission Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($student_data as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['lab_id']); ?></td>
                            <td class="pre-wrap"><?php echo (htmlspecialchars(trim($row['student_output']))); ?></td>
                            <td class="pre-wrap"><?php echo (htmlspecialchars(trim($row['teacher_output']))); ?></td>
                            <td>
                                <?php 
                                if ($row['result'] == '1') {
                                    echo '<i class="fas fa-check-circle" style="color: green;"></i>';
                                } else {
                                    echo '<i class="fas fa-times-circle" style="color: red;"></i>';
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <div class="alert alert-warning" role="alert">No records found for Student ID: <?php echo htmlspecialchars($student_id); ?></div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
