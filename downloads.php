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
        /* ...[สไตล์เหมือนเดิม ไม่เปลี่ยน กูไม่แปะซ้ำให้รกตา]... */
    </style>
    <script>
        const isAdmin = <?= json_encode($isAdmin) ?>;

        function liveSearch() {
            const query = document.getElementById("searchInput").value.toLowerCase();
            const tableRows = document.querySelectorAll("#fileTable tr");

            tableRows.forEach(row => {
                const tds = row.querySelectorAll("td");
                if (tds.length > 0) {
                    const filenameIndex = isAdmin ? 2 : 1;
                    const categoryIndex = isAdmin ? 3 : 2;

                    const filename = tds[filenameIndex]?.textContent?.toLowerCase() || "";
                    const category = tds[categoryIndex]?.textContent?.toLowerCase() || "";

                    if (filename.includes(query) || category.includes(query)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            });
        }
    </script>
</head>
<body>
<div class="container">
    <h2>📁 ไฟล์ของฉัน</h2>
    <a href="dashboard.php" class="back-link">← กลับไปหน้าแดชบอร์ด</a>

    <input class="sssss" type="text" id="searchInput" onkeyup="liveSearch()" placeholder=" 🔍 ค้นหาชื่อไฟล์หรือหมวดหมู่...">

    <table>
        <thead>
        <tr>
            <th>พรีวิว</th>
            <?php if ($isAdmin): ?><th>ผู้ใช้</th><?php endif; ?>
            <th>ชื่อไฟล์</th>
            <th>หมวดหมู่</th>
            <th>ประเภทไฟล์</th>
            <th>การดาวน์โหลด</th>
            <th>การอัปโหลด</th>
            <th>การจัดการ</th>
        </tr>
        </thead>
        <tbody id="fileTable">
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
                <td><?= strtoupper($ext) ?></td>
                <td><?= $file['download_count'] ?></td>
                <td><?= $file['upload_count'] ?></td>
                <td class="action-links">
                    <a href="download.php?id=<?= $file['id'] ?>" class="download">📥 ดาวน์โหลด</a>
                    <a href="rename_file.php?id=<?= $file['id'] ?>" class="rename">✏️ เปลี่ยนชื่อไฟล์</a>
                    <a href="delete_file.php?id=<?= $file['id'] ?>" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบ?')" class="delete">🗑 ลบ</a>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>

   <style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #a8edea, #fed6e3);
        color: #333;
    }

    .container {
        max-width: 1600px;
        margin: 50px auto;
        background: #ffffff;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 12px 40px rgba(0,0,0,0.1);
        animation: fadeIn 0.5s ease;
        
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(20px);}
        to {opacity: 1; transform: translateY(0);}
    }

    h2 {
        font-size: 36px;
        color: #007bff;
        margin-bottom: 25px;
        text-align: center;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 25px;
        color: #6c757d;
        font-size: 18px;
        text-decoration: none;
    }

    .back-link:hover {
        color: #007bff;
    }

    input[type="text"] {
        width: 100%;
        padding: 15px;
        font-size: 18px;
        border: 2px solid #ced4da;
        border-radius: 12px;
        margin-bottom: 30px;
        transition: border-color 0.3s;
    }

    input[type="text"]:focus {
        border-color: #007bff;
        outline: none;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
    }

    th, td {
        padding: 18px;
        border-bottom: 1px solid #dee2e6;
        text-align: center;
        vertical-align: middle;
    }

    th {
        background-color: #f1f3f5;
        color: #495057;
        font-weight: 600;
        font-size: 18px;
    }

    td {
        font-size: 17px;
    }

    tr:hover {
        background-color: #f8f9fa;
    }

    .file-preview {
        max-width: 80px;
        max-height: 80px;
        border-radius: 10px;
    }

    .action-links {
        display: flex;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
        padding: 40px;
    }

    .action-links a {
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease-in-out;
    }

    .download {
        background-color: #28a745;
    }

    .rename {
        background-color: #ffc107;
    }

    .delete {
        background-color: #dc3545;
    }

    .action-links a:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .no-files {
        text-align: center;
        padding: 30px;
        color: #888;
        font-size: 18px;
    }

    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }

        th, td {
            font-size: 14px;
            padding: 12px;
        }

        .action-links a {
            font-size: 14px;
        }
    }
    .sssss {
            max-width: 1550px;
        }
</style>