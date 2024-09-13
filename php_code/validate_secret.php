<?php
header('Content-Type: text/plain');

function getSecretCode() {
    $file = __DIR__ . '/pass.dat';
    if (!file_exists($file)) {
        throw new Exception("pass.dat file not found.");
    }
    return trim(file_get_contents($file));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $secret_code = $_POST['secret_code'] ?? '';
    if ($secret_code === getSecretCode()) {
        echo 'valid';
    } else {
        echo 'invalid';
    }
}
?>
