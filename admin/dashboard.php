<?php
require '../config/auth.php';
require '../config/db.php';
requireLogin();
requireRole('admin');

$totalTeachers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users WHERE role = 'teacher'"))['c'];
$totalClasses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM classes"))['c'];
$totalDocs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM documents"))['c'];
$totalSpecialties = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM specialties"))['c'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الإدارة</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">S</div>
            <div>لوحة الإدارة</div>
        </div>

        <nav>
            <a href="dashboard.php" class="active">الرئيسية</a>
            <a href="teachers.php">المعلمين</a>
            <a href="specialties.php">التخصصات</a>
            <a href="../logout.php">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="content">
        <div class="topbar">
            <h1>مرحبًا، <?= htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') ?></h1>
        </div>

        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">المعلمين</div>
                <div class="stat-number"><?= $totalTeachers ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-label">الفصول</div>
                <div class="stat-number"><?= $totalClasses ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-label">المستندات</div>
                <div class="stat-number"><?= $totalDocs ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-label">التخصصات</div>
                <div class="stat-number"><?= $totalSpecialties ?></div>
            </div>
        </section>
    </main>
</body>
</html>
