<?php
// ตรวจสอบสถานะของเซสชันก่อนเรียก session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function generateToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // สร้าง token ใหม่
    }
    return $_SESSION['csrf_token'];
}

// เรียกใช้ฟังก์ชันเพื่อสร้าง CSRF token
$token = generateToken();
?>

<!DOCTYPE html>
<html>
<head>
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>เข้าสู่ระบบ</h2>
    <form action="login.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $token ?>">
        <input type="text" name="username" placeholder="ชื่อผู้ใช้" required><br>
        <input type="password" name="password" placeholder="รหัสผ่าน" required><br>
        <button type="submit">เข้าสู่ระบบ</button>
    </form>
    <p>ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
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