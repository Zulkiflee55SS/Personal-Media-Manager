<?php
require 'auth.php';
requireLogin();
if (!isAdmin()) die("เข้าถึงไม่ได้");
require 'db_connect.php';

// ดึงข้อมูลไฟล์จากฐานข้อมูล
$stmt = $pdo->query("SELECT f.*, u.username FROM files f JOIN users u ON f.user_id = u.id ORDER BY f.uploaded_at DESC");
$files = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการไฟล์ทั้งหมด</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0f7fa, #fff);
            color: #333;
            padding: 50px;
        }

        .container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            font-size: 40px;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px 20px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 20px;
        }

        th {
            background: #4CAF50;
            color: white;
        }

        tr:nth-child(even) td {
            background: #f9f9f9;
        }

        tr:hover td {
            background: #f1f1f1;
        }

        .file-image {
            max-width: 120px;
            max-height: 120px;
            border-radius: 8px;
        }

        video {
            max-width: 200px;
            max-height: 120px;
        }

        .download-btn, .rename-btn, .delete-btn {
            padding: 6px 15px;
            border-radius: 5px;
            font-size: 14px;
            margin: 5px;
            display: inline-block;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease; /* เพิ่มการเคลื่อนไหว */
        }

        .download-btn { 
            background: #00BFFF; 
        }
        .rename-btn { 
            background: #FF8C00; 
        }
        .delete-btn { 
            background: #FF6347; 
        }

        /* เพิ่มเอฟเฟกต์เมื่อเมาส์เข้าใกล้ */
        .download-btn:hover, .rename-btn:hover, .delete-btn:hover {
            background-color: #007BFF; /* เปลี่ยนสีพื้นหลัง */
            transform: scale(1.1); /* ขยายขนาดปุ่มเล็กน้อย */
        }

        /* เพิ่มเอฟเฟกต์สำหรับปุ่มขยะ (ลบ) */
        .delete-btn:hover {
            background-color: #D9534F; /* สีสำหรับปุ่มลบ */
            transform: scale(1.1); /* ขยายขนาดปุ่มเล็กน้อย */
        }

        /* เพิ่มการเปลี่ยนแปลงของปุ่มดาวน์โหลด */
        .download-btn:hover {
            background-color: #007BFF; /* เปลี่ยนสีพื้นหลัง */
            transform: scale(1.1); /* ขยายขนาดปุ่ม */
        }

        /* เพิ่มการเปลี่ยนแปลงของปุ่มแก้ไข */
        .rename-btn:hover {
            background-color: #FFA500; /* สีสำหรับปุ่มแก้ไข */
            transform: scale(1.1); /* ขยายขนาดปุ่ม */
        }

        .back-button {
            background: #388E3C;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 30px;
            display: inline-block;
        }

    </style>
</head>
<body>

<div class="container">
    <h2>📂 ไฟล์ทั้งหมด</h2>
    <table>
        <tr>
            <th>รูปภาพ</th>
            <th>ชื่อผู้ใช้</th>
            <th>ชื่อไฟล์</th>
            <th>หมวดหมู่</th>
            <th>จัดการ</th>
        </tr>
        <?php foreach ($files as $file): ?>
            <tr>
                <td>
                    <?php
                    $ext = strtolower(pathinfo($file['original_name'], PATHINFO_EXTENSION));
                    $imageExt = ['jpg', 'jpeg', 'png', 'gif'];
                    $videoExt = ['mp4', 'webm', 'ogg'];
                    $pdfExt = ['pdf'];
                    ?>
                    
                    <?php if (in_array($ext, $imageExt)): ?>
                        <img src="uploads/<?= htmlspecialchars($file['filename']) ?>" alt="รูปภาพ" class="file-image">
                    <?php elseif (in_array($ext, $videoExt)): ?>
                        <video controls>
                            <source src="uploads/<?= htmlspecialchars($file['filename']) ?>" type="video/<?= $ext ?>">
                            วิดีโอไม่รองรับ
                        </video>
                    <?php elseif (in_array($ext, $pdfExt)): ?>
                        <a href="uploads/<?= htmlspecialchars($file['filename']) ?>" target="_blank">เปิด PDF</a>
                    <?php else: ?>
                        <img src="file_icon/images.png" alt="ไอคอนไฟล์" class="file-image">
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($file['username']) ?></td>
                <td><?= htmlspecialchars($file['original_name']) ?></td>
                <td><?= htmlspecialchars($file['category']) ?></td>
                <td>
                    <a href="download.php?id=<?= $file['id'] ?>" class="download-btn">📥 ดาวน์โหลด</a>
                    <?php if (in_array($ext, $videoExt)): ?>
                        <a href="uploads/<?= $file['filename'] ?>" class="download-btn" target="_blank">เล่นวิดีโอ</a>
                    <?php elseif (in_array($ext, $pdfExt)): ?>
                        <a href="uploads/<?= $file['filename'] ?>" class="download-btn" target="_blank">เปิดเอกสาร</a>
                    <?php endif; ?>
                    <a href="rename_file.php?id=<?= $file['id'] ?>" class="rename-btn">✏️ แก้ไขชื้อไฟล์</a>
                    <a href="delete_file.php?id=<?= $file['id'] ?>" onclick="return confirm('ลบไฟล์นี้?')" class="delete-btn">🗑</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="admin_dashboard.php" class="back-button">← กลับ</a>
</div>

</body>
</html>
