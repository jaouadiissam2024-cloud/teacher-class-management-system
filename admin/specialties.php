<?php
require '../config/auth.php';
require '../config/db.php';
requireLogin();
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $name = trim($_POST['name'] ?? '');

    if ($name !== '') {
        $stmt = mysqli_prepare($conn, "INSERT INTO specialties (name) VALUES (?)");
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_stmt_execute($stmt);
    }
}

$result = mysqli_query($conn, "SELECT * FROM specialties ORDER BY name ASC");
$specialties = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التخصصات</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">S</div>
            <div>لوحة الإدارة</div>
        </div>

        <nav>
            <a href="dashboard.php">الرئيسية</a>
            <a href="teachers.php">المعلمين</a>
            <a href="specialties.php" class="active">التخصصات</a>
            <a href="../logout.php">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="content">
        <div class="topbar">
            <h1>إدارة التخصصات</h1>
        </div>

        <section class="panel">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                <div class="form-grid">
                    <input type="text" name="name" placeholder="اسم التخصص" required>
                    <button type="submit">إضافة</button>
                </div>
            </form>
        </section>

        <section class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>التخصص</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($specialties as $spec): ?>
                        <tr>
                            <td><?= htmlspecialchars($spec['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
