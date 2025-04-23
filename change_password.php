<?php
require 'auth.php';
requireLogin();
require 'db_connect.php';

$id = $_GET['id'] ?? $_SESSION['user_id'];
$isAdmin = isAdmin();

if (!$isAdmin && $_SESSION['user_id'] != $id) {
    die("ไม่มีสิทธิ์");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    if (strlen($password) < 6) {
        $error = "รหัสผ่านต้องยาวอย่างน้อย 6 ตัวอักษร";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")->execute([$hashed, $id]);
        $message = "เปลี่ยนรหัสผ่านสำเร็จ";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>เปลี่ยนรหัสผ่าน</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>🔐 เปลี่ยนรหัสผ่าน</h2>
    <?php if (isset($message)) echo "<p style='color:green'>$message</p>"; ?>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <label>รหัสผ่านใหม่:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">บันทึก</button>
    </form>
    <br>
    <a href="<?= $isAdmin ? 'admin_manage_users.php' : 'dashboard.php' ?>">← กลับ</a>
</div>
</body>
</html>
