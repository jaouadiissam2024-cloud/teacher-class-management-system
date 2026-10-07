<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: teacher/dashboard.php");
    }
    exit;
}

header("Location: login.php");
exit;
