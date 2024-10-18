<?php

namespace App\Handlers;

use App\UserRepository\UserRepository;
use App\TelegramBot\TelegramBot;
use App\UserRepository\UserDTO;


class TelegramHandler implements TelegramHandlerInterface
{
    private UserRepository $userManager;
    private TelegramBot $telegramBot;

    public function __construct(UserRepository $userManager, TelegramBot $telegramBot)
    {
        $this->userManager = $userManager;
        $this->telegramBot = $telegramBot;
    }

    public function processUpdates(): void
    {
        $updates = $this->telegramBot->getUpdates();

        foreach ($updates as $update) {
            $this->processUpdate($update);
        }
    }

    private function processUpdate($update): void
    {
        $telegramId = $update->message->from->id;
        $user = $this->userManager->getUserByTelegramId($telegramId);

        $userDetails = $this->extractUserDetails($update);

        if ($user) {
            $message = $this->handleExistingUser($user, $userDetails['message']);
        } else {
            $message = $this->handleNewUser($telegramId, $userDetails);
        }

        $chatId = $update->message->chat->id;
        $this->telegramBot->sendMessage($chatId, $message);
    }

    private function extractUserDetails($update): array
    {
        return [
            'first_name' => $update->message->from->first_name,
            'last_name' => $update->message->from->last_name,
            'username' => $update->message->from->username,
            'message' => $update->message->text,
        ];
    }

    private function handleExistingUser($user, $userMessage): string
    {
        if ($user->getIsAccess()) {

            $currentTime = date('Y-m-d H:i:s', time());
            return "Текущее время по Гринвичу: $currentTime";
        } else {
            $rightAnswer = $user->getRightAnswer();
            if ((int)$userMessage === (int)$rightAnswer) {

                $user->setIsAccess(true);
                $this->userManager->saveUsers([$user]);

                $currentTime = date('Y-m-d H:i:s', time());
                return "Текущее время по Гринвичу: $currentTime";
            } else {
                return $this->generateMathProblem($rightAnswer);
            }
        }
    }

    private function handleNewUser($telegramId, $userDetails): string
    {

        $rightAnswer = rand(1, 50);

        $newUser = UserDTO::create(
            $telegramId,
            $userDetails['first_name'],
            $userDetails['last_name'],
            $userDetails['username'],
            false,
            $rightAnswer
        );
        $this->userManager->saveUsers([$newUser]);

        return "Отправь любое сообщение, реши пример и получи доступ";
    }

    private function generateMathProblem($rightAnswer): string
    {
        $firstNumber = rand(1, $rightAnswer);
        $secondNumber = $rightAnswer - $firstNumber;
        return "Найди сумму $firstNumber и $secondNumber и получи доступ";
    }
}
