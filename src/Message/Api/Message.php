<?php


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

    public function __construct(int $id, DateTime $timestamp, string $message, int $userId)
    {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->message = $message;
        $this->userId = $userId;
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