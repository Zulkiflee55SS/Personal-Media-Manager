<?php
require 'auth.php';
requireLogin();  // ตรวจสอบว่าผู้ใช้ล็อกอินแล้วหรือยัง

require 'db_connect.php';

$id = $_GET['id'] ?? null;

// ตรวจสอบว่า id ถูกต้องหรือไม่
if (!$id || !is_numeric($id)) {
    die("ID ไม่ถูกต้อง");
}

$id = (int) $id;

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    die("ไม่พบผู้ใช้ในระบบ");
}

// ถ้าส่งข้อมูลมาแบบ POST ให้ทำการอัปเดต
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    $stmt->execute([$username, $email, $role, $id]);

    header("Location: " . $_SERVER['HTTP_REFERER']); // กลับไปยังหน้าก่อนหน้า
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขผู้ใช้</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            font-size: 24px;
            color: #007bff;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
            color: #333;
        }

        input, select {
            padding: 12px;
            font-size: 16px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f8f9fa;
        }

        button {
            padding: 12px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .btn-back {
            display: inline-block;
            margin-top: 15px;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
        }

        .btn-back:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>✏️ แก้ไขผู้ใช้</h2>
    <form method="POST">
        <label>ชื่อผู้ใช้:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Role:</label>
        <select name="role" required>
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>user</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>admin</option>
        </select>

        <button type="submit">💾 บันทึก</button>
    </form>
    <a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn-back">← กลับ</a>
</div>
</body>
</html>
