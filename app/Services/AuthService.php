<?php

class AuthService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(string $name, string $email, string $password, bool $is_admin = false): User
    {
        if ($this->userRepository->findByEmail($email)) {
            throw new Exception('Email already exists');
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $this->userRepository->create([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'is_admin' => $is_admin,
        ]);
    }

    public function login(string $email, string $password): ?User
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
            return $user;
        }
        return null;
    }

    public function logout(): void
    {
        session_destroy();
    }

    public function getCurrentUser(): ?User
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        return $this->userRepository->findById($_SESSION['user_id']);
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function isAdmin(): bool
    {
        $user = $this->getCurrentUser();
        return $user && $user->is_admin;
    }
}
