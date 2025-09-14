<?php

class CourseRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM courses ORDER BY created_at DESC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new Course($row), $data);
    }

    public function findById(int $id): ?Course
    {
        $stmt = $this->pdo->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Course($data) : null;
    }

    public function create(array $data): Course
    {
        $stmt = $this->pdo->prepare("INSERT INTO courses (title, description, image, category) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['title'],
            $data['description'],
            $data['image'] ?? null,
            $data['category'],
        ]);
        $data['id'] = $this->pdo->lastInsertId();
        $data['created_at'] = date('Y-m-d H:i:s');
        return new Course($data);
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $values = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id;
        $stmt = $this->pdo->prepare("UPDATE courses SET " . implode(', ', $fields) . " WHERE id = ?");
        return $stmt->execute($values);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM courses WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
