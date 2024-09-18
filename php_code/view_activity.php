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
$student_id = $_GET['student_id'] ?? 'default_student';

// Get check-ins for the student for the past year
$sql = "SELECT check_in_date, COUNT(*) as total FROM check_ins 
        WHERE student_id = '$student_id' 
        GROUP BY check_in_date";
$result = $conn->query($sql);
$check_ins = [];

// Store results in an associative array
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $check_ins[$row['check_in_date']] = $row['total'];
    }
}

// Get current date and one year ago
$currentDate = new DateTime();
$oneYearAgo = (new DateTime())->modify('-1 year');

// Generate a 365-day contribution calendar
echo '<div class="container">';
echo '<h3>Contribution Activity for Student ID: ' . $student_id . '</h3>';
echo '<div class="calendar-container" style="display: flex; flex-wrap: wrap;">';

while ($oneYearAgo <= $currentDate) {
    $day = $oneYearAgo->format('Y-m-d');
    $count = $check_ins[$day] ?? 0;

    // Define background color based on check-in count (like GitHub's heatmap)
    $bgColor = '#ebedf0'; // Default (no check-ins)
    if ($count > 0 && $count <= 2) {
        $bgColor = '#9be9a8';
    } elseif ($count > 2 && $count <= 4) {
        $bgColor = '#40c463';
    } elseif ($count > 4) {
        $bgColor = '#30a14e';
    }

    echo '<div class="calendar-day" style="width: 13px; height: 13px; margin: 2px; background-color: ' . $bgColor . ';" title="Date: ' . $day . ' - Check-ins: ' . $count . '"></div>';
    $oneYearAgo->modify('+1 day');
}

echo '</div>';
echo '</div>';

$conn->close();
?>
