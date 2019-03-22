<?php declare(strict_types=1);

namespace ChatApp\User\WebApi;


use ChatApp\Common\Api\Result;
use ChatApp\Message\Api\Message;
use ChatApp\User\Api\User;
use ChatApp\User\Api\UserService;
use DateTime;

require_once 'MessagesResponse.php';
require_once 'UsersResponse.php';

class UserController
{
    /** @var UserService */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return UsersResponse
     */
    public function getAllUsers(): UsersResponse
    {
        $usersResult = $this->userService->findAllUsers();

        return self::toUsersResponse($usersResult);
    }

    private static function toUsersResponse(Result $usersResult): UsersResponse
    {
        if ($usersResult->hasErrors()) {
            return new UsersResponse([], $usersResult->getErrors());
        }
        $userViews = array_map(
            function (User $user) {
                return new UserView($user->getId());
            },
            $usersResult->get());

        return new UsersResponse($userViews);
    }

    /**
     * @param int $userId
     * @return MessagesResponse
     */
    public function getMessages(int $userId): MessagesResponse
    {
        $messagesResult = $this->userService->findMessages($userId);

        return self::toMessagesResponse($messagesResult);
    }

    private static function toMessagesResponse(Result $messagesResult): MessagesResponse
    {
        if ($messagesResult->hasErrors()) {
            return new MessagesResponse([], $messagesResult->getErrors());
        }
        $messageViews = array_map(
            function (Message $message) {
                return new MessageView(
                    $message->getId(),
                    $message->getUserId(),
                    self::toIsoDateTime($message->getTimestamp()),
                    $message->getMessage());
            },
            $messagesResult->get());
        return new MessagesResponse($messageViews);
    }

    private static function toIsoDateTime(DateTime $getTimestamp): string
    {
        return $getTimestamp->format(DateTime::ATOM);
    }
}