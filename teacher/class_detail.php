<?php
require '../config/auth.php';
require '../config/db.php';
requireLogin();
requireRole('teacher');

$class_id = (int)($_GET['id'] ?? 0);
$user_id = (int)$_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "
    SELECT c.id, c.class_name, c.level, c.academic_year
    FROM classes c
    WHERE c.id = ? AND c.teacher_id = ?
    LIMIT 1
");
mysqli_stmt_bind_param($stmt, "ii", $class_id, $user_id);
mysqli_stmt_execute($stmt);
$class = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$class) {
    die("الفصل غير موجود أو لا تملك الصلاحية لعرضه.");
}

$studentsStmt = mysqli_prepare($conn, "
    SELECT s.id, s.full_name, s.email, s.phone, s.student_code
    FROM class_students cs
    JOIN students s ON s.id = cs.student_id
    WHERE cs.class_id = ?
    ORDER BY s.full_name ASC
");
mysqli_stmt_bind_param($studentsStmt, "i", $class_id);
mysqli_stmt_execute($studentsStmt);
$students = mysqli_fetch_all(mysqli_stmt_get_result($studentsStmt), MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الفصل</title>
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
            <a href="classes.php" class="active">الفصول</a>
            <a href="documents.php">المستندات</a>
            <a href="inbox.php">البريد</a>
            <a href="../logout.php">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="content">
        <div class="topbar">
            <h1>الفصل: <?= htmlspecialchars($class['class_name'], ENT_QUOTES, 'UTF-8') ?></h1>
        </div>

        <section class="info-bar">
            <div>المستوى: <?= htmlspecialchars($class['level'], ENT_QUOTES, 'UTF-8') ?></div>
            <div>السنة الدراسية: <?= htmlspecialchars($class['academic_year'], ENT_QUOTES, 'UTF-8') ?></div>
        </section>

        <section class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>الاسم الكامل</th>
                        <th>البريد</th>
                        <th>الهاتف</th>
                        <th>رمز الطالب</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['full_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($student['email'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($student['phone'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($student['student_code'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
