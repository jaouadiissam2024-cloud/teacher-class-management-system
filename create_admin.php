<?php
require __DIR__ . '/config/db.php';

$email = 'admin@school.com';
$password = 'Admin@123';

$check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
mysqli_stmt_bind_param($check, "s", $email);
mysqli_stmt_execute($check);
$checkResult = mysqli_stmt_get_result($check);

if (mysqli_num_rows($checkResult) > 0) {
    echo "المسؤول موجود بالفعل.";
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = mysqli_prepare($conn, "
    INSERT INTO users (full_name, email, password_hash, role, specialty_id, is_active)
    VALUES ('المسؤول', ?, ?, 'admin', NULL, 1)
");
mysqli_stmt_bind_param($stmt, "ss", $email, $hash);

if (mysqli_stmt_execute($stmt)) {
    echo "تم إنشاء حساب المسؤول بنجاح.";
    echo "<br>البريد الإلكتروني: admin@school.com";
    echo "<br>كلمة المرور: Admin@123";
} else {
    echo "فشل إنشاء المسؤول.";
}
