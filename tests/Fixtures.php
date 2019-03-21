<?php declare(strict_types=1);

namespace ChatApp;


use ChatApp\Message\Api\DefaultMessageRepository;
use ChatApp\Message\Api\Message;
use ChatApp\User\Api\UserService;
use ChatApp\User\WebApi\UserController;


class Fixtures
{
    /**
     * @param Message[] $messages
     * @return UserController
     */
    public static function newUserController(array $messages): UserController
    {
        return new UserController(self::newUserService($messages));
    }

    /**
     * @param array $messages
     * @return UserService
     */
    public static function newUserService(array $messages): UserService
    {
        return new UserService(new DefaultMessageRepository($messages));
    }
}