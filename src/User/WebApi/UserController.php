<?php declare(strict_types=1);

namespace ChatApp\User\WebApi;


use ChatApp\Common\Api\Result;
use ChatApp\Message\Api\Message;
use ChatApp\User\Api\UserService;
use DateTime;

require_once 'MessagesResponse.php';

class UserController
{
    /** @var UserService */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param int $userId
     * @return MessagesResponse
     */
    public function getMessages(int $userId): MessagesResponse
    {
        $messages = $this->userService->findMessages($userId);

        return self::toMessageResponse($messages);
    }

    private static function toMessageResponse(Result $result): MessagesResponse
    {
        if ($result->hasErrors()) {
            return new MessagesResponse([], $result->getErrors());
        }
        $messageViews = array_map(
            function (Message $message) {
                return new MessageView(
                    $message->getId(),
                    $message->getUserId(),
                    self::toIsoDateTime($message->getTimestamp()),
                    $message->getMessage());
            },
            $result->get());
        return new MessagesResponse($messageViews);
    }

    private static function toIsoDateTime(DateTime $getTimestamp): string
    {
        return $getTimestamp->format(DateTime::ATOM);
    }
}