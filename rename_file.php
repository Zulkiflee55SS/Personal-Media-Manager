<?php
require 'auth.php';
requireLogin();
require 'db_connect.php';

$id = $_GET['id'] ?? null;
if (!$id) die("ไม่พบไฟล์");

$stmt = $pdo->prepare("SELECT * FROM files WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetch();

if (!$file) die("ไม่พบไฟล์");

$isOwner = $_SESSION['user_id'] == $file['user_id'];
if (!isAdmin() && !$isOwner) die("ไม่มีสิทธิ์");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = $_POST['new_name'];

    if (empty($new_name)) {
        $error = "กรุณาใส่ชื่อใหม่";
    } else {
        $stmt = $pdo->prepare("UPDATE files SET original_name = ? WHERE id = ?");
        $stmt->execute([$new_name, $id]);
        $message = "เปลี่ยนชื่อสำเร็จ";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>เปลี่ยนชื่อไฟล์</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>✏️ เปลี่ยนชื่อไฟล์</h2>
    <p><strong>ชื่อเดิม:</strong> <?= htmlspecialchars($file['original_name']) ?></p>
    <?php if (isset($message)) echo "<p style='color:green'>$message</p>"; ?>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="new_name" placeholder="ชื่อใหม่" required>
        <button type="submit">บันทึก</button>
    </form>
    <br>
    <a href="downloads.php">← กลับ</a>
</div>
</body>
</html>
