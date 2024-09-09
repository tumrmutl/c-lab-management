<?php

function av( $input ) {
    echo '<pre>' ;
    print_r( $input ) ;
    echo '</pre>' ;
}

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

// Load .env file
$env = loadEnv(__DIR__ . '/.env');

// Create connection
$conn = new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_NAME']);

if ($conn->connect_error) {
    throw new Exception("Connection failed: " . $conn->connect_error);
}

$sql_check = "SELECT * FROM LAB ;";
$result_check = $conn->query($sql_check);
$count = $result_check->fetch_array();

av( $count ) ;

// foreach( $count as $key => $value ) {
//     echo $key . " = " . $value . "<br />" ;
// }