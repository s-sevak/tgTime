<?php

namespace App\UserManager;
use PDO;

class UserManager implements UserManagerInterface
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
                $updates = [];
                if ($existingUser['first_name'] !== $user->getFirstName()) {
                    $updates['first_name'] = $user->getFirstName();
                }
                if ($existingUser['last_name'] !== $user->getLastName()) {
                    $updates['last_name'] = $user->getLastName();
                }
                if ($existingUser['username'] !== $user->getUserName()) {
                    $updates['username'] = $user->getUserName();
                }


                if ((int)$existingUser['is_access'] !== (int)$user->getIsAccess()) {
                    $updates['is_access'] = (int)$user->getIsAccess();
                }

                if ((int)$existingUser['right_answer'] !== (int)$user->getRightAnswer()) {
                    $updates['right_answer'] = (int)$user->getRightAnswer();
                }

                if (!empty($updates)) {
                    $setClause = [];
                    $params = [];
                    foreach ($updates as $column => $value) {
                        $setClause[] = "$column = :$column";
                        $params[":$column"] = $value;
                    }
                    $params[':telegramId'] = $telegramId;

                    $updateSql = "UPDATE users SET " . implode(", ", $setClause) . " WHERE telegram_id = :telegramId";
                    $updateStmt = $this->dbConnection->prepare($updateSql);
                    $updateStmt->execute($params);
                }
            } else {
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
        }
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
