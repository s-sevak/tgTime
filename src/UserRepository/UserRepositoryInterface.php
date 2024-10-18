<?php

namespace App\UserRepository;

interface UserRepositoryInterface
{
    public function saveUsers(array $users): void;

    public function getUserByTelegramId(int $telegramId): ?UserDTO;
}
