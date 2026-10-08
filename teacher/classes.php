<?php
require __DIR__ . '/../config/bootstrap.php';
requireLogin('teacher');

$classes = [
    ['name' => 'الصف 2A', 'level' => 'ابتدائي', 'students' => 30, 'year' => '2026/2027'],
    ['name' => 'الصف 4B', 'level' => 'متوسط', 'students' => 26, 'year' => '2026/2027'],
    ['name' => 'الصف 6A', 'level' => 'ثانوي', 'students' => 22, 'year' => '2026/2027'],
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الفصول</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="app-body">
    <aside class="sidebar">
        <div class="brand-box">
            <div class="brand-circle">S</div>
            <div>
                <strong>SchoolFlow</strong>
                <small>منصة المعلم</small>
            </div>
        </div>

        <nav class="nav-menu">
            <a href="/teacher/dashboard.php">لوحة التحكم</a>
            <a class="active" href="/teacher/classes.php">الفصول</a>
            <a href="/teacher/messages.php">البريد</a>
            <a href="/teacher/settings.php">الإعدادات</a>
            <a href="/logout.php">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="content-panel">
        <header class="topbar">
            <div>
                <span class="mini-label">الفصول</span>
                <h1>الفصول الموكلة</h1>
            </div>
            <button class="primary-btn">+ إضافة طالب</button>
        </header>

        <section class="class-grid">
            <?php foreach ($classes as $class): ?>
                <article class="class-card">
                    <div class="class-tag"><?= htmlspecialchars($class['level'], ENT_QUOTES, 'UTF-8'); ?></div>
                    <h3><?= htmlspecialchars($class['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p>تدريس اللغة العربية</p>
                    <div class="class-meta">
                        <span><?= (int)$class['students']; ?> طالب</span>
                        <span><?= htmlspecialchars($class['year'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <div class="class-actions">
                        <button class="ghost-btn small">الأرشيف</button>
                        <button class="primary-btn small">مراجعة</button>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>
