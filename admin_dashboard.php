<?php
require 'auth.php';
requireLogin();
if (!isAdmin()) die("เข้าถึงไม่ได้");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ดแอดมิน</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #a8edea, #fed6e3);
            color: #333;
        }

        .container {
            max-width: 960px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            font-size: 36px;
            font-weight: 700;
            color: #007bff;
            margin-bottom: 30px;
            text-transform: uppercase;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin: 15px 0;
        }

        a {
            display: inline-block;
            text-decoration: none;
            background-color: #f1f1f1;
            color: #333;
            padding: 15px 30px;
            border-radius: 12px;
            font-size: 20px;
            transition: transform 0.3s ease, background-color 0.3s ease;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        a:hover {
            background-color: #007bff;
            color: white;
            transform: translateY(-5px);
        }

        a:active {
            transform: translateY(0);
        }

        .back-button {
            background-color: #ff4040;
        }

        .back-button:hover {
            background-color: #d92f2f;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 28px;
            }

            a {
                font-size: 16px;
                padding: 12px 25px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🔧 แดชบอร์ดแอดมิน</h2>
    <ul>
        <li><a href="admin_manage_users.php">👥 จัดการผู้ใช้</a></li>
        <li><a href="admin_manage_files.php">📂 จัดการไฟล์ทั้งหมด</a></li>
        <li><a href="dashboard.php" class="back-button">← กลับหน้าหลัก</a></li>
    </ul>
</div>

</body>
</html>
