<?php

class AuthMiddleware
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function handle(): void
    {
        if (!$this->authService->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }

    public function handleAdmin(): void
    {
        $this->handle();
        if (!$this->authService->isAdmin()) {
            header('Location: /');
            exit;
        }
    }
}
