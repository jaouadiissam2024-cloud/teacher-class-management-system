<?php
require '../config/auth.php';
require '../config/db.php';
requireLogin();
requireRole('teacher');

$user_id = (int)$_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "
    SELECT m.id, m.subject, m.body, m.created_at, u.full_name AS sender
    FROM messages m
    JOIN users u ON u.id = m.sender_id
    WHERE m.receiver_id = ?
    ORDER BY m.created_at DESC
");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$messages = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البريد الداخلي</title>
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
            <a href="inbox.php" class="active">البريد</a>
            <a href="compose_mail.php">رسالة جديدة</a>
            <a href="../logout.php">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="content">
        <div class="topbar">
            <h1>صندوق البريد</h1>
        </div>

        <section class="panel">
            <a href="compose_mail.php" class="primary-link">رسالة جديدة</a>
        </section>

        <?php foreach ($messages as $msg): ?>
            <section class="message-box">
                <h3><?= htmlspecialchars($msg['subject'], ENT_QUOTES, 'UTF-8') ?></h3>
                <p class="message-meta">من: <?= htmlspecialchars($msg['sender'], ENT_QUOTES, 'UTF-8') ?></p>
                <p><?= nl2br(htmlspecialchars($msg['body'], ENT_QUOTES, 'UTF-8')) ?></p>
                <small><?= htmlspecialchars($msg['created_at'], ENT_QUOTES, 'UTF-8') ?></small>
            </section>
        <?php endforeach; ?>
    </main>
</body>
</html>
