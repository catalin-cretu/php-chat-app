<?php declare(strict_types=1);

namespace ChatApp\User\WebApi;


use ChatApp\Common\WebApi\ErrorsResponse;

require_once __DIR__ . '/../../Common/WebApi/ErrorsResponse.php';

class MessagesResponse extends ErrorsResponse
{
    /** @var MessageView[] */
    public $messages;

    /**
     * @param MessageView[] $messageViews
     * @param string[] $errors
     */
    public function __construct(array $messageViews = [], array $errors = [])
    {
        $this->messages = $messageViews;
        $this->errors = $errors;
    }
}

class MessageView
{
    /** @var int */
    public $id;

    /** @var int */
    public $user;

    /** @var string */
    public $timestamp;

    /** @var string */
    public $message;

    public function __construct(int $id, int $user, string $timestamp, string $message)
    {
        $this->id = $id;
        $this->user = $user;
        $this->timestamp = $timestamp;
        $this->message = $message;
    }
}