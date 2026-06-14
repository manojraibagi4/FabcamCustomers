<?php
/**
 * Run once to create/reset admin and sales users.
 * Usage: php seed_users.php
 */

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';

$pdo = new PDO(
    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
    DB_USER, DB_PASS,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$users = [
    [
        'name'     => 'Administrator',
        'email'    => 'admin@fabcam.com',
        'password' => 'Admin@123',
        'role'     => 'admin',
    ],
    [
        'name'     => 'Sales User',
        'email'    => 'sales@fabcam.com',
        'password' => 'Sales@123',
        'role'     => 'sales',
    ],
];

$stmt = $pdo->prepare(
    'INSERT INTO users (name, email, password_hash, role, is_active)
     VALUES (:name, :email, :hash, :role, 1)
     ON DUPLICATE KEY UPDATE
       name          = VALUES(name),
       password_hash = VALUES(password_hash),
       role          = VALUES(role),
       is_active     = 1'
);

foreach ($users as $u) {
    $hash = password_hash($u['password'], PASSWORD_BCRYPT);
    $stmt->execute([
        ':name'  => $u['name'],
        ':email' => $u['email'],
        ':hash'  => $hash,
        ':role'  => $u['role'],
    ]);
    echo "✓ {$u['role']} user created: {$u['email']}  password: {$u['password']}\n";
    echo "  Hash: {$hash}\n\n";
}

echo "Done. You can delete this file after running it.\n";
