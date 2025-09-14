<?php

class CourseService
{
    private CourseRepository $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function getAllCourses(): array
    {
        return $this->courseRepository->findAll();
    }

    public function getCourseById(int $id): ?Course
    {
        return $this->courseRepository->findById($id);
    }

    public function createCourse(array $data): Course
    {
        if (empty($data['title']) || empty($data['description']) || empty($data['category'])) {
            throw new Exception('Title, description, and category are required');
        }
        return $this->courseRepository->create($data);
    }

    public function updateCourse(int $id, array $data): bool
    {
        if (empty($data['title']) || empty($data['description']) || empty($data['category'])) {
            throw new Exception('Title, description, and category are required');
        }
        return $this->courseRepository->update($id, $data);
    }

    public function deleteCourse(int $id): bool
    {
        return $this->courseRepository->delete($id);
    }
}
