<?php declare(strict_types=1);

namespace ChatApp\User\Api;


use ChatApp\Common\Api\Result;
use ChatApp\Message\Api\Message;
use ChatApp\Message\Repo\MessageRepository;
use ChatApp\User\Repo\UserRepository;
use DateTime;
use Exception;

require_once __DIR__ . '/../../Common/Api/Result.php';

class UserService
{
    /** @var UserRepository */
    private $userRepository;

    /** @var MessageRepository */
    private $messageRepository;

    public function __construct(UserRepository $userRepository, MessageRepository $messageRepository)
    {
        $this->userRepository = $userRepository;
        $this->messageRepository = $messageRepository;
    }

    public function findAllUsers(): Result
    {
        $users = $this->userRepository->findAll();

        return Result::ok($users);
    }

    public function findMessages(int $userId): Result
    {
        $userExists = $this->userRepository->exists($userId);
        if (!$userExists) {
            return Result::errors(["Cannot find user with id $userId"]);
        }
        $messages = $this->messageRepository->findByUserId($userId);
        return Result::ok($messages);
    }

    /**
     * @param int $userId
     * @param int|null $senderId
     * @param string|null $message
     * @return Result
     * @throws Exception
     */
    public function createMessage(int $userId, ?int $senderId, ?string $message): Result
    {
        $userExists = $this->userRepository->exists($userId);
        if (!$userExists) {
            return Result::errors(["Cannot find user with id $userId"]);
        }
        if ($senderId === null) {
            return Result::errors(["Sender user id ('sender') must not be blank"]);
        }
        if ($message === null
            || empty(trim($message))) {
            return Result::errors(["Content of message ('message') must not be blank"]);
        }
        $senderExists = $this->userRepository->exists($senderId);
        if (!$senderExists) {
            return Result::errors(["Cannot find sender user with id $senderId"]);
        }
        $savedMessage = $this->messageRepository->save(new Message($senderId, new DateTime(), $message));
        return Result::ok($savedMessage);
    }
}