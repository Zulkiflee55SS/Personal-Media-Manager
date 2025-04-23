<?php
require 'auth.php';
requireLogin();
require 'db_connect.php';

$user_id = $_SESSION['user_id'];
$isAdmin = isAdmin();

if ($isAdmin) {
    $stmt = $pdo->query("SELECT f.*, u.username FROM files f JOIN users u ON f.user_id = u.id ORDER BY uploaded_at DESC");
} else {
    $stmt = $pdo->prepare("SELECT f.*, u.username FROM files f JOIN users u ON f.user_id = u.id WHERE user_id = ? ORDER BY uploaded_at DESC");
    $stmt->execute([$user_id]);
}
$files = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ไฟล์ของ<?= $isAdmin ? "ทุกคน" : "ฉัน" ?></title>
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
            font-size: 40px;
            margin-bottom: 25px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #6c757d;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 18px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
            font-size: 18px;
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

        .action-links {
            display: flex;
            justify-content: center;
            gap: 10px;

            padding: 50px;
        }

        .action-links a {
            font-size: 18px;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-block;
            transition: all 0.3s ease;
            font-weight: bold;
            
        }

        .action-links a.download {
            background-color: #28a745;
            color: white;
        }

        .action-links a.download:hover {
            background-color: rgb(0, 97, 29);
            transform: translateY(-3px);
        }

        .action-links a.rename {
            background-color: #ffc107;
            color: white;
        }
        .action-links a.rename:hover {
            background-color:rgb(184, 174, 33);
        }

        .action-links a.delete {
            background-color: #ff4444;
            color: white;
        }

        .action-links a.delete:hover {
            background-color: #dc3545;
        }

        .action-links a:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .no-files {
            text-align: center;
            padding: 20px;
            color: #888;
            font-size: 18px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>📁 ไฟล์ของ<?= $isAdmin ? "ทุกคน" : "ฉัน" ?></h2>
    <a href="dashboard.php" class="back-link">← กลับไปหน้าแดชบอร์ด</a>

    <?php if (count($files) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>พรีวิว</th>
                    <?php if ($isAdmin): ?><th>ผู้ใช้</th><?php endif; ?>
                    <th>ชื่อไฟล์</th>
                    <th>หมวดหมู่</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file): ?>
                    <?php
                    $path = "uploads/" . $file['filename'];
                    $ext = strtolower(pathinfo($file['filename'], PATHINFO_EXTENSION));
                    $imageExt = ['jpg', 'jpeg', 'png', 'gif'];
                    $videoExt = ['mp4', 'webm', 'ogg'];
                    $pdfExt = ['pdf'];
                    ?>
                    <tr>
                        <td>
                            <?php if (in_array($ext, $imageExt)): ?>
                                <img src="<?= $path ?>" alt="รูปภาพ" class="file-preview">
                            <?php elseif (in_array($ext, $videoExt)): ?>
                                <video controls class="file-preview">
                                    <source src="<?= $path ?>" type="video/<?= $ext ?>">
                                </video>
                            <?php elseif (in_array($ext, $pdfExt)): ?>
                                <a href="<?= $path ?>" target="_blank">เปิด PDF</a>
                            <?php else: ?>
                                <img src="file_icon/images.png" class="file-preview" alt="ไอคอนไฟล์">
                            <?php endif; ?>
                        </td>
                        <?php if ($isAdmin): ?><td><?= htmlspecialchars($file['username']) ?></td><?php endif; ?>
                        <td><?= htmlspecialchars($file['original_name']) ?></td>
                        <td><?= htmlspecialchars($file['category']) ?></td>
                        <td class="action-links">
                            <a href="download.php?id=<?= $file['id'] ?>" class="download">📥 ดาวน์โหลด</a>
                            <a href="rename_file.php?id=<?= $file['id'] ?>" class="rename">✏️ เปลี่ยนชื่อไฟล์</a>
                            <a href="delete_file.php?id=<?= $file['id'] ?>" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบ?')" class="delete">🗑 ลบ</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-files">ไม่มีไฟล์ให้แสดงในขณะนี้</div>
    <?php endif; ?>
</div>
</body>
</html>
