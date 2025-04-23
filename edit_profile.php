<?php
require 'auth.php';
requireLogin();  // ตรวจสอบการล็อกอิน

require 'db_connect.php';

$id = $_SESSION['user_id'];  // ใช้ ID ของผู้ใช้ที่ล็อกอิน

// ดึงข้อมูลของผู้ใช้จากฐานข้อมูล
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    die("ไม่พบผู้ใช้ในระบบ");
}

// ถ้าส่งข้อมูลมาแบบ POST ให้ทำการอัปเดตข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $profile_picture = $user['profile_picture'] ?? ''; // ใช้การตรวจสอบค่าของ profile_picture

    // ตรวจสอบการอัปโหลดไฟล์รูปโปรไฟล์
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $upload_dir = 'uploads/profile_pictures/';
        $file_name = basename($_FILES['profile_picture']['name']);
        $target_file = $upload_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // ตรวจสอบว่าไฟล์เป็นรูปภาพหรือไม่
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_type, $allowed_types)) {
            // อัปโหลดไฟล์
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $profile_picture = $file_name; // อัปเดตเฉพาะชื่อไฟล์ในฐานข้อมูล
            } else {
                echo "การอัปโหลดไฟล์ล้มเหลว!";
            }
        } else {
            echo "ไฟล์ต้องเป็นรูปภาพเท่านั้น!";
        }
    }

    // อัปเดตข้อมูลในฐานข้อมูล
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, profile_picture = ? WHERE id = ?");
    $stmt->execute([$username, $email, $profile_picture, $id]);

    header("Location: dashboard.php");  // หลังจากบันทึก ให้กลับไปที่หน้าแดชบอร์ด
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขโปรไฟล์</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>✏️ แก้ไขโปรไฟล์ของคุณ</h2>
         <div class="aaaa" >
            <?php if (!empty($user['profile_picture'])): ?>
                <div class="profile-picture">
                    <img src="uploads/profile_pictures/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" class="profile-img-preview">
                </div>
            <?php endif; ?>            
        </div>   
    <form method="POST" enctype="multipart/form-data" class="profile-form">
        <div class="form-group">
            <label for="username">ชื่อผู้ใช้:</label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>



        <div class="form-group">
            <label for="profile_picture">รูปโปรไฟล์:</label>
            <input type="file" name="profile_picture" accept="image/*" id="profile_picture">
           
        </div>


            <button type="submit" class="btn-save">💾 บันทึก</button>
    </form>
    <a class="btn-back" href="dashboard.php">← กลับ</a>
</div>
</body>
</html>

<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #a8edea, #fed6e3);
        color: #333;
        padding: 0;
    }

    .container {
        max-width: 700px;
        margin: 50px auto;
        background: #ffffff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    }

    h2 {
        color: #007bff;
        font-size: 28px;
        margin-bottom: 20px;
        text-align: center;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 8px;
    }

    input[type="text"], input[type="email"], input[type="file"] {
        width: 100%;
        padding: 12px;
        margin-top: 5px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 16px;
    }

    .profile-picture img {
        
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin-top: 15px;
    }

    .btn-save {
        background-color: #007bff;
        color: white;
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 18px;
        border: none;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-save:hover {
        background-color: #0056b3;
    }
    .aaaa {
        text-align: center;
    }

    .btn-back {
        display: inline-block;
        margin-top: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        text-align: center;
    }

    .btn-back:hover {
        background-color: #5a6268;
    }
</style>
