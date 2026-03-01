<?php

namespace App\Models;

use App\Core\Model;

class Admin extends Model
{
    protected string $table = 'admins';

    public function findByEmail(string $email)
    {
        return $this->findBy('email', $email);
    }

    public function verifyPassword(string $email, string $password): bool
    {
        $admin = $this->findByEmail($email);
        if (!$admin) {
            return false;
        }
        return password_verify($password, $admin['password']);
    }

    public function createAdmin(string $email, string $password, string $name, string $role = 'editor'): bool
    {
        return $this->create([
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_ARGON2ID),
            ':name' => $name,
            ':role' => $role,
        ]);
    }
}
