<?php
require __DIR__ . '/../config/bootstrap.php';
requireLogin('teacher');

$classes = [
    ['name' => 'الصف 2A', 'level' => 'ابتدائي', 'students' => 30, 'year' => '2026/2027'],
    ['name' => 'الصف 4B', 'level' => 'متوسط', 'students' => 26, 'year' => '2026/2027'],
    ['name' => 'الصف 6A', 'level' => 'ثانوي', 'students' => 22, 'year' => '2026/2027'],
];

$stats = [
    ['label' => 'الطلاب', 'value' => '120', 'trend' => '+7%', 'tone' => 'green'],
    ['label' => 'الحصص', 'value' => '18', 'trend' => '+3%', 'tone' => 'blue'],
    ['label' => 'الوثائق', 'value' => '35', 'trend' => '+12%', 'tone' => 'violet'],
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة المعلم</title>
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
            <a class="active" href="/teacher/dashboard.php">لوحة التحكم</a>
            <a href="/teacher/classes.php">الفصول</a>
            <a href="/teacher/messages.php">البريد</a>
            <a href="/teacher/settings.php">الإعدادات</a>
            <a href="/logout.php">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="content-panel">
        <header class="topbar">
            <div>
                <span class="mini-label">مرحبًا</span>
                <h1><?= htmlspecialchars($_SESSION['user']['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
            </div>
            <button class="primary-btn">إضافة ملف</button>
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
                    <h3>الفصول الموكلة</h3>
                    <a href="/teacher/classes.php">عرض الكل</a>
                </div>

                <div class="classes-list">
                    <?php foreach ($classes as $class): ?>
                        <div class="class-row">
                            <div>
                                <strong><?= htmlspecialchars($class['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                <small><?= htmlspecialchars($class['level'], ENT_QUOTES, 'UTF-8'); ?></small>
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
                    <h3>ملاحظات سريعة</h3>
                </div>
                <ul class="activity-list">
                    <li>
                        <span class="dot"></span>
                        <div>
                            <strong>تجهيز الحصة القادمة</strong>
                            <small>الساعة 10:00</small>
                        </div>
                    </li>
                    <li>
                        <span class="dot"></span>
                        <div>
                            <strong>إرسال devoir</strong>
                            <small>اليوم</small>
                        </div>
                    </li>
                </ul>
            </div>
        </section>
    </main>
</body>
</html>
