<?php

namespace App\UserManager;

interface UserManagerInterface
{
    public function saveUsers(array $users): void;

    public function getUserByTelegramId(int $telegramId): ?UserDTO;
}
