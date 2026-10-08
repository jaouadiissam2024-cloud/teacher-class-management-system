<?php
require __DIR__ . '/../config/bootstrap.php';
requireLogin('admin');

$classes = [
    ['name' => 'الصف 1A', 'level' => 'ابتدائي', 'teacher' => 'سارة محمد', 'students' => 28, 'year' => '2026/2027'],
    ['name' => 'الصف 2B', 'level' => 'ابتدائي', 'teacher' => 'نادية علي', 'students' => 25, 'year' => '2026/2027'],
    ['name' => 'الصف 3A', 'level' => 'متوسط', 'teacher' => 'إياد المومن', 'students' => 31, 'year' => '2026/2027'],
    ['name' => 'الصف 4C', 'level' => 'متوسط', 'teacher' => 'هدى سالم', 'students' => 27, 'year' => '2026/2027'],
];

$stats = [
    ['label' => 'الصفوف', 'value' => '48', 'trend' => '+8%', 'tone' => 'violet'],
    ['label' => 'المعلمين', 'value' => '16', 'trend' => '+2%', 'tone' => 'blue'],
    ['label' => 'الطلاب', 'value' => '940', 'trend' => '+14%', 'tone' => 'green'],
    ['label' => 'المستندات', 'value' => '132', 'trend' => '+22%', 'tone' => 'orange'],
];

$recent = [
    ['title' => 'تحديث جدول الحصص', 'time' => 'منذ 2 ساعة'],
    ['title' => 'إرسال مذكرة للمعلمين', 'time' => 'منذ 4 ساعة'],
    ['title' => 'مراجعة الحضور الأسبوعي', 'time' => 'اليوم'],
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الإدارة</title>
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
            <a class="active" href="/admin/dashboard.php">لوحة التحكم</a>
            <a href="/admin/classes.php">الفصول</a>
            <a href="/admin/messages.php">البريد</a>
            <a href="/admin/settings.php">الإعدادات</a>
            <a href="/logout.php">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="content-panel">
        <header class="topbar">
            <div>
                <span class="mini-label">مرحبًا</span>
                <h1><?= htmlspecialchars($_SESSION['user']['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
            </div>
            <div class="topbar-actions">
                <button class="ghost-btn">+ إضافة فصل</button>
                <button class="primary-btn">إرسال رسالة</button>
            </div>
        </header>

        <section class="stats-grid">
            <?php foreach ($stats as $stat): ?>
                <article class="stat-card <?= htmlspecialchars($stat['tone'], ENT_QUOTES, 'UTF-8'); ?>">
                    <span><?= htmlspecialchars($stat['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <strong><?= htmlspecialchars($stat['value'], ENT_QUOTES, 'UTF-8'); ?></strong>
                    <small><?= htmlspecialchars($stat['trend'], ENT_QUOTES, 'UTF-8'); ?></small>
                </article>
            <?php endforeach; ?>
        </section>

        <section class="dashboard-grid">
            <div class="panel-card wide-panel">
                <div class="section-head">
                    <h3>أحدث الفصول</h3>
                    <a href="/admin/classes.php">عرض الكل</a>
                </div>

                <div class="classes-list">
                    <?php foreach ($classes as $class): ?>
                        <div class="class-row">
                            <div>
                                <strong><?= htmlspecialchars($class['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                <small><?= htmlspecialchars($class['level'], ENT_QUOTES, 'UTF-8'); ?></small>
                            </div>
                            <div>
                                <small>المعلم</small>
                                <strong><?= htmlspecialchars($class['teacher'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            </div>
                            <div>
                                <small>الطلاب</small>
                                <strong><?= (int)$class['students']; ?></strong>
                            </div>
                            <div>
                                <small>السنة</small>
                                <strong><?= htmlspecialchars($class['year'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="panel-card">
                <div class="section-head">
                    <h3>آخر النشاطات</h3>
                </div>

                <ul class="activity-list">
                    <?php foreach ($recent as $item): ?>
                        <li>
                            <span class="dot"></span>
                            <div>
                                <strong><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                <small><?= htmlspecialchars($item['time'], ENT_QUOTES, 'UTF-8'); ?></small>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    </main>
</body>
</html>
