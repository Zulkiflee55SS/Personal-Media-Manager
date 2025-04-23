<?php
require 'auth.php';
require 'csrf_token.php';
requireLogin();
$token = generateToken();
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>อัปโหลดไฟล์</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e0f7fa, #fff);
      color: #333;
      padding: 50px;
    }

    .container {
      max-width: 600px;
      background: #fff;
      margin: auto;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 25px;
    }

    label {
      display: block;
      font-weight: bold;
      margin-bottom: 10px;
      margin-top: 20px;
    }

    select, input[type="file"], button {
      width: 100%;
      padding: 14px;
      margin-top: 8px;
      border-radius: 10px;
      border: 1px solid #ccc;
      font-size: 16px;
    }

    .error {
      color: red;
      font-size: 15px;
      margin-top: 5px;
      display: none;
    }

    button {
      background: #3498db;
      color: white;
      font-weight: bold;
      margin-top: 30px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: #2c80b4;
    }
    .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            text-align: center;
        }

        .btn-back {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            border-radius: 14px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }
        select:invalid {
  color: gray;
}
select option[value=""] {
  display: none;
}

  </style>
</head>
<body>

<div class="container">
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="success">✅ อัปโหลดไฟล์เรียบร้อยแล้ว</div>
        
    <?php endif; ?>
  <h2>📤 อัปโหลดไฟล์</h2>

  <form id="uploadForm" method="POST" action="upload_process.php" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $token ?>">

    <label for="file">เลือกไฟล์</label>
    <input type="file" name="file" id="file" required>

    <label for="category">เลือกหมวดหมู่</label>
    <select id="category" name="category">
      <option value="">-- กรุณาเลือกหมวดหมู่ --</option>
      <option value="รูปภาพ">📷 รูปภาพ</option>
      <option value="วิดีโอ">🎬 วิดีโอ</option>
      <option value="เอกสาร">📄 เอกสาร</option>
      <option value="อื่นๆ">📁 อื่นๆ</option>
    </select>
    <div id="categoryError" class="error">⚠️ กรุณาเลือกหมวดหมู่ก่อนอัปโหลด</div>

    <button type="submit">📤 อัปโหลด</button>
    <a class="btn-back" href="dashboard.php">ย้อนกลับหน้าหลัก</a>
  </form>
</div>

<script>
  const form = document.getElementById('uploadForm');
  const category = document.getElementById('category');
  const error = document.getElementById('categoryError');

  form.addEventListener('submit', function(e) {
    if (category.value === '') {
      e.preventDefault();
      error.style.display = 'block';
      category.focus();
    } else {
      error.style.display = 'none';
    }
  });

  category.addEventListener('change', function() {
    if (category.value !== '') {
      error.style.display = 'none';
    }
  });

  window.addEventListener('DOMContentLoaded', () => {
    const success = document.querySelector('.success');
    if (success) {
      setTimeout(() => {
        success.style.opacity = '0';  // ลดความทึบของข้อความ
        success.style.transition = 'opacity 1s';  // ทำให้มัน fade out อย่างช้าๆ
        setTimeout(() => success.remove(), 1000);  // เอาข้อความออกหลังจากจางไป 1 วิ
      }, 3000);  // ตั้งเวลาไว้ 3 วินาทีให้แสดงก่อน fade out
    }
  });
</script>


</body>
</html>
