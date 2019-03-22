<?php declare(strict_types=1);

namespace ChatApp\User\WebApi;


class CreateUserMessageRequest
{
    /** @var int */
    public $sender;

    /** @var string */
    public $message;

    public function __construct(?int $sender = null, ?string $message = null)
    {
        $this->sender = $sender;
        $this->message = $message;
    }
}

use ChatApp\Common\WebApi\ErrorsResponse;

require_once __DIR__ . '/../../Common/WebApi/ErrorsResponse.php';

class CreateUserMessageResponse extends ErrorsResponse
{
    /** @var MessageView */
    public $message;

    /**
     * @param MessageView|null $messageView
     * @param string[] $errors
     */
    public function __construct(?MessageView $messageView, array $errors = [])
    {
        $this->errors = $errors;
        $this->message = $messageView;
    }
}