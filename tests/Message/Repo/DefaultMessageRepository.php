<?php declare(strict_types=1);

namespace ChatApp\Message\Repo;

use ChatApp\Message\Api\Message;
use DateTime;
use Exception;

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

    /**
     * @param Message $message
     * @return Message
     * @throws Exception
     */
    public function save(Message $message): Message
    {
        $newMessage = new Message($message->getUserId(), new DateTime(), $message->getMessage(), $message->getUserId());
        $this->messages[] = $newMessage;

        return $newMessage;
    }
}