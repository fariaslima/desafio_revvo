<?php

require_once __DIR__ . '/../../config/database.php';

$pdo = getPDO();

$sql = "
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

try {
    $pdo->exec($sql);
    echo "Courses table created successfully.\n";
} catch (PDOException $e) {
    echo "Error creating courses table: " . $e->getMessage() . "\n";
}
