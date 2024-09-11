<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// if (isset($_SESSION['student_logged_in']) && $_SESSION['student_logged_in'] === true) {
//     header('Location: index.php');
//     exit();
// }

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

function validateResetToken($conn, $token) {
    $sql = "SELECT email FROM student WHERE reset_token = ? AND token_expires_at > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function updatePassword($conn, $email, $newPassword) {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE student SET password = ?, reset_token = NULL, token_expires_at = NULL WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashedPassword, $email);
    return $stmt->execute();
}

try {
    $env = loadEnv(__DIR__ . '/.env');
    $conn = new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_NAME']);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $success_message = $error_message = '';
    $token = $_GET['token'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $token = $_POST['token'];
        $newPassword = $_POST['password'];

        if (validateResetToken($conn, $token)) {
            $email = validateResetToken($conn, $token)['email'];
            if (updatePassword($conn, $email, $newPassword)) {
                $success_message = "Password reset successfully. You can now log in with your new password.";
            } else {
                $error_message = "Failed to reset password. Please try again.";
            }
        } else {
            $error_message = "Invalid or expired token.";
        }
    }

    $conn->close();

} catch (Exception $e) {
    $error_message = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Reset Password</h2>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <form method="POST" action="reset_password.php">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
    </form>
    <br /><a href="./index.php">< กลับหน้าแรก</a>
</div>
<!-- Include the footer -->
<?php include 'footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
