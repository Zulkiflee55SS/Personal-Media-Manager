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

        <div class="password-container">
            <input type="password" name="password" placeholder="รหัสผ่าน" required id="password">
            <button type="button" class="toggle-password" onclick="togglePassword('password', this)">👁️</button>
        </div>
        <br>

        <button type="submit">เข้าสู่ระบบ</button>
    </form>
    <p>ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
</div>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
        btn.textContent = "🙈"; // เปลี่ยนเป็นไอคอนซ่อน
    } else {
        input.type = "password";
        btn.textContent = "👁️"; // เปลี่ยนเป็นไอคอนแสดง
    }
}
</script>
</body>
</html>

<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #a8edea, #fed6e3);
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
