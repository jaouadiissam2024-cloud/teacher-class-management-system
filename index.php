<?php
require __DIR__ . '/bootstrap.php';

if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        header('Location: /admin/dashboard.php');
    } else {
        header('Location: /teacher/dashboard.php');
    }
    exit;
}

header('Location: /login.php');
exit;
