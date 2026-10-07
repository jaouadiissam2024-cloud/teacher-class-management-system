<?php
require '../config/auth.php';
require '../config/db.php';
requireLogin();
requireRole('teacher');

$user_id = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $class_name = trim($_POST['class_name'] ?? '');
        $level = trim($_POST['level'] ?? '');
        $academic_year = trim($_POST['academic_year'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $specialty_id = (int)($_POST['specialty_id'] ?? 0);

        if ($class_name !== '' && $level !== '' && $academic_year !== '') {
            $stmt = mysqli_prepare($conn, "
                INSERT INTO classes (teacher_id, specialty_id, class_name, level, academic_year, description)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            mysqli_stmt_bind_param($stmt, "iissss", $user_id, $specialty_id, $class_name, $level, $academic_year, $description);
            mysqli_stmt_execute($stmt);
        }
    }

    if ($action === 'delete') {
        $class_id = (int)($_POST['class_id'] ?? 0);

        $delStudents = mysqli_prepare($conn, "DELETE FROM class_students WHERE class_id = ?");
        mysqli_stmt_bind_param($delStudents, "i", $class_id);
        mysqli_stmt_execute($delStudents);

        $delClass = mysqli_prepare($conn, "DELETE FROM classes WHERE id = ? AND teacher_id = ?");
        mysqli_stmt_bind_param($delClass, "ii", $class_id, $user_id);
        mysqli_stmt_execute($delClass);
    }
}

$specialtiesResult = mysqli_query($conn, "SELECT * FROM specialties ORDER BY name ASC");
$specialties = mysqli_fetch_all($specialtiesResult, MYSQLI_ASSOC);

$stmt = mysqli_prepare($conn, "
    SELECT c.id, c.class_name, c.level, c.academic_year, c.description
    FROM classes c
    WHERE c.teacher_id = ?
    ORDER BY c.created_at DESC
");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$classes = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الفصول</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/app.js" defer></script>
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
            <a href="import_xml.php">استيراد XML</a>
            <a href="documents.php">المستندات</a>
            <a href="inbox.php">البريد</a>
            <a href="../logout.php">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="content">
        <div class="topbar">
            <h1>إدارة الفصول</h1>
        </div>

        <section class="panel">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="action" value="add">

                <div class="form-grid">
                    <input type="text" name="class_name" placeholder="اسم الفصل" required>
                    <input type="text" name="level" placeholder="المستوى" required>
                    <input type="text" name="academic_year" placeholder="السنة الدراسية" required>
                    <select name="specialty_id" required>
                        <option value="">اختر التخصص</option>
                        <?php foreach ($specialties as $spec): ?>
                            <option value="<?= (int)$spec['id'] ?>"><?= htmlspecialchars($spec['name'], ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <textarea name="description" rows="4" placeholder="وصف الفصل"></textarea>
                <button type="submit">إضافة فصل</button>
            </form>
        </section>

        <section class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>اسم الفصل</th>
                        <th>المستوى</th>
                        <th>السنة</th>
                        <th>الوصف</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class): ?>
                        <tr>
                            <td><?= htmlspecialchars($class['class_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($class['level'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($class['academic_year'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($class['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <div class="row-actions">
                                    <a href="class_detail.php?id=<?= (int)$class['id'] ?>">عرض</a>

                                    <form method="POST" class="inline-delete">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="class_id" value="<?= (int)$class['id'] ?>">
                                        <button type="submit" class="danger-button">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
