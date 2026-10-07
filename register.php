<?php
require __DIR__ . '/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $specialty_id = (int)($_POST['specialty_id'] ?? 0);

    if ($full_name === '' || $email === '' || $password === '') {
        $error = "يرجى ملء الحقول الضرورية.";
    } else {
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        $checkResult = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($checkResult) > 0) {
            $error = "هذا البريد الإلكتروني موجود سابقًا.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare($conn, "
                INSERT INTO users (full_name, email, phone, password_hash, role, specialty_id, is_active)
                VALUES (?, ?, ?, ?, 'teacher', ?, 1)
            ");
            mysqli_stmt_bind_param($stmt, "ssssi", $full_name, $email, $phone, $hash, $specialty_id);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: login.php");
                exit;
            } else {
                $error = "فشل في إنشاء الحساب.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب معلم</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-body">
    <div class="auth-card">
        <div class="auth-logo">T</div>
        <h2>إنشاء حساب معلم</h2>

        <?php if (!empty($error)) : ?>
            <div class="alert error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="full_name" placeholder="الاسم الكامل" required>
            <input type="email" name="email" placeholder="البريد الإلكتروني" required>
            <input type="text" name="phone" placeholder="رقم الهاتف">
            <select name="specialty_id" required>
                <option value="">اختر تخصصك</option>
                <option value="1">علوم</option>
                <option value="2">رياضيات</option>
                <option value="3">لغة عربية</option>
                <option value="4">تربية</option>
            </select>
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <button type="submit">تسجيل</button>
        </form>
    </div>
</body>
</html>
