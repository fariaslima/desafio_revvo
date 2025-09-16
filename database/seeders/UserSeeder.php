<?php

require_once __DIR__ . '/../../bootstrap.php';

$pdo = getPDO();

$users = [
    [
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'is_admin' => 1,
    ],
    [
        'name' => 'User',
        'email' => 'user@example.com',
        'password' => password_hash('user123', PASSWORD_DEFAULT),
        'is_admin' => 0,
    ],
];

foreach ($users as $user) {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $user['name'],
        $user['email'],
        $user['password'],
        $user['is_admin'],
    ]);
}

echo "UserSeeder executed successfully\n";
