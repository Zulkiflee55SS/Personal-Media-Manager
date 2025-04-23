<?php
session_start();
$user_id = $_SESSION['user_id'];

require 'auth.php';
requireLogin();

require 'db_connect.php';

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("ไม่พบผู้ใช้ในระบบ");
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แดชบอร์ด | ไฟล์ของฉัน</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        @import url('https://fonts.googleapis.com/css2?family=Prompt&display=swap');

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #a8edea, #fed6e3);
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 80px auto;
            text-align: center;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #007bff;
        }

        h3 {
            font-size: 30px;
            color: #333;
            margin-bottom: 30px;
        }

        .name {
            background: linear-gradient(to right, rgb(47, 0, 255), rgb(41, 35, 100));
            -webkit-background-clip: text;
            color: transparent;
            font-weight: bold;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }

        .card-btn {
            display: block;
            text-decoration: none;
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 12px;
            transition: 0.3s;
            color: #333;
            font-size: 18px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        .card-btn, .pro_file {
    display: block;
    text-decoration: none !important; /* เพิ่มเพื่อป้องกันขีดใต้ */
    background-color: #f1f1f1;
    padding: 20px;
    border-radius: 12px;
    transition: 0.3s;
    color: #333;
    font-size: 20px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
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

        .profile-picture img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .pro_file {
            display: inline-block;
            background-color: #f1f1f1;
            padding: 12px 25px;
            width: auto;
            border-radius: 12px;
            margin-top: 10px;
            color: #007bff;
            font-size: 18px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: 0.3s;
        }

        .pro_file:hover {
            background-color: #007bff;
            color: white;
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px;
            }

            .card-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .card-btn {
                font-size: 16px;
                padding: 15px;
            }

            .profile-picture img {
                width: 120px;
                height: 120px;
            }

            h2 {
                font-size: 30px;
            }

            h3 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>ยินดีต้อนรับเข้าสู่ระบบ Personal Media Manager</h2>
    <h3>ยินดีต้อนรับคุณ : <span class="name"><?= htmlspecialchars($_SESSION['username']) ?></span>
    <?php if (isAdmin()): ?>
        (<?= htmlspecialchars($_SESSION['role']) ?>)
    <?php endif; ?>
    </h3>

    <!-- แสดงรูปโปรไฟล์ -->
    <div class="profile-picture">
    <?php if (!empty($user['profile_picture']) && file_exists('uploads/profile_pictures/' . $user['profile_picture'])): ?>
        <img src="uploads/profile_pictures/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture">
    <?php else: ?>
        <img src="uploads/profile_pictures/default-profile.png" alt="Default Profile Picture">
    <?php endif; ?>
</div>

    
    <a href="edit_profile.php?id=<?= $user_id ?>" class="pro_file">🔧 แก้ไขโปรไฟล์</a>

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
