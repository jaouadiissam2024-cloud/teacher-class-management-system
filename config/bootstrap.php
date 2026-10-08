<?php
session_start();

function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function requireLogin(?string $role = null): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: /login.php');
        exit;
    }

    if ($role !== null && $_SESSION['user']['role'] !== $role) {
        header('Location: /index.php');
        exit;
    }
}

function demoUsers(): array
{
    return [
        'admin@school.com' => [
            'name' => 'المسؤول',
            'email' => 'admin@school.com',
            'phone' => '+966500000000',
            'role' => 'admin',
            'password' => 'Admin@123',
        ],
        'teacher@school.com' => [
            'name' => 'سارة محمد',
            'email' => 'teacher@school.com',
            'phone' => '+966500000001',
            'role' => 'teacher',
            'password' => 'Teacher@123',
        ],
    ];
}

function userExists(string $email): bool
{
    return isset(demoUsers()[$email]);
}

function getUserByEmail(string $email): ?array
{
    $users = demoUsers();
    return $users[$email] ?? null;
}

function messageInbox(): array
{
    $admin = [
        ['id' => 1, 'from' => 'مكتب الإدارة', 'subject' => 'تحديث جدول الحصص', 'preview' => 'تم تعديل جدول الحصص الأسبوعي...', 'time' => '09:30', 'unread' => true, 'tag' => 'إدارة'],
        ['id' => 2, 'from' => 'سارة محمد', 'subject' => 'حضور الفصل', 'preview' => 'أرفقت قائمة الحضور للصف الثاني...', 'time' => 'أمس', 'unread' => false, 'tag' => 'معلم'],
        ['id' => 3, 'from' => 'عبد الرحمن', 'subject' => 'طلب وثيقة', 'preview' => 'أرغب في الحصول على نسخة PDF...', 'time' => 'الأحد', 'unread' => false, 'tag' => 'طالب'],
    ];

    $teacher = [
        ['id' => 1, 'from' => 'إدارة المدرسة', 'subject' => 'إعلان مهم', 'preview' => 'تحديث جديد حول الأنشطة...', 'time' => '08:15', 'unread' => true, 'tag' => 'إدارة'],
        ['id' => 2, 'from' => 'أحمد علي', 'subject' => 'مراجعة devoir', 'preview' => 'أرغب في الاطلاع على التصحيح...', 'time' => '10:45', 'unread' => false, 'tag' => 'طالب'],
        ['id' => 3, 'from' => 'نور الدين', 'subject' => 'تأكيد حضور', 'preview' => 'سأحضر حصتي اليوم في تمام...', 'time' => 'أمس', 'unread' => false, 'tag' => 'طالب'],
    ];

    return $_SESSION['user']['role'] === 'admin' ? $admin : $teacher;
}

function currentYear(): string
{
    return date('Y');
}
