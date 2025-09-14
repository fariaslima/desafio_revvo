<?php

require_once __DIR__ . '/../bootstrap.php';

$pdo = getPDO();

$userRepo = new UserRepository($pdo);

$authService = new AuthService($userRepo);


$authMiddleware = new AuthMiddleware($authService);

require_once __DIR__ . '/../app/Route.php';

$courseRepo = new CourseRepository($pdo);
$courseService = new CourseService($courseRepo);
$courseController = new CourseController($courseService);

$homeController = new HomeController($authService, $courseService);
$authController = new AuthController($authService);

$router = new Router();

// Home route
$router->add('GET', '/', [$homeController, 'index']);

// Auth routes
$router->add('GET', '/login', [$authController, 'showLogin']);
$router->add('POST', '/login', [$authController, 'login']);
$router->add('GET', '/register', [$authController, 'showRegister']);
$router->add('POST', '/register', [$authController, 'register']);
$router->add('GET', '/logout', [$authController, 'logout']);

// Course routes (API)
$router->add('GET', '/courses', [$courseController, 'index']);
$router->add('GET', '/courses/{id}', [$courseController, 'show']);
$router->add('POST', '/courses', [$courseController, 'store']);
$router->add('POST', '/courses/{id}', [$courseController, 'update']);
$router->add('DELETE', '/courses/{id}', [$courseController, 'destroy']);

// Image serving route
$router->add('GET', '/image/{filename}', function($filename) {
    $filePath = __DIR__ . '/../storage/' . $filename;
    if (file_exists($filePath) && is_file($filePath)) {
        $mime = mime_content_type($filePath);
        header('Content-Type: ' . $mime);
        readfile($filePath);
        exit;
    } else {
        http_response_code(404);
        echo 'Image not found';
    }
});

// Dispatch the request
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->dispatch($method, $uri);
