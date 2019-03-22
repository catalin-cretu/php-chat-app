<?php declare(strict_types=1);

namespace ChatApp\User\Api;


use ChatApp\Common\Api\Result;
use ChatApp\Message\Repo\MessageRepository;
use ChatApp\User\Repo\UserRepository;

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

    /**
     * @return Result
     */
    public function findAllUsers(): Result
    {
        $users = $this->userRepository->findAll();

        return Result::ok($users);
    }

    /**
     * @param int $userId
     * @return Result
     */
    public function findMessages(int $userId): Result
    {
        $messages = $this->messageRepository->findByUserId($userId);

        if (!empty($messages)) {
            return Result::ok($messages);
        }
        return Result::errors(["Cannot find user with id $userId"]);
    }
}