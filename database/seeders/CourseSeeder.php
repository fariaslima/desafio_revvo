<?php

require_once __DIR__ . '/../../bootstrap.php';

$pdo = getPDO();

$courses = [
    [
        'title' => 'Curso de PHP Básico',
        'description' => 'Aprenda os fundamentos do PHP para desenvolvimento web.',
        'image' => 'php-basico.jpg',
        'category' => 'Programação',
    ],
    [
        'title' => 'JavaScript para Iniciantes',
        'description' => 'Introdução ao JavaScript e desenvolvimento front-end.',
        'image' => 'javascript-iniciantes.jpg',
        'category' => 'Programação',
    ],
    [
        'title' => 'Design Responsivo',
        'description' => 'Aprenda a criar layouts responsivos para dispositivos móveis.',
        'image' => 'design-responsivo.jpg',
        'category' => 'Design',
    ],
];

foreach ($courses as $course) {
    $stmt = $pdo->prepare("INSERT INTO courses (title, description, image, category) VALUES (:title, :description, :image, :category)");
    $stmt->execute([
        ':title' => $course['title'],
        ':description' => $course['description'],
        ':image' => $course['image'],
        ':category' => $course['category'],
    ]);
}

echo "UserSeeder executed successfully\n";
