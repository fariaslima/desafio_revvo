<?php

require_once __DIR__ . '/../../bootstrap.php';

$pdo = getPDO();

$courses = [
    [
        'title' => 'Curso de Introdução ao PHP',
        'description' => 'Aprenda os fundamentos do PHP para desenvolvimento web.',
        'image' => 'curso_introducao-ao-php_2171.png',
        'link' => 'https://google.com',
    ],
    [
        'title' => '.NET Framework',
        'description' => 'Curso de introdução ao .NET Framework para desenvolvimento de aplicações.',
        'image' => 'indtroducao-ao-net.jpg',
        'link' => 'https://google.com',
    ],
    [
        'title' => 'Inteligência Emocional',
        'description' => 'Entenda o que é inteligência emocional e como aplicá-la no dia a dia.',
        'image' => 'Inteligencia-Emocional.jpg',
        'link' => 'https://google.com',
    ],
    [
        'title' => 'Como ser um Chef',
        'description' => 'Como se tornar um chef de cozinha e dominar a arte culinária.',
        'image' => 'como-ser-um-chef-de-cozinha-1024x706.jpg',
        'link' => 'https://google.com',
    ],
];

foreach ($courses as $course) {
    $stmt = $pdo->prepare("INSERT INTO courses (title, description, image, link) VALUES (:title, :description, :image, :link)");
    $stmt->execute([
        ':title' => $course['title'],
        ':description' => $course['description'],
        ':image' => $course['image'],
        ':link' => $course['link'],
    ]);
}

echo "UserSeeder executed successfully\n";
