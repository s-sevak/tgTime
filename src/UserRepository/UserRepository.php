<?php

namespace App\UserRepository;

use PDO;

class UserRepository implements UserRepositoryInterface
{
    private PDO $dbConnection;

    public function __construct(PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function saveUsers(array $users): void
    {
        foreach ($users as $user) {
            $telegramId = $user->getTelegramId();

            $stmt = $this->dbConnection->prepare("SELECT * FROM users WHERE telegram_id = :telegramId");
            $stmt->bindParam(':telegramId', $telegramId, PDO::PARAM_INT);
            $stmt->execute();
            $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                $updates = $this->getUpdatedFields($existingUser, $user);

                if ($updates) {
                    $this->updateUser($telegramId, $updates);
                }
            } else {
                $this->insertUser($user);
            }
        }
    }

    private function getUpdatedFields(array $existingUser, UserDTO $user): ?UserUpdateDTO
    {
        $updates = new UserUpdateDTO(
            $existingUser['first_name'] !== $user->getFirstName() ? $user->getFirstName() : null,
            $existingUser['last_name'] !== $user->getLastName() ? $user->getLastName() : null,
            $existingUser['username'] !== $user->getUserName() ? $user->getUserName() : null,
            (int)$existingUser['is_access'] !== (int)$user->getIsAccess() ? (int)$user->getIsAccess() : null,
            (int)$existingUser['right_answer'] !== (int)$user->getRightAnswer() ? (int)$user->getRightAnswer() : null
        );

        return $updates->getFirstName() || $updates->getLastName() || $updates->getUsername() ||
        $updates->getIsAccess() !== null || $updates->getRightAnswer() !== null ? $updates : null;
    }

    private function updateUser(int $telegramId, UserUpdateDTO $updates): void
    {
        $fields = [
            'first_name' => $updates->getFirstName(),
            'last_name' => $updates->getLastName(),
            'username' => $updates->getUsername(),
            'is_access' => $updates->getIsAccess(),
            'right_answer' => $updates->getRightAnswer(),
        ];

        $setClause = [];
        $params = [':telegramId' => $telegramId];

        foreach ($fields as $column => $value) {
            if ($value !== null) {
                $setClause[] = "$column = :$column";
                $params[":$column"] = $value;
            }
        }

        if (!empty($setClause)) {
            $updateSql = "UPDATE users SET " . implode(", ", $setClause) . " WHERE telegram_id = :telegramId";
            $updateStmt = $this->dbConnection->prepare($updateSql);
            $updateStmt->execute($params);
        }
    }

    private function insertUser(UserDTO $user): void
    {
        $stmt = $this->dbConnection->prepare(
            "INSERT INTO users (telegram_id, first_name, last_name, username, is_access, right_answer) 
             VALUES (:telegramId, :firstName, :lastName, :username, :isAccess, :rightAnswer)"
        );
        $stmt->execute([
            ':telegramId' => $user->getTelegramId(),
            ':firstName' => $user->getFirstName(),
            ':lastName' => $user->getLastName(),
            ':username' => $user->getUserName(),
            ':isAccess' => (int)$user->getIsAccess(),
            ':rightAnswer' => $user->getRightAnswer()
        ]);
    }

    public function getUserByTelegramId(int $telegramId): ?UserDTO
    {
        $stmt = $this->dbConnection->prepare("SELECT * FROM users WHERE telegram_id = :telegramId");
        $stmt->bindParam(':telegramId', $telegramId, PDO::PARAM_INT);
        $stmt->execute();

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            return UserDTO::create(
                $userData['telegram_id'],
                $userData['first_name'],
                $userData['last_name'],
                $userData['username'],
                (bool)$userData['is_access'],
                (int)$userData['right_answer']
            );
        }

        return null;
    }
}
