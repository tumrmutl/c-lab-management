<?php

function av( $input ) {
    echo '<pre>' ;
    print_r( $input ) ;
    echo '</pre>' ;
}

// Function to load environment variables from .env file
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

// Function to handle the JSON data and process it into the MySQL database
function handleJSONData($data, $conn, $subject) {
    $table_name = $conn->real_escape_string($subject) . '_similarity'; // Use subject to create table name

    foreach ($data as $row) {
        // Map JSON fields to MySQL fields
        $lab_id = $conn->real_escape_string($row['Lab ID']);
        $file1 = $conn->real_escape_string($row['File 1']);
        $file2 = $conn->real_escape_string($row['File 2']);
        $similarity = $conn->real_escape_string($row['Similarity (%)']);
        $hash_similarity = $conn->real_escape_string($row['Hash Similarity (%)']);
        $structure_similarity = $conn->real_escape_string($row['Structural Similarity (%)']);
        $token_similarity = $conn->real_escape_string($row['Token Similarity (%)']);
        $embedding_similarity = $conn->real_escape_string($row['Embedding Similarity (%)']);
        $tfidf_similarity = $conn->real_escape_string($row['TF-IDF Similarity (%)']);

        // Check if a record already exists for the same lab_id, file1, and file2
        $sql_check = "SELECT COUNT(*) FROM $table_name WHERE lab_id='$lab_id' AND file1='$file1' AND file2='$file2'";
        $result_check = $conn->query($sql_check);
        $count = $result_check->fetch_array()[0];

        if ($count > 0) {
            // If record exists, update it
            $sql = "UPDATE $table_name
                    SET similarity='$similarity', hash_similarity='$hash_similarity', structure_similarity='$structure_similarity',
                    token_similarity='$token_similarity', embedding_similarity='$embedding_similarity', tfidf_similarity='$tfidf_similarity'
                    WHERE lab_id='$lab_id' AND file1='$file1' AND file2='$file2'";
        } else {
            // If record does not exist, insert a new one
            $sql = "INSERT INTO $table_name (lab_id, file1, file2, similarity, hash_similarity, structure_similarity, token_similarity, embedding_similarity, tfidf_similarity)
                    VALUES ('$lab_id', '$file1', '$file2', '$similarity', '$hash_similarity', '$structure_similarity', '$token_similarity', '$embedding_similarity', '$tfidf_similarity')";
        }

        // Execute the query and log any errors
        if (!$conn->query($sql)) {
            error_log("Error updating/adding record for Lab ID: $lab_id, File1: $file1, File2: $file2, Error: " . $conn->error);
            return ['status' => 'error', 'message' => 'Database update failed'];
        }
    }

    return ['status' => 'success', 'message' => 'Data processed successfully'];
}

try {
    // Load environment variables
    $env = loadEnv(__DIR__ . '/.env');

    // Connect to MySQL database
    $conn = new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_NAME']);

    // Check for connection errors
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Read subject from $_GET parameter
    if (!isset($_GET['subject']) || empty($_GET['subject'])) {
        throw new Exception("Subject not provided");
    }
    $subject = $_GET['subject'];  // Get the subject code from the GET parameter

    //av( $subject ) ;

    // Read raw POST data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Validate JSON data
    if (json_last_error() === JSON_ERROR_NONE) {
        // Handle JSON data with the subject code
        $result = handleJSONData($data, $conn, $subject);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
    }

} catch (Exception $e) {
    // Handle any exceptions and return error message
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    // Close the database connection
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}

?>
