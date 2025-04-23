<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_connect.php';

echo "เริ่มต้น<br>";

if (isset($_GET['token'])) {
    echo "Token ได้รับแล้ว: " . htmlspecialchars($_GET['token']) . "<br>";
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email_token = ?");
    $stmt->execute([$token]);

    echo "จำนวนผู้ใช้ที่พบ: " . $stmt->rowCount() . "<br>";

    if ($stmt->rowCount() > 0) {
        $pdo->prepare("UPDATE users SET email_verified = 1, email_token = NULL WHERE email_token = ?")
            ->execute([$token]);
        echo "✅ ยืนยันอีเมลเรียบร้อยแล้ว! <a href='index.php'>เข้าสู่ระบบ</a>";
    } else {
        echo "❌ Token ไม่ถูกต้อง หรือถูกใช้งานแล้ว";
    }
} else {
    echo "❌ ไม่มี token ส่งมา";
}
?>
