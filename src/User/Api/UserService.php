<?php declare(strict_types=1);

namespace ChatApp\User\Api;


use ChatApp\Common\Api\Result;
use ChatApp\Message\Repo\MessageRepository;

require_once __DIR__ . '/../../Common/Api/Result.php';

class UserService
{
    /** @var MessageRepository */
    private $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
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