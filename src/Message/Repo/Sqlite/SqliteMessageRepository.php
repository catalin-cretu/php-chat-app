<?php declare(strict_types=1);

namespace ChatApp\Message\Repo\Sqlite;


use ChatApp\Message\Api\Message;
use ChatApp\Message\Repo\MessageRepository;

class SqliteMessageRepository implements MessageRepository
{

    /**
     * @param int $userId
     * @return Message[]
     */
    public function findByUserId(int $userId): array
    {
        return [];
    }
}