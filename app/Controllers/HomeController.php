<?php

class HomeController
{
    private AuthService $authService;
    private CourseService $courseService;

    public function __construct(AuthService $authService, CourseService $courseService)
    {
        $this->authService = $authService;
        $this->courseService = $courseService;
    }

    public function index(): void
    {
        $user = $this->authService->getCurrentUser();
        $courses = $this->courseService->getAllCourses();

        include __DIR__ . '/../Views/home.php';
    }
}
