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

function handleJSONData($data, $conn, $tableName) {
    foreach ($data as $row) {
        $std_id = $conn->real_escape_string($row['student id']);
        $lab_id = $conn->real_escape_string($row['lab']);
        $student_output = $conn->real_escape_string($row['student output']);
        $teacher_output = $conn->real_escape_string($row['teacher output']);
        $result = $conn->real_escape_string($row['result']);

        $sql_check = "SELECT COUNT(*) FROM $tableName WHERE std_id='$std_id' AND lab_id='$lab_id'";
        $result_check = $conn->query($sql_check);
        $count = $result_check->fetch_array()[0];

        if ($count > 0) {
            $sql = "UPDATE $tableName 
                    SET student_output='$student_output', teacher_output='$teacher_output', result='$result'
                    WHERE std_id='$std_id' AND lab_id='$lab_id'";
        } else {
            $sql = "INSERT INTO $tableName (std_id, lab_id, student_output, teacher_output, result)
                    VALUES ('$std_id', '$lab_id', '$student_output', '$teacher_output', '$result')";
        }

        if (!$conn->query($sql)) {
            error_log("Error updating/adding record for student ID: $std_id, Error: " . $conn->error);
            return ['status' => 'error', 'message' => 'Database update failed'];
        }
    }

    return ['status' => 'success', 'message' => 'Data processed successfully'];
}

try {
    $env = loadEnv(__DIR__ . '/.env');

    $conn = new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_NAME']);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Get the subject code from GET parameter
    $subject_code = isset($_GET['subject']) ? $conn->real_escape_string($_GET['subject']) : '';

    if (empty($subject_code)) {
        throw new Exception("Subject code not provided");
    }

    // Read raw POST data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        // Handle JSON data
        $result = handleJSONData($data, $conn, $subject_code);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}

?>
