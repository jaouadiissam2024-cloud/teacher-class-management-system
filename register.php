<?php
require __DIR__ . '/config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = 'teacher';

    if ($name === '' || $email === '' || $password === '') {
        $error = 'يرجى ملء جميع الحقول الأساسية.';
    } elseif (userExists($email)) {
        $error = 'هذا البريد الإلكتروني مسجل بالفعل.';
    } else {
        $_SESSION['demo_users'][$email] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'role' => $role,
            'password' => $password,
        ];

        $_SESSION['user'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'role' => $role,
        ];

        header('Location: /teacher/dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-shell compact">
        <div class="auth-panel form-panel full-width">
            <div class="form-header">
                <span class="mini-label">عضو جديد</span>
                <h2>إنشاء حساب معلم</h2>
            </div>

            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form register-form">
                <div class="field-grid">
                    <label>
                        الاسم الكامل
                        <input type="text" name="name" placeholder="مثال: سارة محمد" required>
                    </label>

                    <label>
                        البريد الإلكتروني
                        <input type="email" name="email" placeholder="example@school.com" required>
                    </label>

                    <label>
                        الهاتف
                        <input type="text" name="phone" placeholder="+9665...">
                    </label>

                    <label>
                        كلمة المرور
                        <input type="password" name="password" placeholder="••••••••" required>
                    </label>
                </div>

                <button type="submit" class="primary-btn">تسجيل</button>
            </form>

            <div class="auth-meta">
                <a href="/login.php">العودة إلى تسجيل الدخول</a>
            </div>
        </div>
    </div>
</body>
</html>
