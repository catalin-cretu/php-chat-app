<?php declare(strict_types=1);

namespace ChatApp\Message\Api;


use DateTime;

class Message
{
    /** @var int */
    private $id;

    /** @var int */
    private $userId;

    /** @var DateTime */
    private $timestamp;

    /** @var string */
    private $message;

    public function __construct(int $userId, DateTime $timestamp, string $message, ?int $id = null)
    {
        $this->userId = $userId;
        $this->timestamp = $timestamp;
        $this->message = $message;
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return DateTime
     */
    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}