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
            padding-right: 30px;
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
    <h2>เข้าสู่ระบบ</h2>
    <form action="login.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $token ?>">
        <input type="text" name="username" placeholder="ชื่อผู้ใช้" required><br>

        <div class="password-container">
            <input type="password" name="password" placeholder="รหัสผ่าน" required id="password">
            <button type="button" class="toggle-password" onclick="togglePassword()">👁️</button>
        </div>
        <br>

        <button type="submit">เข้าสู่ระบบ</button>
    </form>
    <p>ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
</div>

<script>
function togglePassword() {
    const input = document.getElementById("password");
    const btn = event.currentTarget;
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
