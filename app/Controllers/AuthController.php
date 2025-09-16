<?php

class AuthController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLogin(): void
    {
        $user = $this->authService->getCurrentUser();
        include __DIR__ . '/../Views/login.php';
    }

    public function showRegister(): void
    {
        include __DIR__ . '/../Views/register.php';
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->authService->login($email, $password);
        if ($user) {
            header('Location: /');
            exit;
        } else {
            $error = 'Invalid credentials';
            $user = $this->authService->getCurrentUser();
            include __DIR__ . '/../Views/login.php';
        }
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $is_admin = isset($_POST['is_admin']) && $_POST['is_admin'] === '1' ? true : false;

        try {
            $this->authService->register($name, $email, $password, $is_admin);
            header('Location: /login');
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
            include __DIR__ . '/../Views/register.php';
        }
    }

    public function logout(): void
    {
        $this->authService->logout();
        header('Location: /');
        exit;
    }
}
