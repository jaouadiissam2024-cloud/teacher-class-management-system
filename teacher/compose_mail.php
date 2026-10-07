<?php
require '../config/auth.php';
require '../config/db.php';
requireLogin();
requireRole('teacher');

$user_id = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_email = trim($_POST['receiver_email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $body = trim($_POST['body'] ?? '');

    if ($receiver_email !== '' && $subject !== '' && $body !== '') {
        $receiverStmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($receiverStmt, "s", $receiver_email);
        mysqli_stmt_execute($receiverStmt);
        $receiver = mysqli_fetch_assoc(mysqli_stmt_get_result($receiverStmt));

        if (!$receiver) {
            $error = "المستلم غير موجود.";
        } else {
            $insert = mysqli_prepare($conn, "
                INSERT INTO messages (sender_id, receiver_id, subject, body)
                VALUES (?, ?, ?, ?)
            ");
            mysqli_stmt_bind_param($insert, "iiss", $user_id, $receiver['id'], $subject, $body);
            mysqli_stmt_execute($insert);

            $success = "تم إرسال الرسالة بنجاح.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رسالة جديدة</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">S</div>
            <div>مساحة المعلم</div>
        </div>

        <nav>
            <a href="dashboard.php">الرئيسية</a>
            <a href="classes.php">الفصول</a>
            <a href="documents.php">المستندات</a>
            <a href="inbox.php">البريد</a>
            <a href="compose_mail.php" class="active">رسالة جديدة</a>
            <a href="../logout.php">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="content">
        <div class="topbar">
            <h1>إرسال رسالة</h1>
        </div>

        <section class="panel">
            <?php if (!empty($error)) : ?>
                <div class="alert error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <?php if (!empty($success)) : ?>
                <div class="alert success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-grid">
                    <input type="email" name="receiver_email" placeholder="بريد المستقبل" required>
                    <input type="text" name="subject" placeholder="الموضوع" required>
                </div>
                <textarea name="body" rows="6" placeholder="نص الرسالة" required></textarea>
                <button type="submit">إرسال</button>
            </form>
        </section>
    </main>
</body>
</html>
