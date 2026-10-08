<?php
require __DIR__ . '/../config/bootstrap.php';
requireLogin('teacher');

$messages = messageInbox();
$activeMessage = $messages[0];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البريد الداخلي</title>
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
            <a href="/teacher/classes.php">الفصول</a>
            <a class="active" href="/teacher/messages.php">البريد</a>
            <a href="/teacher/settings.php">الإعدادات</a>
            <a href="/logout.php">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="content-panel mail-panel">
        <header class="topbar">
            <div>
                <span class="mini-label">البريد</span>
                <h1>صندوق الوارد</h1>
            </div>
            <button class="primary-btn">+ رسالة جديدة</button>
        </header>

        <div class="mail-layout">
            <aside class="mail-sidebar">
                <?php foreach ($messages as $message): ?>
                    <div class="mail-item <?= ($message['unread'] ? 'unread' : 'read'); ?>">
                        <div class="mail-sender">
                            <strong><?= htmlspecialchars($message['from'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            <span><?= htmlspecialchars($message['tag'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        <p><?= htmlspecialchars($message['subject'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <small><?= htmlspecialchars($message['preview'], ENT_QUOTES, 'UTF-8'); ?></small>
                        <time><?= htmlspecialchars($message['time'], ENT_QUOTES, 'UTF-8'); ?></time>
                    </div>
                <?php endforeach; ?>
            </aside>

            <section class="mail-content">
                <div class="mail-header">
                    <div>
                        <span class="mini-label">الرسالة</span>
                        <h3><?= htmlspecialchars($activeMessage['subject'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    </div>
                    <div class="mail-actions">
                        <button class="ghost-btn small">رد</button>
                        <button class="ghost-btn small">حذف</button>
                    </div>
                </div>

                <div class="mail-body">
                    <p>السلام عليكم، تم نشر إعلان مهم حول التقييم...</p>
                    <p>يرجى مراجعة المستندات المرفقة قبل يوم الاثنين، مع التركيز على الفئة العمرية في الصفوف المختلفة.</p>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
