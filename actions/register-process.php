<?php
session_start();
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../register.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Maglagay ng valid na email.';
} elseif (strlen($password) < 8) {
    $_SESSION['error'] = 'Dapat hindi bababa sa 8 characters ang password.';
} elseif ($password !== $confirm_password) {
    $_SESSION['error'] = 'Hindi magkapareho ang password at confirmation.';
} else {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");

    if (!$stmt) {
        die('Unable to prepare account registration.');
    }

    $stmt->bind_param("ss", $email, $password_hash);
    if ($stmt->execute()) {
        $stmt->close();
        $_SESSION['success'] = 'Registration successful. Mag-login gamit ang bagong account.';
        header("Location: ../index.php");
        exit;
    }

    $_SESSION['error'] = $stmt->errno === 1062
        ? 'May account na gamit ang email na ito.'
        : 'Hindi nagawa ang account. Subukan ulit.';
    $stmt->close();
}

header("Location: ../register.php");
exit;
