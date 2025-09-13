<?php

class HomeController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index(): void
    {
        $user = $this->authService->getCurrentUser();

        include __DIR__ . '/../Views/home.php';
    }
}
