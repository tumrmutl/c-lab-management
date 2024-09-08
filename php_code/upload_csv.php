<?php

function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception(".env file not found at $path");
    }

    $envVars = [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Skip lines that are comments or do not contain '='
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) {
            continue;
        }

        // Split key and value
        list($key, $value) = explode('=', $line, 2);
        $envVars[trim($key)] = trim($value);
    }

    return $envVars;
}

function handleCSVUpload($csvFile, $conn) {
    $csvFile = fopen($csvFile, 'r');
    if (!$csvFile) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to open CSV file']);
        return;
    }

    // Read data from CSV file line by line
    while (($row = fgetcsv($csvFile)) !== FALSE) {
        // Extract student_id and remove everything after '_'
        $std_id = $row[0];
        $std_id = explode('_', $std_id)[0]; // Extract only the part before '_'

        $lab_id = $row[1];
        $student_output = $row[2];
        $teacher_output = $row[3];
        $result = $row[4];

        // Update database
        $sql = "UPDATE ENGCC304 
                SET student_output='$student_output', teacher_output='$teacher_output', result='$result'
                WHERE std_id='$std_id' AND lab_id='$lab_id'";

        if (!$conn->query($sql)) {
            echo json_encode(['status' => 'error', 'message' => "Error updating record for student ID: $std_id, Error: " . $conn->error]);
            fclose($csvFile);
            return;
        }
    }

    fclose($csvFile);
}

try {
    // Load .env file
    $env = loadEnv(__DIR__ . '/.env');

    // Create connection
    $conn = new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_NAME']);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Check if file is uploaded
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $csvFilePath = $_FILES['csv_file']['tmp_name'];

        // Update database from CSV file
        handleCSVUpload($csvFilePath, $conn);

        // Move file to the desired location
        $uploadDir = __DIR__ . '/uploads/';
        $uploadFile = $uploadDir . basename($_FILES['csv_file']['name']);

        if (move_uploaded_file($csvFilePath, $uploadFile)) {
            echo json_encode(['status' => 'success', 'message' => 'File uploaded and processed successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file', 'details' => error_get_last()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No valid file uploaded']);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}

?>
