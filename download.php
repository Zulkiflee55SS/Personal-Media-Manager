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

$path = 'uploads/' . $file['filename'];
if (!file_exists($path)) die("ไฟล์ไม่อยู่แล้ว");

$pdo->prepare("UPDATE files SET download_count = download_count + 1 WHERE id = ?")->execute([$id]);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($file['original_name']) . '"');
readfile($path);
exit;
