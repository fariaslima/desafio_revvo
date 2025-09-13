<?php

require_once __DIR__ . '/../bootstrap.php';

$pdo = getPDO();

$userRepo = new UserRepository($pdo);

$authService = new AuthService($userRepo);

$authMiddleware = new AuthMiddleware($authService);

$homeController = new HomeController($authService);
$authController = new AuthController($authService);

$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

switch ($path) {
    case '/':
        $homeController->index();
        break;
    case '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            $authController->showLogin();
        }
        break;
    case '/register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            $authController->showRegister();
        }
        break;
    case '/logout':
        $authController->logout();
        break;
    default:
        http_response_code(404);
        echo 'Página não encontrada';
        break;
}
