<?php
require __DIR__ . '/../config/bootstrap.php';
requireLogin('teacher');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $_SESSION['user']['name'] = trim($_POST['full_name'] ?? $_SESSION['user']['name']);
        $_SESSION['user']['phone'] = trim($_POST['phone'] ?? $_SESSION['user']['phone']);
        $_SESSION['user']['email'] = trim($_POST['email'] ?? $_SESSION['user']['email']);
        $success = 'تم تحديث بياناتك بنجاح.';
    }

    if (isset($_POST['change_password'])) {
        $newPassword = $_POST['new_password'] ?? '';
        if ($newPassword !== '') {
            $_SESSION['user']['password'] = $newPassword;
            $success = 'تم تحديث كلمة المرور بنجاح.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الإعدادات</title>
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
            <a href="/teacher/messages.php">البريد</a>
            <a class="active" href="/teacher/settings.php">الإعدادات</a>
            <a href="/logout.php">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="content-panel">
        <header class="topbar">
            <div>
                <span class="mini-label">الحساب</span>
                <h1>الإعدادات</h1>
            </div>
        </header>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <section class="settings-grid">
            <div class="panel-card">
                <div class="section-head">
                    <h3>تعديل الملف الشخصي</h3>
                </div>
                <form method="POST" class="settings-form">
                    <input type="hidden" name="update_profile" value="1">
                    <label>الاسم الكامل
                        <input type="text" name="full_name" value="<?= htmlspecialchars($_SESSION['user']['name'], ENT_QUOTES, 'UTF-8'); ?>">
                    </label>
                    <label>البريد الإلكتروني
                        <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>">
                    </label>
                    <label>رقم الهاتف
                        <input type="text" name="phone" value="<?= htmlspecialchars($_SESSION['user']['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    </label>
                    <button type="submit" class="primary-btn">حفظ التغييرات</button>
                </form>
            </div>

            <div class="panel-card">
                <div class="section-head">
                    <h3>تغيير كلمة المرور</h3>
                </div>
                <form method="POST" class="settings-form">
                    <input type="hidden" name="change_password" value="1">
                    <label>كلمة المرور الجديدة
                        <input type="password" name="new_password" placeholder="••••••••">
                    </label>
                    <button type="submit" class="primary-btn">تحديث كلمة المرور</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
