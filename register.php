<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_connect.php';
require_once 'csrf_token.php';

$token = generateToken();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateToken($_POST['csrf_token'])) {
        die("CSRF validation failed");
    }

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        $errors[] = "ชื่อผู้ใช้นี้ถูกใช้แล้ว";
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $errors[] = "อีเมลนี้ถูกใช้แล้ว";
    }

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed]);
        header("Location: index.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>สมัครสมาชิก</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>สมัครสมาชิก</h2>
    <?php foreach ($errors as $error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endforeach; ?>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= $token ?>">
        <input type="text" name="username" placeholder="ชื่อผู้ใช้" required><br>
        <input type="email" name="email" placeholder="อีเมล"><br>
        <input type="password" name="password" placeholder="รหัสผ่าน" required><br>
        <button type="submit">สมัคร</button>
    </form>
    <p>ย้อนกลับหน้าหลัก <a href="index.php">เข้าสู่ระบบ</a></p>
</div>
</body>
</html>
<style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0f7fa, #fff);
            color: #333;
            padding: 50px;
        }
</style>