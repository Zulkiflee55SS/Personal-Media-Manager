<?php
require 'auth.php';
requireLogin();
if (!isAdmin()) die("เข้าถึงไม่ได้");
require 'db_connect.php';

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
        /* [เหมือนเดิมหมด กูไม่แตะสไตล์มาก จะไม่แปะซ้ำให้รกตา] */
    </style>
    <script>
        function searchFiles() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.file-row');

            rows.forEach(row => {
                const tds = row.querySelectorAll('td');
                if (tds.length > 0) {
                    const filename = tds[2]?.textContent.toLowerCase() || '';
                    const category = tds[3]?.textContent.toLowerCase() || '';

                    if (filename.includes(input) || category.includes(input)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }
    </script>
</head>
<body>

<div class="container">
    <h2>📂 ไฟล์ทั้งหมด</h2>
    <a href="admin_dashboard.php" class="back-button">← กลับ</a>
    <input type="text" id="searchInput" placeholder="ค้นหาชื่อไฟล์หรือหมวดหมู่..." onkeyup="searchFiles()">

    <table id="fileTable">
        <tr>
            <th>รูปภาพ</th>
            <th>ชื่อผู้ใช้</th>
            <th>ชื่อไฟล์</th>
            <th>หมวดหมู่</th>
            <th>จัดการ</th>
        </tr>
        <?php foreach ($files as $file): ?>
            <tr class="file-row">
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
                    <a href="rename_file.php?id=<?= $file['id'] ?>" class="rename-btn">✏️ แก้ไขชื่อไฟล์</a>
                    <a href="delete_file.php?id=<?= $file['id'] ?>" onclick="return confirm('ลบไฟล์นี้?')" class="delete-btn">🗑</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>

<style>
        /* สไตล์ต่างๆ */
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #a8edea, #fed6e3);
            color: #333;
            padding: 50px;
        }

        .container {
            max-width: 1400px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-radius: 8px;
            
        }

        h2 {
            font-size: 40px;
            margin-bottom: 30px;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            width: 100%;
            max-width: 400px;
            margin: 20px 0;
            border: 2px solid #ccc;
            border-radius: 5px;
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
            transition: all 0.3s ease;
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

        .download-btn:hover, .rename-btn:hover, .delete-btn:hover {
            background-color: #007BFF;
            transform: scale(1.1);
        }

        .delete-btn:hover {
            background-color: #D9534F;
        }

        .back-button {
            background: #388E3C;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 30px;
            display: inline-block;
        }

    </style>