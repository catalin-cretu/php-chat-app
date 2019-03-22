<?php declare(strict_types=1);

namespace ChatApp\Message\Repo;

use ChatApp\Message\Api\Message;

class DefaultMessageRepository implements MessageRepository
{
    /** @var Message[] */
    private $messages;

    /**
     * @param Message[] $messages
     */
    public function __construct(array $messages = [])
    {
        $this->messages = $messages;
    }

    /**
     * @param int $userId
     * @return Message[]
     */
    public function findByUserId(int $userId): array
    {
        $foundMessage = [];

        foreach ($this->messages as $message) {
            if ($message->getUserId() === $userId) {
                $foundMessage[] = $message;
            }
        }
        return $foundMessage;
    }
}