<?php
require '../config/auth.php';
require '../config/db.php';
requireLogin();
requireRole('teacher');

$user_id = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['xml_file'])) {
        $file = $_FILES['xml_file'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            die("حدث خطأ أثناء رفع الملف.");
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'xml') {
            die("يجب أن يكون الملف بصيغة XML.");
        }

        $xml = simplexml_load_file($file['tmp_name']);
        if ($xml === false) {
            die("ملف XML غير صالح.");
        }

        $count = 0;

        foreach ($xml->class as $classNode) {
            $class_name = trim((string)$classNode->name);
            $level = trim((string)$classNode->level);
            $academic_year = trim((string)$classNode->academic_year);
            $specialty_name = trim((string)$classNode->specialty);

            if ($class_name === '' || $level === '' || $academic_year === '') {
                continue;
            }

            $specialtyStmt = mysqli_prepare($conn, "SELECT id FROM specialties WHERE name = ? LIMIT 1");
            mysqli_stmt_bind_param($specialtyStmt, "s", $specialty_name);
            mysqli_stmt_execute($specialtyStmt);
            $specialty = mysqli_fetch_assoc(mysqli_stmt_get_result($specialtyStmt));

            if (!$specialty) {
                $ins = mysqli_prepare($conn, "INSERT INTO specialties (name) VALUES (?)");
                mysqli_stmt_bind_param($ins, "s", $specialty_name);
                mysqli_stmt_execute($ins);
                $specialty_id = mysqli_insert_id($conn);
            } else {
                $specialty_id = $specialty['id'];
            }

            $classStmt = mysqli_prepare($conn, "
                INSERT INTO classes (teacher_id, specialty_id, class_name, level, academic_year)
                VALUES (?, ?, ?, ?, ?)
            ");
            mysqli_stmt_bind_param($classStmt, "iisss", $user_id, $specialty_id, $class_name, $level, $academic_year);
            mysqli_stmt_execute($classStmt);
            $class_id = mysqli_insert_id($conn);

            foreach ($classNode->students->student as $studentNode) {
                $full_name = trim((string)$studentNode->full_name);
                $email = trim((string)$studentNode->email);
                $phone = trim((string)$studentNode->phone);
                $code = trim((string)$studentNode->student_code);

                if ($full_name === '') continue;

                $studentCheck = mysqli_prepare($conn, "
                    SELECT id FROM students
                    WHERE email = ? OR student_code = ?
                    LIMIT 1
                ");
                mysqli_stmt_bind_param($studentCheck, "ss", $email, $code);
                mysqli_stmt_execute($studentCheck);
                $existing = mysqli_fetch_assoc(mysqli_stmt_get_result($studentCheck));

                if ($existing) {
                    $student_id = (int)$existing['id'];
                } else {
                    $insertStudent = mysqli_prepare($conn, "
                        INSERT INTO students (full_name, email, phone, student_code)
                        VALUES (?, ?, ?, ?)
                    ");
                    mysqli_stmt_bind_param($insertStudent, "ssss", $full_name, $email, $phone, $code);
                    mysqli_stmt_execute($insertStudent);
                    $student_id = mysqli_insert_id($conn);
                }

                $link = mysqli_prepare($conn, "
                    INSERT INTO class_students (class_id, student_id)
                    VALUES (?, ?)
                    ON DUPLICATE KEY UPDATE class_id = class_id
                ");
                mysqli_stmt_bind_param($link, "ii", $class_id, $student_id);
                mysqli_stmt_execute($link);

                $count++;
            }
        }

        echo "تم استيراد الملف بنجاح. عدد الطلبة المستوردة: " . $count;
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استيراد XML</title>
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
            <a href="import_xml.php" class="active">استيراد XML</a>
            <a href="documents.php">المستندات</a>
            <a href="inbox.php">البريد</a>
            <a href="../logout.php">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="content">
        <div class="topbar">
            <h1>استيراد فصول من XML</h1>
        </div>

        <section class="panel">
            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="xml_file" accept=".xml" required>
                <button type="submit">رفع واستيراد</button>
            </form>
        </section>
    </main>
</body>
</html>
