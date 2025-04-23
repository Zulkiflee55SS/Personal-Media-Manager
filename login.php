<?php
// ตรวจสอบสถานะของเซสชันก่อนเรียก session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'db_connect.php';
require 'csrf_token.php';

$loginError = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบ CSRF Token
    if (!validateToken($_POST['csrf_token'])) {
        die("CSRF validation failed");
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    // ตรวจสอบข้อมูลผู้ใช้ในฐานข้อมูล
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // สร้าง session สำหรับผู้ใช้ที่เข้าสู่ระบบสำเร็จ
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $loginError = true;
    }
}
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

    <?php if ($loginError): ?>
        <p class="error-msg" id="error-msg">ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!</p>
        <script>
            setTimeout(function () {
                const msg = document.getElementById('error-msg');
                if (msg) msg.style.display = 'none';
            }, 3000);
        </script>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= generateToken() ?>">
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
    .error-msg {
        color: red;
        text-align: center;
        font-weight: bold;
        animation: fadein 0.5s;
    }

    @keyframes fadein {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
</style>
