<?php
require __DIR__ . '/db.php';

$email = 'admin@school.com';
$password = 'Admin@123';

$check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$check->bind_param("s", $email);
$check->execute();
$checkResult = $check->get_result();

if ($checkResult->num_rows > 0) {
    echo "المسؤول موجود بالفعل.";
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    INSERT INTO users (full_name, email, password_hash, role, is_active)
    VALUES ('المسؤول', ?, ?, 'admin', 1)");
$stmt->bind_param("ss", $email, $hash);

if ($stmt->execute()) {
    echo "تم إنشاء حساب المسؤول بنجاح.";
    echo "<br>البريد الإلكتروني: admin@school.com";
    echo "<br>كلمة المرور: Admin@123";
} else {
    echo "فشل إنشاء المسؤول.";
}
?>
