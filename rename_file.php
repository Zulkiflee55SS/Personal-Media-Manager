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
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เปลี่ยนชื่อไฟล์</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Sarabun', sans-serif;
            background: linear-gradient(135deg, #a8edea, #fed6e3);
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 80px auto;
            background: white;
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
            font-size: 32px;
            color: #007bff;
            margin-bottom: 25px;
            text-align: center;
        }

        p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 15px;
            font-size: 17px;
            border: 2px solid #ced4da;
            border-radius: 12px;
            margin-bottom: 20px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            border-color: #007bff;
            outline: none;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            font-size: 17px;
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            border-radius: 12px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        .success {
            color: green;
            margin-bottom: 15px;
        }
        .sssss {
            max-width: 565px;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>✏️ เปลี่ยนชื่อไฟล์</h2>
        <p><strong>ชื่อเดิม:</strong> <?= htmlspecialchars($file['original_name']) ?></p>
        <?php if (isset($message)) echo "<p class='success'>$message</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
        <input class="sssss" type="text" name="new_name" placeholder="ชื่อใหม่" required>

            <button type="submit">💾 บันทึก</button>
        </form>
        <a class="btn-back" href="downloads.php">← กลับ</a>
    </div>
</body>
</html>
