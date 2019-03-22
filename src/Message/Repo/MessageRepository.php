<?php declare(strict_types=1);

namespace ChatApp\Message\Repo;


use ChatApp\Message\Api\Message;

interface MessageRepository
{
    /**
     * @param int $userId
     * @return Message[]
     */
    public function findByUserId(int $userId): array;

    public function save(Message $message): Message;
}