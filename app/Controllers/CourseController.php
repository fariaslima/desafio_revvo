<?php

class CourseController
{
    private CourseService $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index(): void
    {
        $courses = $this->courseService->getAllCourses();
        header('Content-Type: application/json');
        echo json_encode(array_map(fn($course) => $course->toArray(), $courses));
    }

    public function list(): void
    {
        $courses = $this->courseService->getAllCourses();
        $user = null;
        if (isset($_SESSION['user_id'])) {
            $user = (object) ['name' => 'John Doe', 'is_admin' => true];
        }
        require_once __DIR__ . '/../Views/courses/index.php';
    }

    public function show(int $id): void
    {
        $course = $this->courseService->getCourseById($id);
        if (!$course) {
            http_response_code(404);
            echo json_encode(['error' => 'Course not found']);
            return;
        }
        header('Content-Type: application/json');
        echo json_encode($course->toArray());
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'image' => $_POST['image'] ?? null,
            'category' => $_POST['category'] ?? '',
        ];

        try {
            $course = $this->courseService->createCourse($data);
            http_response_code(201);
            echo json_encode($course->toArray());
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'image' => $_POST['image'] ?? null,
            'category' => $_POST['category'] ?? '',
        ];

        try {
            $success = $this->courseService->updateCourse($id, $data);
            if ($success) {
                echo json_encode(['message' => 'Course updated successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Course not found']);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function destroy(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $success = $this->courseService->deleteCourse($id);
        if ($success) {
            echo json_encode(['message' => 'Course deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Course not found']);
        }
    }
}
