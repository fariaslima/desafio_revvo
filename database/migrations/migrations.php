<?php

// Run migrations
require_once __DIR__ . '/2025_09_12_create_courses_table.php';
require_once __DIR__ . '/2024_10_01_create_users_table.php';

// Run seeders
require_once __DIR__ . '/../seeders/UserSeeder.php';
require_once __DIR__ . '/../seeders/CourseSeeder.php';

echo "All migrations and seeders executed successfully.\n";
