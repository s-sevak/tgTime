<?php

namespace App\UserRepository;

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

    public function getTelegramId(): int
    {
        return $this->telegramId;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getIsAccess(): bool
    {
        return $this->isAccess;
    }

    public function getRightAnswer(): int
    {
        return $this->rightAnswer;
    }

    public function setIsAccess(bool $isAccess): void
    {
        $this->isAccess = $isAccess;
    }
}
