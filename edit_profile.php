<?php
require 'auth.php';
requireLogin();  // ตรวจสอบการล็อกอิน

require 'db_connect.php';

$id = $_SESSION['user_id'];  // ใช้ ID ของผู้ใช้ที่ล็อกอิน

// ดึงข้อมูลของผู้ใช้จากฐานข้อมูล
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    die("ไม่พบผู้ใช้ในระบบ");
}

// ถ้าส่งข้อมูลมาแบบ POST ให้ทำการอัปเดตข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // อัปเดตข้อมูลในฐานข้อมูล
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $stmt->execute([$username, $email, $id]);

    header("Location: dashboard.php");  // หลังจากบันทึก ให้กลับไปที่หน้าแดชบอร์ด
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>แก้ไขโปรไฟล์</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>✏️ แก้ไขโปรไฟล์ของคุณ</h2>
    <form method="POST">
        <label>ชื่อผู้ใช้:</label><br>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>

        <button type="submit">💾 บันทึก</button>
    </form>
    <br>
    <a href="dashboard.php">← กลับ</a>
</div>
</body>
</html>

<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(to right, #e0f7fa, #fff);
        color: #333;
    }

    .container {
        max-width: 1100px;
        margin: 50px auto;
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    h2 {
        color: #007bff;
        font-size: 32px;
        margin-bottom: 25px;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #6c757d;
        font-size: 14px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 14px;
        text-align: center;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }

    th {
        background-color: #f8f9fa;
        color: #495057;
    }

    tr:nth-child(even) td {
        background: #f9f9f9;
    }

    tr:hover td {
        background: #eef1f5;
    }

    img.file-preview, video.file-preview {
        max-width: 100px;
        max-height: 100px;
        border-radius: 8px;
    }

    .action-links a {
        margin: 0 6px;
        color: #333;
        text-decoration: none;
    }

    .action-links a:hover {
        color: #007bff;
    }

    .no-files {
        text-align: center;
        padding: 20px;
        color: #888;
    }
</style>
