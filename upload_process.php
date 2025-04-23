<?php
require 'auth.php';
require 'csrf_token.php';
requireLogin();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateToken($_POST['csrf_token'])) {
        die("CSRF validation failed");
    }

    $user_id = $_SESSION['user_id'];
    $category = $_POST['category'];
    $file = $_FILES['file'];

    // ตรวจสอบว่านามสกุลไฟล์อยู่ในรายการที่อนุญาต
    $allowed = ['jpg', 'png', 'pdf', 'docx', 'mp4'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        die("❌ ไม่รองรับนามสกุลไฟล์นี้");
    }

    if ($file['size'] > $maxSize) {
        die("❌ ขนาดไฟล์เกิน 5MB");
    }

    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // เปลี่ยนชื่อไฟล์ให้ไม่ซ้ำกัน
    $newName = uniqid() . "." . $ext;
    $destination = 'uploads/' . $newName;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $stmt = $pdo->prepare("INSERT INTO files (user_id, filename, original_name, category) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $newName, $file['name'], $category]);

        // ✅ กลับไปหน้า upload พร้อมแสดงข้อความสำเร็จ
        header("Location: upload.php?success=1");
        exit;
    } else {
        die("❌ อัปโหลดล้มเหลว กรุณาลองใหม่อีกครั้ง");
    }
}
?>
