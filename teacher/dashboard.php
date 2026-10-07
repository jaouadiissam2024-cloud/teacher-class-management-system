<?php
require '../config/auth.php';
require '../config/db.php';
requireLogin();
requireRole('teacher');

$user_id = (int)$_SESSION['user_id'];

$classesStmt = mysqli_prepare($conn, "
    SELECT c.id, c.class_name, c.level, c.academic_year, COUNT(cs.student_id) AS total_students
    FROM classes c
    LEFT JOIN class_students cs ON cs.class_id = c.id
    WHERE c.teacher_id = ?
    GROUP BY c.id
    ORDER BY c.created_at DESC
");
mysqli_stmt_bind_param($classesStmt, "i", $user_id);
mysqli_stmt_execute($classesStmt);
$classes = mysqli_fetch_all(mysqli_stmt_get_result($classesStmt), MYSQLI_ASSOC);

$studentsCountStmt = mysqli_prepare($conn, "
    SELECT COUNT(*) AS total
    FROM class_students cs
    JOIN classes c ON c.id = cs.class_id
    WHERE c.teacher_id = ?
");
mysqli_stmt_bind_param($studentsCountStmt, "i", $user_id);
mysqli_stmt_execute($studentsCountStmt);
$totalStudents = mysqli_fetch_assoc(mysqli_stmt_get_result($studentsCountStmt))['total'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة المعلم</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">S</div>
            <div>مساحة المعلم</div>
        </div>

        <nav>
            <a href="dashboard.php" class="active">الرئيسية</a>
            <a href="classes.php">الفصول</a>
            <a href="import_xml.php">استيراد XML</a>
            <a href="documents.php">المستندات</a>
            <a href="inbox.php">البريد</a>
            <a href="../logout.php">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="content">
        <div class="topbar">
            <h1>مرحبًا، <?= htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') ?></h1>
        </div>

        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">الفصول</div>
                <div class="stat-number"><?= count($classes) ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-label">الطلبة</div>
                <div class="stat-number"><?= (int)$totalStudents ?></div>
            </div>
        </section>

        <section class="table-card">
            <h2>الفصول الحالية</h2>

            <table>
                <thead>
                    <tr>
                        <th>اسم الفصل</th>
                        <th>المستوى</th>
                        <th>السنة الدراسية</th>
                        <th>عدد الطلبة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class): ?>
                        <tr>
                            <td><?= htmlspecialchars($class['class_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($class['level'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($class['academic_year'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= (int)$class['total_students'] ?></td>
                            <td><a href="class_detail.php?id=<?= (int)$class['id'] ?>">عرض</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
