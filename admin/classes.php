<?php
require __DIR__ . '/../config/bootstrap.php';
requireLogin('admin');

$classes = [
    ['name' => 'الصف 1A', 'level' => 'ابتدائي', 'teacher' => 'سارة محمد', 'students' => 28, 'year' => '2026/2027'],
    ['name' => 'الصف 2B', 'level' => 'ابتدائي', 'teacher' => 'نادية علي', 'students' => 25, 'year' => '2026/2027'],
    ['name' => 'الصف 3A', 'level' => 'متوسط', 'teacher' => 'إياد المومن', 'students' => 31, 'year' => '2026/2027'],
    ['name' => 'الصف 4C', 'level' => 'متوسط', 'teacher' => 'هدى سالم', 'students' => 27, 'year' => '2026/2027'],
    ['name' => 'الصف 5B', 'level' => 'ثانوي', 'teacher' => 'أسماء عيسى', 'students' => 24, 'year' => '2026/2027'],
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الفصول</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="app-body">
    <aside class="sidebar">
        <div class="brand-box">
            <div class="brand-circle">S</div>
            <div>
                <strong>SchoolFlow</strong>
                <small>إدارة المؤسسة</small>
            </div>
        </div>

        <nav class="nav-menu">
            <a href="/admin/dashboard.php">لوحة التحكم</a>
            <a class="active" href="/admin/classes.php">الفصول</a>
            <a href="/admin/messages.php">البريد</a>
            <a href="/admin/settings.php">الإعدادات</a>
            <a href="/logout.php">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="content-panel">
        <header class="topbar">
            <div>
                <span class="mini-label">الفصول</span>
                <h1>إدارة الصفوف</h1>
            </div>
            <button class="primary-btn">+ إضافة فصل</button>
        </header>

        <section class="class-grid">
            <?php foreach ($classes as $class): ?>
                <article class="class-card">
                    <div class="class-tag"><?= htmlspecialchars($class['level'], ENT_QUOTES, 'UTF-8'); ?></div>
                    <h3><?= htmlspecialchars($class['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?= htmlspecialchars($class['teacher'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <div class="class-meta">
                        <span><?= (int)$class['students']; ?> طالب</span>
                        <span><?= htmlspecialchars($class['year'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <div class="class-actions">
                        <button class="ghost-btn small">تعديل</button>
                        <button class="primary-btn small">عرض</button>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>
