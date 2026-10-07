<?php
require '../config/auth.php';
require '../config/db.php';
requireLogin();
requireRole('teacher');

$user_id = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document'])) {
    $title = trim($_POST['title'] ?? '');
    $category = $_POST['category'] ?? 'autre';
    $specialty_id = (int)($_POST['specialty_id'] ?? 0);

    $file = $_FILES['document'];

    if ($title === '') {
        die("يرجى إدخال عنوان الملف.");
    }

    $allowedExt = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'zip'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        die("نوع الملف غير مسموح به.");
    }

    if ($file['size'] > 10 * 1024 * 1024) {
        die("حجم الملف كبير جدًا. الحد الأقصى 10MB.");
    }

    $storedName = uniqid('doc_', true) . '.' . $ext;
    $targetDir = '../uploads/documents/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $targetPath = $targetDir . $storedName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        die("فشل في حفظ الملف.");
    }

    $stmt = mysqli_prepare($conn, "
        INSERT INTO documents (teacher_id, specialty_id, title, category, original_name, stored_name, file_path, mime_type)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "iissssss", $user_id, $specialty_id, $title, $category, $file['name'], $storedName, $targetPath, mime_content_type($file['tmp_name']));
    mysqli_stmt_execute($stmt);

    $success = "تم رفع الملف بنجاح.";
}

$result = mysqli_prepare($conn, "
    SELECT d.id, d.title, d.category, d.original_name, d.created_at, s.name AS specialty
    FROM documents d
    LEFT JOIN specialties s ON s.id = d.specialty_id
    WHERE d.teacher_id = ?
    ORDER BY d.created_at DESC
");
mysqli_stmt_bind_param($result, "i", $user_id);
mysqli_stmt_execute($result);
$documents = mysqli_fetch_all(mysqli_stmt_get_result($result), MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المستندات</title>
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
            <a href="import_xml.php">استيراد XML</a>
            <a href="documents.php" class="active">المستندات</a>
            <a href="inbox.php">البريد</a>
            <a href="../logout.php">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="content">
        <div class="topbar">
            <h1>المستندات</h1>
        </div>

        <?php if (!empty($success)) : ?>
            <div class="alert success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <section class="panel">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <input type="text" name="title" placeholder="عنوان الملف" required>
                    <select name="category" required>
                        <option value="cours">Cours</option>
                        <option value="tp">TP</option>
                        <option value="serie">Série</option>
                        <option value="corrige">Corrigé</option>
                        <option value="devoir">Devoir</option>
                        <option value="correction">Correction</option>
                        <option value="autre">Autre</option>
                    </select>
                    <input type="file" name="document" required>
                </div>
                <button type="submit">رفع الملف</button>
            </form>
        </section>

        <section class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>العنوان</th>
                        <th>الفئة</th>
                        <th>اسم الملف</th>
                        <th>التخصص</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($documents as $doc): ?>
                        <tr>
                            <td><?= htmlspecialchars($doc['title'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($doc['category'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($doc['original_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($doc['specialty'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
