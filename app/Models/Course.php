<?php

class Course
{
    public int $id;
    public string $title;
    public string $description;
    public ?string $image;
    public string $link;
    public string $created_at;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? 0;
        $this->title = $data['title'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->image = $data['image'] ?? null;
        $this->link = $data['link'] ?? '';
        $this->created_at = $data['created_at'] ?? '';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'link' => $this->link,
            'created_at' => $this->created_at,
        ];
    }
}
