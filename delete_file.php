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

if (file_exists($path)) unlink($path);

$stmt = $pdo->prepare("DELETE FROM files WHERE id = ?");
$stmt->execute([$id]);

header("Location: downloads.php");
exit;
