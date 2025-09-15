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

    private function handleImageUpload(): ?string
    {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES['image'];

        // Validate file size (max 1MB)
        if ($file['size'] > 1024 * 1024) {
            throw new Exception('A imagem deve ter no máximo 1MB.');
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Tipo de imagem inválido. Permitidos: jpg, jpeg, png, webp.');
        }

        // Validate image dimensions
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            throw new Exception('Arquivo não é uma imagem válida.');
        }
        // Dimension validation removed as per user request

        // Generate unique filename
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('course_', true) . '.' . $ext;

        $destination = __DIR__ . '/../../storage/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception('Erro ao salvar a imagem.');
        }

        return $filename;
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        try {
            $imageFilename = $this->handleImageUpload();

            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'image' => $imageFilename,
                'link' => $_POST['link'] ?? '',
            ];

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

        try {
            $imageFilename = $this->handleImageUpload();

            $data = [];

            if (isset($_POST['title']) && $_POST['title'] !== '') {
                $data['title'] = $_POST['title'];
            }
            if (isset($_POST['description']) && $_POST['description'] !== '') {
                $data['description'] = $_POST['description'];
            }
            if (isset($_POST['link']) && $_POST['link'] !== '') {
                $data['link'] = $_POST['link'];
            }
            if ($imageFilename !== null) {
                $data['image'] = $imageFilename;
            }

            if (empty($data)) {
                throw new Exception('Nenhum dado para atualizar');
            }

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
