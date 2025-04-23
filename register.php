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
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $errors[] = "รหัสผ่านไม่ตรงกัน";
    }

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
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #a8edea, #fed6e3);
            color: #333;
            padding: 50px;
        }

        .password-container {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .password-container input[type="password"],
        .password-container input[type="text"] {
            width: 100%;
            padding-right: 40px;
            box-sizing: border-box;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #666;
        }

        .toggle-password:focus {
            outline: none;
        }
    </style>
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

        <div class="password-container">
            <input type="password" name="password" placeholder="รหัสผ่าน" required id="password">
            <button type="button" class="toggle-password" onclick="togglePassword('password', this)">👁️</button>
        </div>
        <br>

        <div class="password-container">
            <input type="password" name="confirm_password" placeholder="ยืนยันรหัสผ่าน" required id="confirm_password">
            <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', this)">👁️</button>
        </div>
        <br>

        <button type="submit">สมัคร</button>
    </form>
    <p>ย้อนกลับหน้าหลัก <a href="index.php">เข้าสู่ระบบ</a></p>
</div>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
        btn.textContent = "🙈";
    } else {
        input.type = "password";
        btn.textContent = "👁️";
    }
}
</script>
</body>
</html>
