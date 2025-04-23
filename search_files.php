<?php
require 'auth.php';
requireLogin();
require 'db_connect.php';

$user_id = $_SESSION['user_id'];
$isAdmin = isAdmin();
$search = $_GET['q'] ?? '';

if ($isAdmin) {
    $stmt = $pdo->prepare("SELECT f.*, u.username 
                           FROM files f 
                           JOIN users u ON f.user_id = u.id 
                           WHERE f.original_name LIKE ? OR f.category LIKE ? 
                           ORDER BY uploaded_at DESC");
    $stmt->execute(["$search%", "$search%"]);
} else {
    $stmt = $pdo->prepare("SELECT f.*, u.username 
                           FROM files f 
                           JOIN users u ON f.user_id = u.id 
                           WHERE f.user_id = ? AND (f.original_name LIKE ? OR f.category LIKE ?) 
                           ORDER BY uploaded_at DESC");
    $stmt->execute([$user_id, "$search%", "$search%"]);
}

$files = $stmt->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($files);
