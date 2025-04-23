<?php
session_start();
$user_id = $_SESSION['user_id'];

require 'auth.php';
requireLogin();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แดชบอร์ด | ไฟล์ของฉัน</title>
    <style>
        * {
            box-sizing: border-box;
        }
        @import url('https://fonts.googleapis.com/css2?family=Prompt&display=swap');

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0f7fa, #fff);
            color: #333;
        }


        .container {
            max-width: 900px;
            margin: 80px auto;
            text-align: center;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        h2 {
            font-size: 36px;  /* เพิ่มขนาดตัวอักษร */
            margin-bottom: 30px;
            color: #007bff;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .card-btn {
            display: block;
            text-decoration: none;
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 12px;
            transition: 0.3s;
            color: #333;
            font-size: 20px; /* เพิ่มขนาดตัวอักษรในปุ่ม */
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }

        .card-btn:hover {
            background-color: #007bff;
            color: white;
            transform: translateY(-3px);
        }

        .card-btn.red {
            background-color: #ffdddd;
            color: #a00;
        }

        .card-btn.red:hover {
            background-color: #ff4444;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>สวัสดี, <?= htmlspecialchars($_SESSION['username']) ?> (<?= htmlspecialchars($_SESSION['role']) ?>)</h2>

    <div class="card-grid">
        <a href="downloads.php" class="card-btn">📂 ไฟล์ของฉัน</a>
        <a href="upload.php" class="card-btn">📤 อัปโหลดไฟล์</a>
        <a href="edit_profile.php?id=<?= $user_id ?>" class="card-btn">⚙️ แก้ไขโปรไฟล์ของฉัน</a>

        <?php if (isAdmin()): ?>
            <a href="admin_dashboard.php" class="card-btn">🔧 แดชบอร์ดแอดมิน</a>
        <?php endif; ?>

        <a href="logout.php" class="card-btn red">🚪 ออกจากระบบ</a>
    </div>
</div>
</body>
</html>
