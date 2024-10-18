<?php

namespace App\UserRepository;

class UserUpdateDTO
{
    public function __construct(
        private ?string $firstName = null,
        private ?string $lastName = null,
        private ?string $username = null,
        private ?bool $isAccess = null,
        private ?int $rightAnswer = null,
    ) {}

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getIsAccess(): ?bool
    {
        return $this->isAccess;
    }

    public function getRightAnswer(): ?int
    {
        return $this->rightAnswer;
    }
}
