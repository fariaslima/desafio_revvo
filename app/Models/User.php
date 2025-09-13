<?php

class User
{
    public int $id;
    public string $name;
    public string $email;
    public string $password;
    public bool $is_admin;
    public bool $modal_shown;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->is_admin = $data['is_admin'] ?? false;
        $this->modal_shown = $data['modal_shown'] ?? false;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'is_admin' => $this->is_admin,
            'modal_shown' => $this->modal_shown,
        ];
    }
}
