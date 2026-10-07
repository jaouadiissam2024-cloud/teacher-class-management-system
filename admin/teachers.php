<?php
require '../config/auth.php';
require '../config/db.php';
requireLogin();
requireRole('admin');

$result = mysqli_query($conn, "
    SELECT u.id, u.full_name, u.email, u.phone, s.name AS specialty
    FROM users u
    LEFT JOIN specialties s ON s.id = u.specialty_id
    WHERE u.role = 'teacher'
    ORDER BY u.created_at DESC
");
$teachers = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المعلمين</title>
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
            <a href="teachers.php" class="active">المعلمين</a>
            <a href="specialties.php">التخصصات</a>
            <a href="../logout.php">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="content">
        <div class="topbar">
            <h1>قائمة المعلمين</h1>
        </div>

        <section class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>البريد</th>
                        <th>الهاتف</th>
                        <th>التخصص</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teachers as $teacher): ?>
                        <tr>
                            <td><?= htmlspecialchars($teacher['full_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($teacher['email'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($teacher['phone'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($teacher['specialty'] ?? 'غير محدد', ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
