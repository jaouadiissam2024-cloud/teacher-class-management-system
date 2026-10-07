<?php
session_start();

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /login.php");
        exit;
    }
}

function requireRole($role) {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
        http_response_code(403);
        die("Accès interdit à cette page.");
    }
}

function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        die("Token CSRF invalide.");
    }
}
