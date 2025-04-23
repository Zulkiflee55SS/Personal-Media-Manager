<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'db_connect.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header("Location: dashboard.php");
        exit;
    }
}
?>
