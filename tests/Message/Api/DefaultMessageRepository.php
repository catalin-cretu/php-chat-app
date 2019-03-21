<?php declare(strict_types=1);

namespace ChatApp\Message\Api;

use ChatApp\Message\Repo\MessageRepository;

class DefaultMessageRepository implements MessageRepository
{
    /** @var Message[] */
    private $messages = [];

    /**
     * @param Message[] $messages
     */
    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    /**
     * @param int $userId
     * @return Message[]
     */
    public function findByUserId(int $userId): array
    {
        return $this->messages;
    }
}