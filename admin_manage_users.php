<?php
require 'auth.php';
requireLogin();
if (!isAdmin()) die("เข้าถึงไม่ได้");
require 'db_connect.php';

$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการผู้ใช้</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0f7fa, #fff);
            color: #333;
            padding: 50px;3;
        }

        .container {
            max-width: 960px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            font-size: 36px;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            text-transform: uppercase;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            font-size: 16px;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            transition: background-color 0.3s ease;
        }

        th {
            background-color: #4CAF50; /* สีหัวตารางเป็นเขียวอ่อน */
            color: white;
            font-weight: bold;
        }

        td {
            background-color: #fff;
            color: #333;
        }

        tr:nth-child(even) td {
            background-color: #f9f9f9; /* สีแถวคู่ */
        }

        tr:hover td {
            background-color: #f1f1f1; /* สีแถวเมื่อ hover */
        }

        a {
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        /* ปุ่มย้อนกลับ */
        .back-button {
            background-color: #FF5733; /* สีฟ้าสดใส */
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 30px;
            display: inline-block;
            font-size: 18px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #C84B2A; /* สีเข้มขึ้นเมื่อ hover */
        }

        /* ปุ่มแก้ไข */
        .edit-btn {
            background-color: #00BFFF; /* ปรับสีฟ้าอ่อน */
            color: white;
            padding: 6px 15px;
            border-radius: 5px;
            font-size: 14px;
            margin: 0 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .edit-btn:hover {
            background-color: #1E90FF; /* สีเข้มขึ้นเมื่อ hover */
            transform: translateY(-3px);
        }

        .edit-btn:active {
            transform: translateY(3px);
        }

        /* ปุ่มลบ */
        .delete-btn {
            background-color: #FF6347; /* สีแดงสำหรับลบ */
            color: white;
            padding: 6px 15px;
            border-radius: 5px;
            font-size: 14px;
            margin: 0 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .delete-btn:hover {
            background-color: #FF4500; /* สีแดงเข้มขึ้นเมื่อ hover */
            transform: translateY(-3px);
        }

        .delete-btn:active {
            transform: translateY(3px);
        }

        /* ปรับให้เหมาะสมกับขนาดหน้าจอ */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 28px;
            }

            table {
                font-size: 14px;
            }

            th, td {
                padding: 10px;
            }

            .back-button {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>👥 รายชื่อผู้ใช้</h2>
    <table>
        <tr>
            <th>ชื่อผู้ใช้</th>
            <th>Email</th>
            <th>Role</th>
            <th>เครื่องมือ</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['role'] ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $user['id'] ?>" class="edit-btn">✏️ แก้ไข</a>
                    <?php if ($_SESSION['user_id'] != $user['id']): ?>
                        <a href="?delete=<?= $user['id'] ?>" onclick="return confirm('ลบผู้ใช้นี้?')" class="delete-btn">🗑 ลบ</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="admin_dashboard.php" class="back-button">← กลับ</a>
</div>

</body>
</html>

<?php
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$deleteId]);
    header("Location: admin_manage_users.php");
    exit;
}
?>
