<?php

class UserDTO
{
    private function __construct(
        private int          $telegramId,
        private string       $firstName,
        private string       $lastName,
        private string       $userName,
        private bool         $isAccess,
        private readonly int $rightAnswer
    )
    {
    }

    public static function create(
        int    $telegramId,
        string $firstName,
        string $lastName,
        string $userName,
        bool   $isAccess,
        int    $rightAnswer
    ): self
    {
        return new self($telegramId, $firstName, $lastName, $userName, $isAccess, $rightAnswer);
    }
}
