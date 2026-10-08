<?php
require __DIR__ . '/config/bootstrap.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'يرجى ملء جميع الحقول.';
    } else {
        $user = getUserByEmail($email);

        if ($user && $user['password'] === $password) {
            $_SESSION['user'] = [
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'role' => $user['role'],
            ];

            if ($user['role'] === 'admin') {
                header('Location: /admin/dashboard.php');
            } else {
                header('Location: /teacher/dashboard.php');
            }
            exit;
        }

        $error = 'البريد الإلكتروني أو كلمة المرور غير صحيحة.';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-shell">
        <div class="auth-panel brand-panel">
            <div class="brand-badge">S</div>
            <h1>SchoolFlow</h1>
            <p>نظام ذكي لإدارة الصفوف، المعلمين، الوثائق، والاتصالات داخل المؤسسة التعليمية.</p>

            <div class="feature-list">
                <div class="feature-item">
                    <span>✓</span>
                    <p>لوحة تحكم احترافية</p>
                </div>
                <div class="feature-item">
                    <span>✓</span>
                    <p>إدارة الصفوف والفصول</p>
                </div>
                <div class="feature-item">
                    <span>✓</span>
                    <p>صندوق بريد داخلي</p>
                </div>
            </div>
        </div>

        <div class="auth-panel form-panel">
            <div class="form-header">
                <span class="mini-label">مرحبًا</span>
                <h2>تسجيل الدخول</h2>
            </div>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <label>
                    البريد الإلكتروني
                    <input type="email" name="email" placeholder="admin@school.com" required>
                </label>

                <label>
                    كلمة المرور
                    <input type="password" name="password" placeholder="••••••••" required>
                </label>

                <button type="submit" class="primary-btn">دخول</button>
            </form>

            <div class="auth-meta">
                <span>أو</span>
                <a href="/register.php">إنشاء حساب جديد</a>
            </div>

            <div class="demo-box">
                <strong>حسابات تجريبية</strong>
                <p>admin@school.com / Admin@123</p>
                <p>teacher@school.com / Teacher@123</p>
            </div>
        </div>
    </div>
</body>
</html>
