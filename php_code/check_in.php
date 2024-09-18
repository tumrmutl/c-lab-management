<?php
// Include the loadEnv function to load environment variables
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

// Load environment variables from .env file
$env = loadEnv(__DIR__ . '/.env');

// Assign the database credentials
$servername = $env['DB_HOST'];
$username = $env['DB_USERNAME'];
$password = $env['DB_PASSWORD'];
$dbname = $env['DB_NAME'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get student_id from query string
$student_id = $_GET['student_id'];

// Get today's date
$today = date("Y-m-d");

// Insert a new check-in for the student
$sql = "INSERT INTO check_ins (student_id, check_in_date) VALUES ('$student_id', '$today')";

if ($conn->query($sql) === TRUE) {
    echo "Check-in successful!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
